<?php

namespace App\Interfaces;

interface ValidationInterface
{
    public function getRequestRules(string $type): array;
}
