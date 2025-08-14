<?php
/**
 * admin
 */
namespace cls\adm\domain;

class Adm
{
	private $iadmin_seq = NULL;
	private $cid = '';
	private $cpassword = '';
	private $cname = '';
	private $ctel = '';
  private $cfax = '';
  private $cphone = '';
  private $cpost = '';
  private $caddr = '';
  private $caddr_detail = '';
  private $idepart_seq = 0;
  private $cposition = 0;
  private $irole_seq = 0;
  private $buseable = 0;
	private $dcreate_date = '0000-00-00 00:00:00';
	private $dmodify_date = '0000-00-00 00:00:00';

	public function __construct($row=[])
	{
		if (!empty($row['iadmin_seq'])) $this->iadmin_seq = $row['iadmin_seq'];
		if (!empty($row['cid'])) $this->cid = $row['cid'];
		if (!empty($row['cpassword'])) $this->cpassword = $row['cpassword'];
		if (!empty($row['cname'])) $this->cname = $row['cname'];
		if (!empty($row['ctel'])) $this->ctel = $row['ctel'];
    if (!empty($row['cfax'])) $this->cfax = $row['cfax'];
    if (!empty($row['cphone'])) $this->cphone = $row['cphone'];
    if (!empty($row['cpost'])) $this->cpost = $row['cpost'];
    if (!empty($row['caddr'])) $this->caddr = $row['caddr'];
    if (!empty($row['caddr_detail'])) $this->caddr_detail = $row['caddr_detail'];
    if (!empty($row['idepart_seq'])) $this->idepart_seq = intval($row['idepart_seq']);
    if (!empty($row['cposition'])) $this->cposition = intval($row['cposition']);
    if (!empty($row['irole_seq'])) $this->irole_seq = intval($row['irole_seq']);
    if (!empty($row['buseable'])) $this->buseable = intval($row['buseable']);
		if (!empty($row['dcreate_date'])) $this->dcreate_date = $row['dcreate_date'];
		if (!empty($row['dmodify_date'])) $this->dmodify_date = $row['dmodify_date'];
	}

	public function getIadmin_seq() { return $this->iadmin_seq; }
	public function getCid() { return $this->cid; }
	public function getCpassword() { return $this->cpassword; }
	public function getCname() { return $this->cname; }
	public function getCtel() { return $this->ctel; }
	public function getCfax() { return $this->cfax; }
	public function getCphone() { return $this->cphone; }
	public function getCpost() { return $this->cpost; }
	public function getCaddr() { return $this->caddr; }
	public function getCaddr_detail() { return $this->caddr_detail; }
	public function getIdepart_seq() { return $this->idepart_seq; }
  public function getCposition() { return $this->cposition; }
  public function getIrole_seq() { return $this->irole_seq; }
  public function getBuseable() { return $this->buseable; }
	public function getDcreate_date() { return $this->dcreate_date; }
	public function getDmodify_date() { return $this->dmodify_date; }

	public function setIadmin_seq($iadmin_seq) { $this->iadmin_seq = $iadmin_seq; }
	public function setCid($cid) { $this->cid = $cid; }
	public function setCpassword($cpassword) { $this->cpassword = $cpassword; }
	public function setCname($cname) { $this->cname = $cname; }
	public function setCtel($ctel) { $this->ctel = $ctel; }
	public function setCfax($cfax) { $this->cfax = $cfax; }
	public function setCphone($cphone) { $this->cphone = $cphone; }
	public function setCpost($cpost) { $this->cpost = $cpost; }
	public function setCaddr($caddr) { $this->caddr = $caddr; }
	public function setCaddr_detail($caddr_detail) { $this->caddr_detail = $caddr_detail; }
	public function setIdepart_seq($idepart_seq) { $this->idepart_seq = $idepart_seq; }
  public function setCposition($cposition) { $this->cposition = $cposition; }
  public function setIrole_seq($irole_seq) { $this->irole_seq = $irole_seq; }
  public function setBuseable($buseable) { $this->buseable = $buseable; }
	public function setDcreate_date($dcreate_date) { $this->dcreate_date = $dcreate_date; }
	public function setDmodify_date($dmodify_date) { $this->dmodify_date = $dmodify_date; }

	public function toArray()
	{
		return [
			'iadmin_seq' => $this->iadmin_seq,
			'cid' => $this->cid,
			'cpassword' => $this->cpassword,
			'cname' => $this->cname,
			'ctel' => $this->ctel,
			'cfax' => $this->cfax,
			'cphone' => $this->cphone,
			'cpost' => $this->cpost,
			'caddr' => $this->caddr,
			'caddr_detail' => $this->caddr_detail,
			'idepart_seq' => $this->idepart_seq,
      'cposition' => $this->cposition,
      'irole_seq' => $this->irole_seq,
      'buseable' => $this->buseable,
			'dcreate_date' => $this->dcreate_date,
			'dmodify_date' => $this->dmodify_date,
		];
	}
}