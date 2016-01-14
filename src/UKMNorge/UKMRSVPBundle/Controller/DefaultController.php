<?php

namespace UKMNorge\UKMRSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UKMRSVPBundle:Default:index.html.twig');
    }
}
