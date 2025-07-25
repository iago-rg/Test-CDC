<?php

namespace App\Controllers;

use App\Entities\CustomersEntity;
use App\Models\CustomersModel;
use App\Repositories\CustomersRepository;
use App\Services\GeneralService;
use App\Validation\CustomersValidation;
use Config\ResponseTrait;

class Customers extends BaseController
{
    public function search()
    {
        $data = (object)$this->request->getGet();
        $repository = new CustomersRepository(new CustomersModel());
        $search = $repository->search($data);

        if (empty($search)) {
            ResponseTrait::Success('SM0003', []);
        }

        ResponseTrait::Success('SM0004', $search);
    }

    public function create()
    {
        $rules = (new CustomersValidation())->getRequestRules('CREATE');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }
        $data = $this->request->getJSON(true);
        $generalService = new GeneralService();

        if (!$generalService->isValidBirthDate($data['birth_date'])) {
            ResponseTrait::Error('EM0010');
        }
        $repository = new CustomersRepository(new CustomersModel());
        $entity = new CustomersEntity($data);

        $documentID = $repository->searchDocumentID($data['document_id']);

        if (!empty($documentID)) {
            ResponseTrait::Error('EM0005');
        }

        $repository->create($entity, 'EM0004');

        ResponseTrait::Success('SM0005');
    }

    public function update()
    {
        $rules = (new CustomersValidation())->getRequestRules('UPDATE');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $generalService = new GeneralService();

        if (!$generalService->isValidBirthDate($data['birth_date'])) {
            ResponseTrait::Error('EM0010');
        }
        $repository = new CustomersRepository(new CustomersModel());
        $entity = new CustomersEntity($data);
        unset($entity->document_id, $entity->balance);

        $repository->update($entity, 'SM0003', 'EM0006');

        ResponseTrait::Success('SM0006');
    }

    public function delete()
    {
        $rules = (new CustomersValidation())->getRequestRules('DELETE');

        if (!$this->validate($rules)) {
            ResponseTrait::Error('EM0001', $this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $repository = new CustomersRepository(new CustomersModel());
        $entity = new CustomersEntity($data);

        $repository->delete($entity, 'SM0003', 'EM0007');

        ResponseTrait::Success('SM0007');
    }
}
