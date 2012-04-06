<?php

namespace Pogo\RedisEntityBundle\Service; 
                                  
class RedisEntityManager {
	
	private $redisService;
	
	public function __construct($redisService){
		$this->redisService = $redisService;
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
			$this->redisService->set($redisKey, $val);
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
				$object->$fieldName = $result[$resultIdx];
			}
		}
		return $object;
	}
}

?>