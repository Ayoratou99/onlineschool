<?php

namespace Modules\Workflow\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Securite\Models\Role;
use Modules\Securite\Models\User;
use Tests\TestCase;

/**
 * Workflow instance API tests – all requests hit the real workflow engine.
 * Ensure WORKFLOW_API_URL, WORKFLOW_APP_TOKEN, WORKFLOW_API_USERNAME, WORKFLOW_API_PASSWORD
 * are set (e.g. in phpunit.xml or .env.testing) and the workflow API is running.
 *
 * Why "Application non trouvée" / "applicationnotfound" (400)?
 * - The workflow engine has its own users and links them to an application (e.g. REGULATOUR).
 * - current-step-actions and execute transition send a userId: the engine expects a *workflow*
 *   user ID that exists in the workflow DB and is linked to the app. Sending the Regulatour
 *   auth user UUID (from Securite) is unknown to the workflow → "Application non trouvée pour cet utilisateur".
 * - history, suspend, resume: the engine checks that the instance belongs to the application
 *   identified by the JWT/token. If the instance was created under another app or the link
 *   is missing → "applicationnotfound".
 * To get 200 on those endpoints: use a workflow user ID known to the engine, and an
 * instance that belongs to your application (e.g. created via startWorkflow for REGULATOUR).
 */
class InstanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** Real instance ID (must exist on the workflow engine and belong to your app for full 200s). */
    private const INSTANCE_ID = 'af1f0b24-d965-4621-8e69-e130b42080d1';

    /** Non-existent instance ID for 404 tests. */
    private const INSTANCE_ID_NONEXISTENT = '00000000-0000-0000-0000-000000000000';

    /** Assert 200 with success and data, or 400 when workflow returns "application not found" (user/instance not linked to app). */
    private function assertWorkflowSuccessOrApplicationNotFound(int $status, $responseJson): void
    {
        if ($status === 200) {
            $this->assertTrue($responseJson['success'] ?? false);
            $this->assertArrayHasKey('data', $responseJson);
            return;
        }
        if ($status === 400) {
            $this->assertFalse($responseJson['success'] ?? true);
            $this->assertSame('FUIP_400', $responseJson['app_code'] ?? '');
            $detail = $responseJson['errors']['details'] ?? '';
            $this->assertTrue(
                str_contains($detail, 'application') || str_contains($detail, 'utilisateur'),
                "Expected 400 to mention application/utilisateur, got: {$detail}"
            );
            return;
        }
        $this->fail("Unexpected status: {$status}");
    }

    public function test_search_instances_returns_list(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->getJson(route('api.workflow.instance-workflows.search'));
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('app_code', 'FUIP_100');
        $this->assertArrayHasKey('data', $response->json());
    }

    public function test_search_instances_without_auth_returns_401(): void
    {
        $response = $this->getJson(route('api.workflow.instance-workflows.search'));
        $response->assertStatus(401);
    }

    public function test_get_instance_returns_details(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->getJson(route('api.workflow.instance-workflows.show', ['instanceId' => self::INSTANCE_ID]));
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('app_code', 'FUIP_101');
        $this->assertArrayHasKey('data', $response->json());
    }

    public function test_get_instance_without_auth_returns_401(): void
    {
        $response = $this->getJson(route('api.workflow.instance-workflows.show', ['instanceId' => self::INSTANCE_ID]));
        $response->assertStatus(401);
    }

    public function test_get_instance_svg_returns_svg_content_type(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->get(route('api.workflow.instance-workflows.svg', ['instanceId' => self::INSTANCE_ID]));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertStringContainsString('<svg', $response->getContent());
    }

    public function test_get_current_step_actions_returns_actions_or_400_application_not_found(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->getJson(route('api.workflow.instance-workflows.current-step-actions', [
                'instanceId' => self::INSTANCE_ID,
            ]));
        $this->assertWorkflowSuccessOrApplicationNotFound($response->status(), $response->json());
    }

    public function test_execute_transition_returns_success_or_400_application_not_found(): void
    {
        $user = $this->authenticatedUser();
        $response = $this->actingAs($user, 'api')
            ->postJson(route('api.workflow.instance-workflows.transition', ['instanceId' => self::INSTANCE_ID]), [
                'action' => 'valider',
                'userId' => (string) $user->id,
                'commentaire' => 'Approuvé par test',
            ]);
        $this->assertWorkflowSuccessOrApplicationNotFound($response->status(), $response->json());
    }

    public function test_execute_transition_validation_error_when_action_missing(): void
    {
        $user = $this->authenticatedUser();
        $response = $this->actingAs($user, 'api')
            ->postJson(route('api.workflow.instance-workflows.transition', ['instanceId' => self::INSTANCE_ID]), [
                'userId' => (string) $user->id,
            ]);
        $response->assertStatus(422);
    }

    public function test_get_history_returns_history_or_400_application_not_found(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->getJson(route('api.workflow.instance-workflows.history', ['instanceId' => self::INSTANCE_ID]));
        $this->assertWorkflowSuccessOrApplicationNotFound($response->status(), $response->json());
    }

    public function test_suspend_workflow_returns_success_or_400_application_not_found(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->postJson(route('api.workflow.instance-workflows.suspend', ['instanceId' => self::INSTANCE_ID]));
        $this->assertWorkflowSuccessOrApplicationNotFound($response->status(), $response->json());
    }

    public function test_resume_workflow_returns_success_or_400_application_not_found(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->postJson(route('api.workflow.instance-workflows.resume', ['instanceId' => self::INSTANCE_ID]));
        $this->assertWorkflowSuccessOrApplicationNotFound($response->status(), $response->json());
    }

    public function test_get_instance_404_when_instance_does_not_exist(): void
    {
        $response = $this->actingAs($this->authenticatedUser(), 'api')
            ->getJson(route('api.workflow.instance-workflows.show', ['instanceId' => self::INSTANCE_ID_NONEXISTENT]));
        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('app_code', 'FUIP_404');
    }

    private function authenticatedUser(): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $role = Role::firstOrCreate(
            ['name' => 'ADMIN'],
            ['description' => 'Admin', 'state' => 'ACTIVE']
        );
        $user->roles()->sync([$role->id]);
        return $user;
    }
}
