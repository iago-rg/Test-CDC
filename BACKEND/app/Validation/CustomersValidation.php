<?php

namespace App\Validation;

use App\Interfaces\ValidationInterface;

class CustomersValidation implements ValidationInterface
{
    protected const SEARCH_RULES = [
    ];
    protected const CREATE_RULES = [
        'name' => [
            'label' => 'Email',
            'rules' => ['required'],
        ],
        'document_id' => [
            'label' => 'CPF/CNPJ',
            'rules' => ['required', 'numeric'],
        ],
        'birth_date' => [
            'label' => 'Data de nascimento ou fundação',
            'rules' => ['required', 'valid_date[Y-m-d]'],
        ],
        'monthly_income' => [
            'label' => 'Renda mensal',
            'rules' => ['required', 'numeric', 'greater_than_equal_to[0]'],
        ],
        'balance' => [
            'label' => 'Saldo',
            'rules' => ['required', 'numeric', 'greater_than_equal_to[0]'],
        ],
        'id_user_ins' => [
            'label' => 'Identificador do usuário de inserção',
            'rules' => ['required', 'is_not_unique[users.id]'],
        ],
    ];

    protected const UPDATE_RULES = [
        'id' => [
            'label' => 'Identificador',
            'rules' => ['required', 'is_not_unique[customers.id]'],
        ],
        'name' => [
            'label' => 'Email',
            'rules' => ['permit_empty'],
        ],
        'birth_date' => [
            'label' => 'Data de nascimento ou fundação',
            'rules' => ['permit_empty', 'valid_date[Y-m-d]'],
        ],
        'monthly_income' => [
            'label' => 'Renda mensal',
            'rules' => ['permit_empty', 'numeric', 'greater_than_equal_to[0]'],
        ],
    ];
    protected const DELETE_RULES = [
        'id' => [
            'label' => 'Identificador',
            'rules' => ['required'],
        ],
        'id_user_del' => [
            'label' => 'Identificador do usuário de exclusão',
            'rules' => ['required', 'is_not_unique[users.id]'],
        ],
    ];
    public function getRequestRules(string $method): array
    {
        switch ($method) {
            case 'SEARCH':
                $rules = self::SEARCH_RULES;

                break;
            case 'CREATE':
                $rules = self::CREATE_RULES;

                break;
            case 'UPDATE':
                $rules = self::UPDATE_RULES;

                break;
            case 'DELETE':
                $rules = self::DELETE_RULES;

                break;
            default:
                $rules = self::SEARCH_RULES;

                break;
        }

        return $rules;
    }
}
