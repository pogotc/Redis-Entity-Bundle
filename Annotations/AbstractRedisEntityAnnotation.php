<?php

namespace Pogotc\RedisEntityBundle\Annotations; 

abstract class AbstractRedisEntityAnnotation extends \Doctrine\Common\Annotations\Annotation {
	
	abstract public function prepareInput($input);
	abstract public function prepareOutput($output);
}

?>