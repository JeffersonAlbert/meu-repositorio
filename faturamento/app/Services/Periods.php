<?php

namespace App\Services;

class Periods
{
    public function setPeriod($period)
    {
        if ($period == 'today') {
            $billingDateRangeStart = date('Y-m-d');
            $billingDateRangeEnd = date('Y-m-d');
            $queryPeriod = 'Hoje';
        }

        if ($period == 'thisWeek') {
            $billingDateRangeStart = date('Y-m-d', strtotime('monday this week'));
            $billingDateRangeEnd = date('Y-m-d', strtotime('sunday this week'));
            $queryPeriod = 'Esta semana';
        }

        if ($period == 'thisMonth') {
            $billingDateRangeStart = date('Y-m-01');
            $billingDateRangeEnd = date('Y-m-t');
            $queryPeriod = 'Este mês';
        }

        if ($period == 'thisYear') {
            $billingDateRangeStart = date('Y-01-01');
            $billingDateRangeEnd = date('Y-12-31');
            $queryPeriod = 'Este ano';
        }

        if ($period == 'lastTwelveMonts') {
            $billingDateRangeStart = date('Y-m-d', strtotime('-12 months'));
            $billingDateRangeEnd = date('Y-m-d');
            $queryPeriod = 'Últimos 12 meses';
        }

        if ($period == 'allPeriod') {
            $billingDateRangeStart = null;
            $billingDateRangeEnd = null;
            $queryPeriod = 'Todo o período';
        }

        return ['startDate' => $billingDateRangeStart, 'endDate' => $billingDateRangeEnd, 'queryPeriod' => $queryPeriod];
    }
}
