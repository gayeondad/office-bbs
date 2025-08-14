<?php

namespace cls\Adm\Service;

use cls\adm\repository\AdmRepositoryImpl;
use cls\configuration\Database;

class AdmServiceImpl implements AdmService 
{
  private $repository;

  public function __construct($bool = false)
  {
    $db = Database::getInstance($bool)->getDb();
    $this->repository = new AdmRepositoryImpl($db);
  }

  /**
   * 게시글 목록 조회
   */
  public function readAllUsers(array $params = []): array
  {
    $rslt = $this->repository->findAllUsers($params);

    // pagination
    if (!empty($rslt['total'])) {
      $rslt['pages'] = \cls\helper\PagingHelper::mkPages(
        $rslt['total'], 
        $params['itemsPerPage'] ?? 10, 
        $params['pagesToShow'] ?? 5, 
        $params['currentPage'] ?? 1
      );
    } else {
      $rslt['pages'] = [];
    }
    return $rslt;
  }

  /**
   * 게시글 상세 조회
   */
  public function readUserByCd(int $UserCd): ?\cls\adm\domain\Adm
  {
    return $this->repository->findUserByCd($UserId);
  }

  public function readUserById(string $UserId): ?\cls\adm\domain\Adm
  {
    return $this->repository->findUserById($UserId);
  }

  /**
   * 게시글 등록
   */
  public function createUser(\cls\adm\domain\Adm $Adm): bool
  {
    $password = $Adm->getCpassword();
    if (!empty($password)) {
      // 비밀번호가 변경된 경우에만 해싱 처리
      $Adm->setCpassword(auth::generatePwKey($password));
    }
    return $this->repository->saveUser($Adm);
  }

  /**
   * 게시글 수정
   */
  public function updateUser(\cls\adm\domain\Adm $Adm): bool
  {
    // 관리자 ID가 유효한지 검사
    $iadmin_seq = $Adm->getIadmin_seq();
    $oldData = $this->repository->findUserById($iadmin_seq);
    if ($iadmin_seq <= 0 || $oldData === null) {
      throw new \InvalidArgumentException("Invalid User ID. It must be a positive integer.");
    }

    $password = $Adm->getCpassword();
    if (!empty($password)) {
      // 비밀번호가 변경된 경우에만 해싱 처리
      $Adm->setCpassword(auth::generatePwKey($password));
    } else {
      // 비밀번호가 변경되지 않은 경우 기존 비밀번호 유지
      $Adm->setCpassword($oldData->getCpassword());
    }
    
    return $this->repository->saveUser($Adm);
  }

  /**
   * 게시글 삭제
   */
  public function deleteUserById(int $UserId): bool
  {
    return $this->repository->destroyUserById($UserId);
  }
}