<?php
    
namespace Pogotc\RedisEntityBundle\Annotations;
                                            

class RedisEntityString extends AbstractRedisEntityAnnotation {
	
	public function prepareInput($input){
		return $input;
	}                           
	
	public function prepareOutput($output){
		return $output;
	}
}

?>