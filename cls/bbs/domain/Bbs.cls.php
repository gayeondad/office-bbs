<?php
/**
 * bbs
 */
namespace cls\bbs\domain;

class Bbs
{
	private $ipost_seq = NULL;
	private $iboard_seq = 0;
	private $ctitle = '';
	private $ccontent = '';
	private $cwriter_id = '';
	private $dcreate_date = '0000-00-00 00:00:00';
	private $dmodify_date = '0000-00-00 00:00:00';

	public function __construct($row=array())
	{
		if (!empty($row['ipost_seq'])) $this->ipost_seq = $row['ipost_seq'];
		if (!empty($row['iboard_seq'])) $this->iboard_seq = intval($row['iboard_seq']);
		if (!empty($row['ctitle'])) $this->ctitle = $row['ctitle'];
		if (!empty($row['ccontent'])) $this->ccontent = $row['ccontent'];
		if (!empty($row['cwriter_id'])) $this->cwriter_id = $row['cwriter_id'];
		if (!empty($row['dcreate_date'])) $this->dcreate_date = $row['dcreate_date'];
		if (!empty($row['dmodify_date'])) $this->dmodify_date = $row['dmodify_date'];
	}

	public function getIpost_seq() { return $this->ipost_seq; }
	public function getIboard_seq() { return $this->iboard_seq; }
	public function getCtitle() { return $this->ctitle; }
	public function getCcontent() { return $this->ccontent; }
	public function getCwriter_id() { return $this->cwriter_id; }
	public function getDcreate_date() { return $this->dcreate_date; }
	public function getDmodify_date() { return $this->dmodify_date; }

	public function setIpost_seq($ipost_seq) { $this->ipost_seq = $ipost_seq; }
	public function setIboard_seq($iboard_seq) { $this->iboard_seq = $iboard_seq; }
	public function setCtitle($ctitle) { $this->ctitle = $ctitle; }
	public function setCcontent($ccontent) { $this->ccontent = $ccontent; }
	public function setCwriter_id($cwriter_id) { $this->cwriter_id = $cwriter_id; }
	public function setDcreate_date($dcreate_date) { $this->dcreate_date = $dcreate_date; }
	public function setDmodify_date($dmodify_date) { $this->dmodify_date = $dmodify_date; }

	public function toArray()
	{
		return [
			'ipost_seq' => $this->ipost_seq,
			'iboard_seq' => $this->iboard_seq,
			'ctitle' => $this->ctitle,
			'ccontent' => $this->ccontent,
			'cwriter_id' => $this->cwriter_id,
			'dcreate_date' => $this->dcreate_date,
			'dmodify_date' => $this->dmodify_date,
		];
	}
}