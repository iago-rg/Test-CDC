<?php

namespace App\Repositories;

use CodeIgniter\Model;
use Config\ErrorTrait;
use CodeIgniter\Entity\Entity;
use App\Interfaces\RepositoryInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

class BaseRepository implements RepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }


    public function create(Entity $data, string $errorCode): int
    {
        if (!$this->model->insert($data, false)) {
            throw new ErrorTrait($errorCode);
        }

        return $this->model->getInsertID();
    }

    public function createBath(array $data, string $errorCode): int
    {
        if (!$this->model->insertBatch($data)) {
            throw new ErrorTrait($errorCode);
        }

        return $this->model->getInsertID();
    }

    public function update(Entity $data, string $errorCodeSearch, string $errorCodeInsert): bool
    {
        if (!$this->model->find($data->id)) {
            throw new ErrorTrait($errorCodeSearch);
        }

        $allowedFields = $this->model->allowedFields;
        $dataEntity = $data->toRawArray();
        $countDataUpdate = 0;
        foreach ($dataEntity as $key => $value) {
            if (in_array($key, $allowedFields) && $value !== null) {
                $countDataUpdate++;
            }
            if ($countDataUpdate > 0) {
                break;
            }
        }

        if ($countDataUpdate === 0) {
            return true;
        }

        if (!$this->model->update($data->id, $data)) {
            throw new ErrorTrait($errorCodeInsert);
        }

        return true;
    }

    public function updateBatchRepo(array $data, string $errorUpdate, ?string $key = 'id')
    {
        try {
            if (!$this->model->updateBatch($data, $key)) {
                throw new ErrorTrait($errorUpdate);
            }

            return true;
        } catch (DatabaseException $e) {
            throw new ErrorTrait($errorUpdate);
        }
    }

    public function find(string|array $ids = null): array
    {
        $search = $this->model->find($ids);

        if (empty($search)) {
            return [];
        }

        return $search;
    }

    public function delete(Entity $data, string $errorCodeSearch, string $errorCodeDelete): void
    {
        if (!$this->model->find($data->id)) {
            throw new ErrorTrait($errorCodeSearch);
        }

        if (!$this->model->delete($data->id)) {
            throw new ErrorTrait($errorCodeDelete, $this->model->errors());
        }

        $this->model->set('id_user_del', $data->id_user_del ?? null);
        $ids = is_array($data->id) ? $data->id : [$data->id];
        $this->model->withDeleted(true)
            ->update($ids);

        return;
    }

    public function getUuidFromId(int|string $id, string $errorCode): string
    {
        $search = $this->model->select('uuid')->find($id);

        if (empty($search)) {
            throw new ErrorTrait($errorCode);
        }

        return $search['uuid'];
    }

    public function getIdFromUuid(string $uuid, string $errorCode): int
    {
        $search = $this->model->select('id')->where('uuid', $uuid)->first();

        if (empty($search)) {
            throw new ErrorTrait($errorCode);
        }

        return $search['id'];
    }
}
