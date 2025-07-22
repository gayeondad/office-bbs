<?php
namespace cls\bbs\dto;

class BbsListDto
{
    public $seq;
    public $boardSeq;
    public $title;
    public $content;
    public $wrtierId;
    public $writerName;
    public $dtReg;
    public $dtMdf;

    public function __construct($data)
    {
        $this->seq      = $data['seq'] ?? 0;
        $this->boardSeq = $data['boardSeq'] ?? 0; // Ensure boardSeq is set to 0 if not provided
        $this->title    = $data['title'] ?? ''; // Ensure title is set to an empty string if not provided
        $this->content  = $data['content'] ?? ''; // Ensure content is set to an empty string if not provided
        $this->wrtierId  = $data['writerId'] ?? ''; //          // Ensure writeId is set to an empty string if not provided
        $this->writerName  = $data['writerName'] ?? ''; // Ensure writeNm is set to an empty string if not provided
        $this->dtReg    = $data['dtReg'] ?? date('Y-m-d H:i:s'); // Default to current date if not provided
        $this->dtMdf    = $data['dtMdf'] ?? date('Y-m-d H:i:s'); // Default to current date if not provided
    }
}