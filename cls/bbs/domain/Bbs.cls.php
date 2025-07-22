<?php
/**
 * bbs
 */
namespace cls\bbs\domain;

class Bbs
{
	private $seq = NULL;
	private $boardSeq = 0;
	private $title = '';
	private $content = '';
	private $writeId = '';
	private $dtReg = '0000-00-00 00:00:00';
	private $dtMdf = '0000-00-00 00:00:00';

	public function __construct($row=array())
	{
		if (!empty($row['seq'])) $this->seq = $row['seq'];
		if (!empty($row['boardSeq'])) $this->boardSeq = intval($row['boardSeq']);
		if (!empty($row['title'])) $this->title = $row['title'];
		if (!empty($row['content'])) $this->content = $row['content'];
		if (!empty($row['writeId'])) $this->writeId = $row['writeId'];
		if (!empty($row['dtReg'])) $this->dtReg = $row['dtReg'];
		if (!empty($row['dtMdf'])) $this->dtMdf = $row['dtMdf'];
	}

	public function getSeq() { return $this->seq; }
	public function getBoardSeq() { return $this->boardSeq; }
	public function getTitle() { return $this->title; }
	public function getContent() { return $this->content; }
	public function getWriteId() { return $this->writeId; }
	public function getDtReg() { return $this->dtReg; }
	public function getDtMdf() { return $this->dtMdf; }

	public function setSeq($seq) { $this->seq = $seq; }
	public function setBoradSeq($boradSeq) { $this->boradSeq = $boradSeq; }
	public function setTitle($title) { $this->title = $title; }
	public function setContent($content) { $this->content = $content; }
	public function setWriteId($writeId) { $this->writeId = $writeId; }
	public function setDtReg($dtReg) { $this->dtReg = $dtReg; }
	public function setDtMdf($dtMdf) { $this->dtMdf = $dtMdf; }
	public function toArray()
	{
		return [
			'seq' => $this->seq,
			'boardSeq' => $this->boardSeq,
			'title' => $this->title,
			'content' => $this->content,
			'writeId' => $this->writeId,
			'dtReg' => $this->dtReg,
			'dtMdf' => $this->dtMdf,
		];
	}
}