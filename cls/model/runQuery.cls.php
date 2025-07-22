<?php
/**
 * Interface
 */
namespace cls\model;

interface runQuery {
	public function setRow($arr=array());
	public function getRow($key='');
	public function insQry();
	public function updQry();
	public function delQry();
	public function selQry($arr=array());
	public function chkDbl($arr=array());
}
