<?php
    
namespace Pogotc\RedisEntityBundle\Annotations;
                                            

class RedisEntityDateTime extends AbstractRedisEntityAnnotation {
	
	public function prepareInput($input){
		return $input->format("Y-m-d H:i:s");
	}                                     
	
	public function prepareOutput($output){
		return new \DateTime($output);
	}
}

?>