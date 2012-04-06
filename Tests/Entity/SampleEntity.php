<?php

namespace Pogotc\RedisEntityBundle\Tests\Entity; 

use Pogotc\RedisEntityBundle\Entity\AbstractRedisEntity;

class SampleEntity extends AbstractRedisEntity {
	
	public $title;
	
	public $description;
	
	
	public function getTableKeyName(){
		return "sampleentity";
	}
}

?>
