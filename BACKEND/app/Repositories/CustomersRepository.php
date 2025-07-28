<?php

namespace App\Repositories;

class CustomersRepository extends BaseRepository
{
    protected const TABLE = 'customers';

    public function search($data)
    {
        $query = $this->model->select('name, document_id, birth_date, monthly_income, balance, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS idade');

        if (!empty($data->name)) {
            $query->like('name', $data->name);
        }

        if (!empty($data->document_id)) {
            $query->where('document_id', $data->document_id);
        }

        if (!empty($data->search)) {
            $query->groupStart();
            $query->like('name', $data->search);
            $query->orLike('document_id', $data->search);
            $query->groupEnd();
        }

        $query->where('date_del', null);
        $query->orderBy('id', 'desc');

        $result = $query->find();

        return $result;
    }

    public function searchDocumentID($data)
    {
        $query = $this->model->select('id');

        $query->where('date_del', null);
        $query->where('document_id', $data);

        $result = $query->find();

        return $result;
    }
}
