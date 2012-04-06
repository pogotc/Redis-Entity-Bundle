<?php

namespace Pogo\RedisEntityBundle\Entity; 

abstract class AbstractRedisEntity {
	
	public $id;
	
	private $redisEntityManager;
	
	public function __construct($redisEntityManager){
		$this->redisEntityManager = $redisEntityManager;
	}
	
	public function save(){
		$this->redisEntityManager->save($this);
	}
	
	abstract public function getTableKeyName();
}

?>