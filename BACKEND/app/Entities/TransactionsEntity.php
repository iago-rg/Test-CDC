<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TransactionsEntity extends Entity
{
    protected $attributes = [
        'id_user_ins' => null,
        'id_user_del' => null,
        'sender_id' => null,
        'receiver_id' => null,
        'amount' => null,
        'date_transaction' => null,
    ];
}
