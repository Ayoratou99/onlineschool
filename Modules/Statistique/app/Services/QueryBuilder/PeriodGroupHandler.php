<?php

namespace Modules\Statistique\Services\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PeriodGroupHandler
{
    /**
     * Ajoute un groupement temporel à la requête.
     *
     * @param  Builder $query    La requête Eloquent en cours
     * @param  string  $column   Colonne date (ex: "created_at")
     * @param  string  $interval Intervalle : hour, day, week, month, year
     * @return string            Alias du champ de période ajouté au SELECT
     */
    public function groupByPeriod(Builder $query, string $column, string $interval): string
    {
        $driver = DB::connection()->getDriverName();

        $alias = "period_$interval";
        $expression = $this->getExpression($column, $interval, $driver);

        $query->addSelect(DB::raw("$expression as $alias"));
        $query->groupBy(DB::raw($expression));
        $query->orderBy(DB::raw($expression));

        return $alias;
    }

    /**
     * Retourne l'expression SQL adaptée au driver et à l'intervalle.
     */
    private function getExpression(string $column, string $interval, string $driver): string
    {
        return match ($interval) {
            'hour'  => $this->hourExpr($column, $driver),
            'day'   => $this->dayExpr($column, $driver),
            'week'  => $this->weekExpr($column, $driver),
            'month' => $this->monthExpr($column, $driver),
            'year'  => $this->yearExpr($column, $driver),
            default => throw new \InvalidArgumentException(
                "Intervalle de période '$interval' non supporté. " .
                "Valeurs acceptées : " . implode(', ', config('statistique.period_intervals', []))
            ),
        };
    }

    private function hourExpr(string $col, string $driver): string
    {
        return match ($driver) {
            'pgsql'  => "DATE_TRUNC('hour', $col)",
            'mysql'  => "DATE_FORMAT($col, '%Y-%m-%d %H:00:00')",
            'sqlite' => "strftime('%Y-%m-%d %H:00:00', $col)",
            default  => "DATE_FORMAT($col, '%Y-%m-%d %H:00:00')",
        };
    }

    private function dayExpr(string $col, string $driver): string
    {
        return match ($driver) {
            'pgsql'  => "($col)::date",
            'mysql'  => "DATE($col)",
            'sqlite' => "DATE($col)",
            default  => "DATE($col)",
        };
    }

    private function weekExpr(string $col, string $driver): string
    {
        return match ($driver) {
            'pgsql'  => "DATE_TRUNC('week', $col)",
            'mysql'  => "STR_TO_DATE(CONCAT(YEARWEEK($col, 1), ' Monday'), '%X%V %W')",
            'sqlite' => "strftime('%Y-%W', $col)",
            default  => "YEARWEEK($col, 1)",
        };
    }

    private function monthExpr(string $col, string $driver): string
    {
        return match ($driver) {
            'pgsql'  => "TO_CHAR($col, 'YYYY-MM')",
            'mysql'  => "DATE_FORMAT($col, '%Y-%m')",
            'sqlite' => "strftime('%Y-%m', $col)",
            default  => "DATE_FORMAT($col, '%Y-%m')",
        };
    }

    private function yearExpr(string $col, string $driver): string
    {
        return match ($driver) {
            'pgsql'  => "EXTRACT(YEAR FROM $col)::int",
            'mysql'  => "YEAR($col)",
            'sqlite' => "strftime('%Y', $col)",
            default  => "YEAR($col)",
        };
    }
}
