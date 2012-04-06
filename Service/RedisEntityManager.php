<?php

namespace Pogotc\RedisEntityBundle\Service; 

use Doctrine\Common\Annotations\Reader;
                                  
class RedisEntityManager {
	
	private $redisService;                                      
	private $annotationReader;
	
	public function __construct($redisService, $annotationReader){
		$this->redisService = $redisService;
		$this->annotationReader = $annotationReader;
	}
	
	
	public function create($class){
		if(class_exists($class)){
			return new $class($this);
		}else{
			throw new \Exception("Class $class could not be found");
		}
	} 
	
	public function save($object){   		
		$id = $object->id;
		if(!$id){
			$id = $this->getNextIdForObject($object);
			$object->id = $id;
		}
		$className = $object->getTableKeyName();
		$this->redisService->multi();
		foreach(get_object_vars($object) as $key=>$val){
			if($key == "id"){
				continue;
			}     
			
			$redisKey = $className.":".$id.":".$key;
			
			$propAnnot = $this->annotationReader
							->getPropertyAnnotations(new \ReflectionProperty(get_class($object), $key));
			if($propAnnot){
				$this->redisService->set($redisKey, $propAnnot[0]->prepareInput($val));
			}
		} 
		$this->redisService->exec();
	}
	
	protected function getNextIdForObject($object){
		$key = "global:next".$object->getTableKeyName()."id";
		return $this->redisService->incr($key);
	}
	
	public function loadById($class, $id){
		$object = $this->create($class);
		$redis = $this->redisService->multi();
		$className = $object->getTableKeyName();  
		$fieldKeyMap = array();
		$fieldIdx = 0;         
		//Get values out of Redis
		foreach(get_object_vars($object) as $key=>$val){
			$redisKey = $className.":".$id.":".$key;
			$redis = $redis->get($redisKey);
			$fieldKeyMap[$key] = $fieldIdx;
			$fieldIdx++;
		} 
		$result = $this->redisService->exec();

		//And then put them back into the object
		if(count($fieldKeyMap)){
			foreach($fieldKeyMap as $fieldName => $resultIdx){
				$propAnnot = $this->annotationReader
								->getPropertyAnnotations(new \ReflectionProperty($class, $fieldName));
				if($propAnnot){
					$object->$fieldName = $propAnnot[0]->prepareOutput($result[$resultIdx]);
				}
				
			}
		}     
		
		return $object;
	}
}

?>