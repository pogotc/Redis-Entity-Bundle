<?php

namespace Pogo\RedisEntityBundle\Tests\Entity; 

use Pogo\RedisEntityBundle\Entity\AbstractRedisEntity;

class SampleEntity extends AbstractRedisEntity {
	
	public $title;
	
	public $description;
	
	
	public function getTableKeyName(){
		return "sampleentity";
	}
}

?>
