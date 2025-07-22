<?php
namespace cls\bbs\controller;

use cls\bbs\service\BbsService;
use cls\bbs\service\BbsServiceImpl;
use cls\configuration\Validator;

class BbsController
{

  private BbsService $bbsService;

  public function __construct($bool=false)
  {
    $this->bbsService = new BbsServiceImpl($bool);
  }

  public function retrieveAllPosts(): array
  {
    // GET 파라미터 유효성 검사 및 기본값 설정
    $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1, 'max_range' => 100]]) : 10;
    $currentPage = isset($_GET['currentPage']) ? filter_var($_GET['currentPage'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1;
    // $offset = isset($_GET['offset']) ? filter_var($_GET['offset'], FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]) : 0;
    $searchKeyword = isset($_GET['searchKeyword']) ? strip_tags($_GET['searchKeyword']) : '';
    $searchType = isset($_GET['searchType']) ? strip_tags($_GET['searchType']) : '';
    $searchDateType = isset($_GET['searchDateType']) ? strip_tags($_GET['searchDateType']) : 'dtReg'; // 기본값은 'dtReg'
    $startDate = isset($_GET['startDate']) ? strip_tags($_GET['startDate']) : date('Y-m-d', strtotime('-1 month')); // 기본값은 한 달 전
    $endDate = isset($_GET['endDate']) ? strip_tags($_GET['endDate']) : date('Y-m-d'); // 기본값은 오늘
    $sort = isset($_GET['sort']) ? strip_tags($_GET['sort']) : 'dtReg'; // 기본값은 'dtReg'
    $order = isset($_GET['order']) ? strip_tags($_GET['order']) : 'DESC'; // 기본값은 'DESC'
    $boardSeq = isset($_GET['boardSeq']) ? filter_var($_GET['boardSeq'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1; // 기본값은 1

    $startDate = Validator::validateDate($startDate);
    $endDate = Validator::validateDate($endDate);

    // 유효성 검사
    if ($startDate === null || $endDate === null) {
      throw new \InvalidArgumentException("Invalid date format. Expected format is YYYY-MM-DD.");
    }
    // 유효성 검사 실패 시 기본값으로 설정
    if ($limit === false) {
      $limit = 10;
    }
    if ($currentPage === false) {
      $currentPage = 1;
    }
    $offset = ($currentPage - 1) * $limit; // 페이지 번호에 따라 오프셋 계산
    if ($offset < 0) {  // 오프셋이 음수일 경우 0으로 설정
      $offset = 0;
    }

    $params = [
      'limit' => $limit,
      'offset' => $offset,
      'searchKeyword' => $searchKeyword,
      'searchType' => $searchType,
      'searchDateType' => $searchDateType,
      'startDate' => $startDate,
      'endDate' => $endDate,
      'sort' => $sort,
      'order' => $order,
      'boardSeq' => $boardSeq
    ];

    return $this->bbsService->readAllPosts($params);
  }

  public function retrievePostById(int $postId): ?\cls\bbs\domain\Bbs
  {
    // 게시글 ID가 유효한지 검사
    if ($postId <= 0) {
      throw new \InvalidArgumentException("Invalid post ID. It must be a positive integer.");
    }

    // 게시글 조회
    $post = $this->bbsService->readPostById($postId);
    if ($post === null) {
      throw new \Exception("Post not found.");
    }

    return $post;
  }

  public function createPost(array $data): bool
  {
    $bbs = new \cls\bbs\domain\Bbs($data);
    return $this->bbsService->createPost($bbs);
  }
  
  public function updatePostById(int $postId, array $data): bool
  {
    $bbs = new \cls\bbs\domain\Bbs($data);
    return $this->bbsService->updatePostById($bbs);
  }
  
  public function deletePostById(int $postId): bool
  {
    return $this->bbsService->deleteById($postId);
  }

  
}