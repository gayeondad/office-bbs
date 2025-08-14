<?php
namespace cls\Adm\controller;

use cls\adm\service\AdmService;
use cls\adm\service\AdmServiceImpl;
use cls\helper\Validator;
use cls\adm\domain\Adm;
use cls\common\auth;

class AdmController
{

  private AdmService $AdmService;

  public function __construct($bool=false)
  {
    $this->AdmService = new AdmServiceImpl($bool);
  }

  public function retrieveAllUsers(): array
  {
    // GET 파라미터 유효성 검사 및 기본값 설정
    $_GET['itemsPerPage'] = isset($_GET['itemsPerPage']) ? filter_var($_GET['itemsPerPage'], FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1, 'max_range' => 100]]) : 10;
    $_GET['currentPage'] = isset($_GET['currentPage']) ? filter_var($_GET['currentPage'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1;
    $_GET['searchKeyword'] = isset($_GET['searchKeyword']) ? strip_tags($_GET['searchKeyword']) : '';
    $_GET['searchType'] = isset($_GET['searchType']) ? strip_tags($_GET['searchType']) : '';
    $_GET['searchDateType'] = isset($_GET['searchDateType']) ? strip_tags($_GET['searchDateType']) : 'dtReg'; // 기본값은 'dtReg'
    $_GET['startDate'] = isset($_GET['startDate']) ? strip_tags($_GET['startDate']) : '2025-03-01'; // 기본값은 한 달 전
    $_GET['endDate'] = isset($_GET['endDate']) ? strip_tags($_GET['endDate']) : date('Y-m-d'); // 기본값은 오늘
    $_GET['sort'] = isset($_GET['sort']) ? strip_tags($_GET['sort']) : 'dcreate_date'; // 기본값은 'dcreate_date'
    $_GET['order'] = isset($_GET['order']) ? strip_tags($_GET['order']) : 'DESC'; // 기본값은 'DESC'
    $_GET['idepart_seq'] = isset($_GET['idepart_seq']) ? $_GET['idepart_seq'] : ''; // 기본값은 빈 문자열
    $_GET['irole_seq'] = isset($_GET['irole_seq']) ? filter_var($_GET['irole_seq'], FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]) : 0; // 기본값은 0
    $_GET['cposition'] = isset($_GET['cposition']) ? $_GET['cposition'] : ''; // 기본값은 빈 문자열
    $_GET['buseable'] = isset($_GET['buseable']) ? filter_var($_GET['buseable'], FILTER_VALIDATE_BOOLEAN, ['options' => ['default' => true]]) : true; // 기본값은 true

    // 날짜 형식 검증
    $_GET['startDate'] = Validator::validateDate($_GET['startDate']);
    $_GET['endDate'] = Validator::validateDate($_GET['endDate']);
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
    
    return $this->AdmService->readAllUsers($_GET);
  }

  public function retrieveUserById(string $UserId): ?\cls\adm\domain\Adm
  {
    // 관리자 ID가 유효한지 검사
    if (empty($UserId)) {
      throw new \InvalidArgumentException("Invalid User ID. It must be a string.");
    }

    // 관리자 조회
    $User = $this->AdmService->readUserById($UserId);
    if ($User === null) {
      throw new \Exception("User not found.");
    }

    return $User;
  }

  public function retrieveUserByCd(int $UserCd): ?\cls\adm\domain\Adm
  {
    // 관리자 Code가 유효한지 검사
    if ($UserCd <= 0) {
      throw new \InvalidArgumentException("Invalid User Code. It must be a positive integer.");
    }

    // 관리자 조회
    $User = $this->AdmService->readUserByCd($UserCd);
    if ($User === null) {
      throw new \Exception("User not found.");
    }

    return $User;
  }

  public function createUser(array $data): bool
  {
    // 데이터 유효성 검사
    if (empty($data['cid']) || empty($data['cname']) || empty($data['cpassword'])) {
      throw new \InvalidArgumentException("Required fields are missing: cid, cname, cpassword.");
    }
    // 비밀번호 해싱 처리
    // $data['cpassword'] = auth::generatePwKey($data['cpassword']);
    // 나머지 필드에 대한 기본값 설정
    $data['ctel'] = $data['ctel'] ?? '';
    $data['cfax'] = $data['cfax'] ?? '';
    $data['cphone'] = $data['cphone'] ?? '';
    $data['cpost'] = $data['cpost'] ?? '';
    $data['caddr'] = $data['caddr'] ?? '';
    $data['caddr_detail'] = $data['caddr_detail'] ?? '';
    $data['idepart_seq'] = isset($data['idepart_seq']) ? intval($data['idepart_seq']) : 0;
    $data['cposition'] = isset($data['cposition']) ? intval($data['cposition']) : 0;
    $data['irole_seq'] = isset($data['irole_seq']) ? intval($data['irole_seq']) : 0;
    $data['buseable'] = isset($data['buseable']) ? intval($data['buseable']) : 0;
    // $data['buseable'] = isset($data['buseable']) ? filter_var($data['buseable'], FILTER_VALIDATE_BOOLEAN, ['options' => ['default' => true]]) : true; // 기본값은 true
    $data['dcreate_date'] = $data['dcreate_date'] ?? date('Y-m-d H:i:s'); // 기본값은 현재 시간
    $data['dmodify_date'] = $data['dmodify_date'] ?? date('Y-m-d H:i:s'); // 기본값은 현재 시간
    
    // 관리자 객체 생성 및 저장
    $Adm = new \cls\adm\domain\Adm($data);
    return $this->AdmService->createUser($Adm);
  }
  
  public function updateUser(array $data): bool
  {
    // 데이터 유효성 검사
    if (empty($data['iadmin_seq']) || empty($data['cid']) || empty($data['cname'])) {
      throw new \InvalidArgumentException("Required fields are missing: cid, cname, cpassword.");
    }
    $data['ctel'] = $data['ctel'] ?? '';
    $data['cfax'] = $data['cfax'] ?? '';
    $data['cphone'] = $data['cphone'] ?? '';
    $data['cpost'] = $data['cpost'] ?? '';
    $data['caddr'] = $data['caddr'] ?? '';
    $data['caddr_detail'] = $data['caddr_detail'] ?? '';
    $data['idepart_seq'] = isset($data['idepart_seq']) ? intval($data['idepart_seq']) : 0;
    $data['cposition'] = isset($data['cposition']) ? intval($data['cposition']) : 0;
    $data['irole_seq'] = isset($data['irole_seq']) ? intval($data['irole_seq']) : 0;
    $data['buseable'] = isset($data['buseable']) ? intval($data['buseable']) : 0;
    // $data['buseable'] = isset($data['buseable']) ? filter_var($data['buseable'], FILTER_VALIDATE_BOOLEAN, ['options' => ['default' => true]]) : true; // 기본값은 true
    // $data['dcreate_date'] = $data['dcreate_date'] ?? date('Y-m-d H:i:s'); // 기본값은 현재 시간
    $data['dmodify_date'] = $data['dmodify_date'] ?? date('Y-m-d H:i:s'); // 기본값은 현재 시간
    
    // 관리자 객체 생성 및 저장
    $Adm = new \cls\adm\domain\Adm($data);
    return $this->AdmService->updateUser($Adm);
  }
  
  public function deleteUserById(int $UserId): bool
  {
    return $this->AdmService->deleteById($UserId);
  }

  
}