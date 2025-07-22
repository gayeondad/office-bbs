<?php
namespace cls\bbs\service;

interface BbsService
{
    public function readAllPosts(array $params = []): array;
    public function readPostById(int $id): ?array;
    public function createPost(\cls\bbs\domain\Bbs $bbs): bool;
    public function updatePostById(int $id, \cls\bbs\domain\Bbs $bbs): bool;
    public function deletePostById(int $id): bool;
}
