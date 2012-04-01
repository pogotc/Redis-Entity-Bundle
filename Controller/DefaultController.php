<?php

namespace Pogo\RedisEntityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('PogoRedisEntityBundle:Default:index.html.twig', array('name' => $name));
    }
}
