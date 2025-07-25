<?php

namespace App\Services;

class GeneralService
{
    public function isValidBirthDate(?string $date): bool
    {
        if (empty($date)) {
            return false;
        }

        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return false;
        }

        $min = strtotime('1900-01-01');
        $max = strtotime(date('Y-m-d'));

        return $timestamp >= $min && $timestamp <= $max;
    }
}
