<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CustomersEntity extends Entity
{
    protected $attributes = [
        'id_user_ins' => null,
        'id_user_del' => null,
        'name' => null,
        'document_id' => null,
        'birth_date' => null,
        'monthly_in' => null,
        'balance' => null,
    ];
}
