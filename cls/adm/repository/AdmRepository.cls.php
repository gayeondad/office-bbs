<?php
/**
 * admin
 */
namespace cls\adm\repository;

interface AdmRepository {
	public function findAllUsers($arr=[]);
	public function findUserByCd($str='');
	public function findUserById($int=0);
	public function saveUser(\cls\adm\domain\Adm $adm);
	public function deleteUserById($int=0);
}
