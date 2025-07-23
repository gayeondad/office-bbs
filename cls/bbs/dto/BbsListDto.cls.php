<?php
namespace cls\bbs\dto;

class BbsListDto
{
    public $ipost_seq;
    public $iboard_seq;
    public $ctitle;
    public $ccontent;
    public $cwriter_id;
    public $writerName;
    public $dcreate_date;
    public $dmodify_date;

    public function __construct($data)
    {
        $this->ipost_seq    = $data['ipost_seq'] ?? 0;
        $this->iboard_seq   = $data['iboard_seq'] ?? 0;
        $this->ctitle       = $data['ctitle'] ?? '';
        $this->ccontent     = $data['ccontent'] ?? '';
        $this->cwriter_id   = $data['writerId'] ?? '';
        $this->writerName   = $data['writerName'] ?? '';
        $this->dcreate_date = $data['dcreate_date'] ?? date('Y-m-d H:i:s');
        $this->dmodify_date = $data['dmodify_date'] ?? date('Y-m-d H:i:s');
    }
}