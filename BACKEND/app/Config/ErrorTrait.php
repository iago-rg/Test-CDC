<?php

namespace Config;

class ErrorTrait extends \Exception
{
    protected string $statusCode;
    protected array $errors;

    public function __construct(string $statusCode, ?array $errors = [])
    {
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        ResponseTrait::Error($statusCode, $errors);
    }

    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
