<?php

namespace Pogotc\RedisEntityBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Pogotc\RedisEntityBundle\Service\RedisEntityManager;
use Pogotc\RedisEntityBundle\Tests\Entity\SampleEntity;
use Snc\RedisBundle\Client\Phpredis\Client;


class AbstractRedisEntityTest extends WebTestCase
{
	
	public function testSetFields(){     
		
		// $mockEntityManager = $this->getMock("RedisEntityManager");
		// 
		// $sampleEntity = new SampleEntity($mockEntityManager);
		// $sampleEntity->title = "Sample Entity";
		// $sampleEntity->description = "Test Description";
		// 
		// $fieldMap = $sampleEntity->getFieldMap();
		// $this->assertTrue(is_array($fieldMap));
		// $this->assertEquals($fieldMap['title'], 'Sample Entity');
		// $this->assertEquals($fieldMap['description'], 'Test Description'); 
		
	}
}