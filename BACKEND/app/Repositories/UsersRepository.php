<?php

namespace App\Repositories;

class UsersRepository extends BaseRepository
{
    protected const TABLE = 'users';

    public function getUser($data)
    {
        $query = $this->model->select('id, email, password_hash')->where('email', $data['email'])->first();

        return $query;
    }
}
