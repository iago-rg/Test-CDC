<?php

namespace App\Interfaces;

use CodeIgniter\Entity\Entity;
use CodeIgniter\Model;

interface RepositoryInterface
{
    public function __construct(Model $model);
    public function create(Entity $data, string $errorCode): int;
    public function update(Entity $data, string $errorCodeSearch, string $errorCodeInsert): bool;
    public function find(string|array $ids): array;
    public function delete(Entity $data, string $errorCodeSearch, string $errorCodeDelete): void;
    public function getUuidFromId(int|string $id, string $errorCode): string;
    public function getIdFromUuid(string $uuid, string $errorCode): int;
}
