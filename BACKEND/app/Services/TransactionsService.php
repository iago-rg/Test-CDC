<?php

namespace App\Services;

use App\Entities\CustomersEntity;
use App\Models\CustomersModel;
use App\Repositories\CustomersRepository;
use Config\ResponseTrait;

class TransactionsService
{
    public function balanceCheck($data)
    {
        $customerModel = new CustomersModel();
        $amountSender = $customerModel->find($data['sender_id']);

        if ($amountSender['balance'] < $data['amount']) {
            ResponseTrait::Error('EM0008');
        }

        return true;
    }

    public function balanceUpdate($data)
    {
        $customerModel = new CustomersModel();
        $customerRepository = new CustomersRepository($customerModel);

        $sender = $customerModel->find($data['sender_id']);
        $receiver = $customerModel->find($data['receiver_id']);

        $sender['balance'] = $sender['balance'] - $data['amount'];
        $receiver['balance'] = $receiver['balance'] + $data['amount'];

        $senderEntity = new CustomersEntity($sender);
        $receiverEntity = new CustomersEntity($receiver);

        $customerRepository->update($senderEntity, 'SM0003', 'EM0006');
        $customerRepository->update($receiverEntity, 'SM0003', 'EM0006');

        return true;
    }
}
