<?php

namespace App\Controllers;

use App\Entities\TransactionsEntity;
use App\Models\TransactionsModel;
use App\Repositories\TransactionsRepository;
use App\Services\TransactionsService;
use App\Validation\TransactionsValidation;
use Config\ResponseTrait;

class Transactions extends BaseController
{
    public function search()
    {
        $data = (object)$this->request->getGet();
        $repository = new TransactionsRepository(new TransactionsModel());
        $search = $repository->search($data);

        if (empty($search)) {
            ResponseTrait::Success('SM0008', []);
        }

        ResponseTrait::Success('SM0009', $search);
    }

    public function create()
    {
        $rules = (new TransactionsValidation())->getRequestRules('CREATE');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }
        $data = $this->request->getJSON(true);

        if ($data['sender_id'] == $data['receiver_id']) {
            ResponseTrait::Error('EM0011');
        }

        $service = new TransactionsService();
        $service->balanceCheck($data);
        $service->balanceUpdate($data);

        $repository = new TransactionsRepository(new TransactionsModel());
        $entity = new TransactionsEntity($data);

        $repository->create($entity, 'EM0009');

        ResponseTrait::Success('SM0010');
    }
}
