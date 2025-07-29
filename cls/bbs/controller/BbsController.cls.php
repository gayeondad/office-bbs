<?php
namespace cls\bbs\controller;

use cls\bbs\service\BbsService;
use cls\bbs\service\BbsServiceImpl;
use cls\helper\Validator;

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
    $_GET['itemsPerPage'] = isset($_GET['itemsPerPage']) ? filter_var($_GET['itemsPerPage'], FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1, 'max_range' => 100]]) : 10;
    $_GET['currentPage'] = isset($_GET['currentPage']) ? filter_var($_GET['currentPage'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1;
    $_GET['searchKeyword'] = isset($_GET['searchKeyword']) ? strip_tags($_GET['searchKeyword']) : '';
    $_GET['searchType'] = isset($_GET['searchType']) ? strip_tags($_GET['searchType']) : '';
    $_GET['searchDateType'] = isset($_GET['searchDateType']) ? strip_tags($_GET['searchDateType']) : 'dtReg'; // 기본값은 'dtReg'
    $_GET['startDate'] = isset($_GET['startDate']) ? strip_tags($_GET['startDate']) : date('Y-m-d', strtotime('-1 month')); // 기본값은 한 달 전
    $_GET['endDate'] = isset($_GET['endDate']) ? strip_tags($_GET['endDate']) : date('Y-m-d'); // 기본값은 오늘
    $_GET['sort'] = isset($_GET['sort']) ? strip_tags($_GET['sort']) : 'dcreate_date'; // 기본값은 'dcreate_date'
    $_GET['order'] = isset($_GET['order']) ? strip_tags($_GET['order']) : 'DESC'; // 기본값은 'DESC'
    $_GET['boardSeq'] = isset($_GET['boardSeq']) ? filter_var($_GET['boardSeq'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1; // 기본값은 1

    $_GET['startDate'] = Validator::validateDate($_GET['startDate']);
    $_GET['endDate'] = Validator::validateDate($_GET['endDate']);

    // 유효성 검사
    if ($_GET['startDate'] === null || $_GET['endDate'] === null) {
      throw new \InvalidArgumentException("Invalid date format. Expected format is YYYY-MM-DD.");
    }
    // 유효성 검사 실패 시 기본값으로 설정
    if ($_GET['itemsPerPage'] === false) {
      $_GET['itemsPerPage'] = 10;
    }
    if ($_GET['currentPage'] === false) {
      $_GET['currentPage'] = 1;
    }
    
    return $this->bbsService->readAllPosts($_GET);
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