<?php

echo "1. Connecting to PostgreSQL...\n";
try {
    $pdo = new PDO(
        'pgsql:host=127.0.0.1;port=5432;dbname=onlineschoolmultitenant_test',
        'onlineschoolmultitenant',
        'onlineschoolmultitenant_password',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "   OK\n";
} catch (Throwable $e) {
    echo "   FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "2. Checking if tenanttest-tenant DB exists...\n";
$stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = ?');
$stmt->execute(['tenanttest-tenant']);
$exists = (bool) $stmt->fetch();
echo "   Exists: " . ($exists ? 'yes' : 'no') . "\n";

if ($exists) {
    echo "3. Dropping tenanttest-tenant for clean test...\n";
    try {
        $pdo->exec('DROP DATABASE IF EXISTS "tenanttest-tenant"');
        echo "   Dropped\n";
    } catch (Throwable $e) {
        echo "   " . $e->getMessage() . "\n";
    }
}

echo "4. Creating tenanttest-tenant...\n";
try {
    $pdo->exec('CREATE DATABASE "tenanttest-tenant" WITH TEMPLATE=template0');
    echo "   Created\n";
} catch (Throwable $e) {
    echo "   " . $e->getMessage() . "\n";
}

echo "Done.\n";
