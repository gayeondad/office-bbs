<?php
/**
 * 
 */
namespace cls\common;

trait util
{
	/**
	 * 에러 발생 시 프린트 메시지 생성
	 * @param  object $e 에러 객체
	 * @return string    에러 내용
	 */
	public function mkErrMsg($e)
	{
		if (empty($e)) return false;
		$msg = $e->getMessage() . "\n >> " . $e->getFile() . ' ( line : ' . $e->getLine() . ")\n";
		if (isset($logger)) {	// logger 사용하는 경우
			$logger->error($msg, ['exception' => $e]);
		}
		return $msg;
	}

	/**
	 * 페이징 처리
	 * @param  integer $curPage    현재페이지
	 * @param  integer $rowPerPage 페이지 당 게시물 수
	 * @param  integer $totalOfRow 전체 게시물 수
	 * @return array               페이지 배열
	 */
	public function paging($curPage=1, $rowPerPage=10, $totalOfRow=1)
	{
		$totalOfPage = ($totalOfRow % $rowPerPage == 0) ? $totalOfRow/$rowPerPage : ceil($totalOfRow/$rowPerPage);						// 전체 페이지(마지막 페이지 번호)
		$startOfPage = ($curPage % $rowPerPage == 0) ? $curPage - $rowPerPage + 1 : floor($curPage/$rowPerPage) * $rowPerPage + 1;		// 해당 화면의 시작 페이지 번호
		$endOfPage = (($startOfPage + $rowPerPage) > $totalOfPage) ? $totalOfPage : ($startOfPage + $rowPerPage - 1);

		// echo 'startOfPage: ' . $startOfPage . ', endOfPage: ' . $endOfPage . '<br>';

		$page = array();
		$page['first'] = ($curPage > $rowPerPage) ? 1 : 0;
		$page['prev'] = ($curPage > $rowPerPage) ? $startOfPage - 1 : 0;
		$page['next'] = (($startOfPage + $rowPerPage) <= $totalOfPage) ? ($startOfPage + $rowPerPage) : 0;
		$page['last'] = (($startOfPage + $rowPerPage) <= $totalOfPage) ? $totalOfPage : 0;
		$page['total'] = $totalOfPage;
		
		for ($i=$startOfPage; $i<=$endOfPage; $i++) {
			$page['visable'][] = $i;
		}

		return $page;
	}

	/**
	 * 상관배열 특정 키(key) 값에 의한 정렬 메소드
	 * @param  array  $arr [description]
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function sortByKey($arr=array(), $key='')
	{
		if (empty($arr) || empty($key)) return false;
		usort($arr, function($a, $b) use ($key) {
			return $b[$key] - $a[$key];
		});
		return $arr;
	}




}
