<?php

namespace App\Repositories;

class TransactionsRepository extends BaseRepository
{
    protected const TABLE = 'transactions';

    public function search($data)
    {
        $query = $this->model->select('transactions.id as transaction_id, sender_id, receiver_id, amount, cs.name as sender_name, cr.name as receiver_name');
        $query->join('customers cs', 'transactions.sender_id = cs.id and cs.date_del is null', 'inner');
        $query->join('customers cr', 'transactions.receiver_id = cr.id and cr.date_del is null', 'inner');

        $query->where('transactions.date_del', null);

        if (!empty($data->sender_id)) {
            $query->where('transactions.sender_id', $data->sender_id);
        }

        if (!empty($data->receiver_id)) {
            $query->where('transactions.receiver_id', $data->receiver_id);
        }

        if (!empty($data->id)) {
            $query->where('transactions.id', $data->id);
        }
        $query->orderBy('transactions.id', 'desc');

        $result = $query->find();

        return $result;
    }
}
