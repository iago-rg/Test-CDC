<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UsersEntity extends Entity
{
    protected $attributes = [
        'email' => null,
        'password_hash' => null,
    ];
}
