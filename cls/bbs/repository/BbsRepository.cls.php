<?php
/**
 * bbs
 */
namespace cls\bbs\repository;

interface BbsRepository {
	public function findAllPosts($arr=[]);
	public function findPostById($int=0);
	public function savePost();
	public function destroyPostById($int=0);
}
