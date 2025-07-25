<?php

namespace App\Validation;

use App\Interfaces\ValidationInterface;

class TransactionsValidation implements ValidationInterface
{
    protected const SEARCH_RULES = [
    ];
    protected const CREATE_RULES = [
        'sender_id' => [
            'label' => 'Identificador do usuário remetente',
            'rules' => ['required', 'is_not_unique[customers.id]', 'numeric'],
        ],
        'receiver_id' => [
            'label' => 'Identificador do usuário destinatário',
            'rules' => ['required', 'is_not_unique[customers.id]', 'numeric'],
        ],
        'amount' => [
            'label' => 'Valor da transação',
            'rules' => ['required', 'greater_than_equal_to[0]', 'numeric'],
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
            default:
                $rules = self::SEARCH_RULES;

                break;
        }

        return $rules;
    }
}
