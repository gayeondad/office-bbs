<?php
namespace cls\adm\service;

interface AdmService
{
    public function readAllUsers(array $params = []): array;
    public function readUserByCd(int $cd): ?\cls\adm\domain\Adm;
    public function readUserById(string $id): ?\cls\adm\domain\Adm;
    public function createUser(\cls\adm\domain\Adm $adm): bool;
    public function updateUser(\cls\adm\domain\Adm $adm): bool;
    public function deleteUserById(int $id): bool;
}
