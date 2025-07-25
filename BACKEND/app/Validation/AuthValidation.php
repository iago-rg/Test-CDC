<?php

namespace App\Validation;

use App\Interfaces\ValidationInterface;

class AuthValidation implements ValidationInterface
{
    protected const SEARCH_RULES = [
    ];
    protected const LOGIN_RULES = [
        'email' => [
            'label' => 'Email',
            'rules' => ['required', 'valid_email'],
        ],
        'password' => [
            'label' => 'Senha',
            'rules' => ['required'],
        ],
    ];

    protected const REGISTER_RULES = [
        'email' => [
            'label' => 'Email',
            'rules' => ['required', 'valid_email'],
        ],
        'password_hash' => [
            'label' => 'Senha',
            'rules' => ['required'],
        ],
    ];

    protected const UPDATE_RULES = [
    ];
    public function getRequestRules(string $method): array
    {
        switch ($method) {
            case 'SEARCH':
                $rules = self::SEARCH_RULES;

                break;
            case 'LOGIN':
                $rules = self::LOGIN_RULES;

                break;
            case 'REGISTER':
                $rules = self::REGISTER_RULES;

                break;
            default:
                $rules = self::SEARCH_RULES;

                break;
        }

        return $rules;
    }
}
