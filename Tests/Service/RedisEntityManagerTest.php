<?php

namespace Pogotc\RedisEntityBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Pogotc\RedisEntityBundle\Service\RedisEntityManager;
use Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity;
use Snc\RedisBundle\Client\Phpredis\Client;


class RedisEntityManagerTest extends WebTestCase
{                           
	
    public function testCreate(){
		$mockRedisService = $this->getMock("Client", array("set", "incr", "multi", "exec"));
		
		$mockRedisService->expects($this->any())
						->method("set")     
						->with($this->logicalOr(
				                 $this->equalTo('sampleentity:1:id'),
				                 $this->equalTo('sampleentity:1:title'),
				                 $this->equalTo('sampleentity:1:description')
				             ),
							 $this->logicalOr(
				                 $this->equalTo('1'),    
				                 $this->equalTo('New Entity'),
				                 $this->equalTo('New description')
				             )
						 );
		$mockRedisService->expects($this->any())
						->method("incr")
						->with("global:nextsampleentityid")
						->will($this->returnValue(1));
						
		$redisEntityManager = new RedisEntityManager($mockRedisService);
		$sampleEntity = $redisEntityManager->create("Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity");
		
		$sampleEntity->title = "New Entity";
		$sampleEntity->description = "New description";
		$sampleEntity->save();
		$this->assertEquals(1, $sampleEntity->id);
	}
    

	public function testUpdate(){                     
		$mockRedisService = $this->getMock("Client", array("set", "multi", "exec"));

		$mockRedisService->expects($this->any())
						->method("set")     
						->with($this->logicalOr(
				                 $this->equalTo('sampleentity:1:title'),
				                 $this->equalTo('sampleentity:1:description')
				             ),
							 $this->logicalOr(
				                 $this->equalTo('Sample Entity'),
				                 $this->equalTo('Test Description')
				             )
						 );
		
		$redisEntityManager = new RedisEntityManager($mockRedisService);
		$sampleEntity = $redisEntityManager->create("Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity");
		$this->assertEquals(get_class($sampleEntity), "Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity");
		
		$sampleEntity->id = 1;
		$sampleEntity->title = "Sample Entity";
		$sampleEntity->description = "Test Description";
		$sampleEntity->save();
    } 

	public function testCreateInvalidClass(){
		try{
			$mockRedisService = $this->getMock("Client", array("set", "multi", "exec"));      
			$redisEntityManager = new RedisEntityManager($mockRedisService);
			$sampleEntity = $redisEntityManager->create("Fake\Entity");                   
			$this->fail();
		}catch(\Exception $e){
			$this->assertEquals($e->getMessage(), "Class Fake\Entity could not be found");
		}
	}

	public function testLoad(){                                            
		$mockRedisService = $this->getMock("Client", array("get", "multi", "exec"));      
		$redisEntityManager = new RedisEntityManager($mockRedisService);
		$map = array(
			array('sampleentity:1:title', 'Sample Entity'),
			array('sampleentity:1:description', 'Test description'),
		);
		$returnArray = array(
			0 => 'Sample Entity',
			1 => 'Test description',
			2 => '1'
		);
		
		$mockRedisService->expects($this->any())
							->method("get")
							->will($this->returnSelf());
		
		$mockRedisService->expects($this->any())
							->method("multi")
							->will($this->returnSelf());
		
		$mockRedisService->expects($this->any())
							->method("exec")
							->will($this->returnValue($returnArray));
							
							
		$sampleEntity = $redisEntityManager->loadById("Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity", 1);
		$this->assertEquals(get_class($sampleEntity), "Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity");
		$this->assertEquals("Sample Entity", $sampleEntity->title);
		$this->assertEquals("Test description", $sampleEntity->description);
		
	}

}
