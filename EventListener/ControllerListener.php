<?php

namespace Pogotc\RedisEntityBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * The ControllerListener class parses annotation blocks located in
 * controller classes.
 *
 * @author Meh
 */
class ControllerListener
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $reader;

    /**
     * Constructor.
     *
     * @param Reader $reader An Reader instance
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Modifies the Request object to apply configuration information found in
     * controllers annotations like the template to render or HTTP caching
     * configuration.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        $request = $event->getRequest();
        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
			var_dump($configuration);
            if ($configuration instanceof ConfigurationInterface) {
                $request->attributes->set('_'.$configuration->getAliasName(), $configuration);
            }
        }   
		// var_dump($object);
		// die("died in ".__file__." @ ".__line__);
    }
}
