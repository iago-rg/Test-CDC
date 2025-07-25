<?php

namespace App\Controllers;

use App\Entities\UsersEntity;
use App\Models\UsersModel;
use App\Repositories\UsersRepository;
use App\Services\AuthService;
use App\Validation\AuthValidation;
use Config\ResponseTrait;

class Auth extends BaseController
{
    public function login()
    {
        $rules = (new AuthValidation())->getRequestRules('LOGIN');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }

        $data = $this->request->getJson(true);
        $model = new UsersModel();
        $repository = new UsersRepository($model);
        $UserFound = $repository->getUser($data);

        if (!$UserFound) {
            ResponseTrait::Error('EM0002');
        }

        if (!password_verify($data['password'], $UserFound['password_hash'])) {
            ResponseTrait::Error('EM0002');
        }

        $JWTGenerator = new AuthService();
        $token = $JWTGenerator->JWT($UserFound);

        ResponseTrait::Success('SM0001', ['token' => $token, 'id_user_ins' => $UserFound['id']]);
    }

    public function register()
    {
        $rules = (new AuthValidation())->getRequestRules('REGISTER');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }

        $data = $this->request->getJson(true);
        //$data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $model = new UsersModel();
        $repository = new UsersRepository($model);
        $entity = new UsersEntity($data);

        $repository->create($entity, 'EM0003');

        ResponseTrait::Success('SM0002');
    }
}
