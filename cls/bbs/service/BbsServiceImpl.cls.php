<?php

namespace cls\bbs\Service;

use cls\bbs\repository\BbsRepositoryImpl;
use cls\configuration\Database;

class BbsServiceImpl implements BbsService 
{
  private $repository;

  public function __construct($bool = false)
  {
    $db = Database::getInstance($bool)->getDb();
    $this->repository = new BbsRepositoryImpl($db);
  }

  /**
   * 게시글 목록 조회
   */
  public function readAllPosts(array $params = []): array
  {
    return $this->repository->findAllPosts($params);
  }

  /**
   * 게시글 상세 조회
   */
  public function readPostById(int $postId): ?\cls\bbs\domain\Bbs
  {
    return $this->repository->findPostById($postId);
  }

  /**
   * 게시글 등록
   */
  public function createPost(\cls\bbs\domain\Bbs $bbs): bool
  {
    return $this->repository->savePost($data);
  }

  /**
   * 게시글 수정
   */
  public function updatePostById(int $postId, \cls\bbs\domain\Bbs $bbs): bool
  {
    return $this->repository->updatePostById($postId, $data);
  }

  /**
   * 게시글 삭제
   */
  public function deletePostById(int $postId): bool
  {
    return $this->repository->destroyPostById($postId);
  }
}