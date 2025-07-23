<?php
// filepath: d:\myrefer\PHP\cls\helper\PagingHelper.cls.php
namespace cls\helper;

class PagingHelper {

    /**
     * 페이지 링크 생성
     *
     * @param int $totalItems 전체 아이템 수
     * @param int $currentPage 현재 페이지 번호
     * @param int $itemsPerPage 페이지당 아이템 수
     * @param int $pagesToShow 표시할 페이지 링크 수
     * @param string $urlPattern 페이지 링크 URL 패턴 (예: ?page={page})
     * @return string HTML 페이지 링크
     */
    public static function getPagingLinks(int $totalItems, int $currentPage, int $itemsPerPage, int $pagesToShow, string $urlPattern): string {
        $totalPages = ceil($totalItems / $itemsPerPage);

        if ($totalPages <= 1) {
            return ''; // 페이지가 1개 이하일 경우 페이지 링크를 표시하지 않음
        }

        $startPage = max(1, $currentPage - floor($pagesToShow / 2));
        $endPage = min($totalPages, $startPage + $pagesToShow - 1);

        // 시작 페이지 번호가 1보다 크면 "처음" 링크 추가
        $html = '';
        if ($startPage > 1) {
            $firstPageUrl = str_replace('{page}', '1', $urlPattern);
            $html .= '<a href="' . htmlspecialchars($firstPageUrl) . '">처음</a> ';
        }

        // 이전 페이지 링크 추가
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $prevPageUrl = str_replace('{page}', (string)$prevPage, $urlPattern);
            $html .= '<a href="' . htmlspecialchars($prevPageUrl) . '">이전</a> ';
        }

        // 페이지 번호 링크 추가
        for ($i = $startPage; $i <= $endPage; $i++) {
            $pageUrl = str_replace('{page}', (string)$i, $urlPattern);
            if ($i == $currentPage) {
                $html .= '<strong>' . $i . '</strong> ';
            } else {
                $html .= '<a href="' . htmlspecialchars($pageUrl) . '">' . $i . '</a> ';
            }
        }

        // 다음 페이지 링크 추가
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $nextPageUrl = str_replace('{page}', (string)$nextPage, $urlPattern);
            $html .= '<a href="' . htmlspecialchars($nextPageUrl) . '">다음</a> ';
        }

        // 마지막 페이지 번호 링크 추가
        if ($endPage < $totalPages) {
            $lastPageUrl = str_replace('{page}', (string)$totalPages, $urlPattern);
            $html .= '<a href="' . htmlspecialchars($lastPageUrl) . '">마지막</a>';
        }

        return $html;
    }

    /**
     * 페이징 처리
     * @param  integer $curPage    현재페이지
     * @param  integer $limit 페이지 당 게시물 수
     * @param  integer $total 전체 게시물 수
     * @return array               페이지 배열
     */
    public static function paging($curPage=1, $limit=10, $total=1, $pagesToShow=10)
    {
        if ($curPage < 1) $curPage = 1; // 현재 페이지가 1보다 작으면 1로 설정
        if ($limit < 1) $limit = 10; // 페이지 당 게시물 수가 1보다 작으면 10으로 설정
        if ($total < 0) $total = 0; // 전체 게시물 수가 음수면 0으로 설정

        $totalPages = ceil($total / $limit); // 전체 페이지 수 계산
        if ($totalPages <= 0) {
            $totalPages = 1; // 최소 1페이지로 설정
        }

        $startPage = ($curPage % $limit == 0) ? $curPage - $limit + 1 : floor($curPage / $limit) * $limit + 1; // 해당 화면의 시작 페이지 번호
        $endPage = min($totalPages, $startPage + $pagesToShow - 1); // 끝 페이지 번호 계산

        // echo 'startPage: ' . $startPage . ', endPage: ' . $endPage . '<br>';

        $page = array();
        $page['first'] = ($curPage > 1) ? 1 : 0;
        $page['prev'] = ($curPage > 1) ? $curPage - 1 : 0;
        $page['next'] = ($curPage < $totalPages) ? $curPage + 1 : 0;
        $page['last'] = ($curPage < $totalPages) ? $totalPages : 0;
        $page['total'] = $total;

        for ($i = $startPage; $i <= $endPage; $i++) {
            $page['visuable'][] = $i;
        }

        return $page;
    }
}