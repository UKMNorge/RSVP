<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function listAction()
    {
		$view_data = array();
    	$eventServ = $this->get('ukmrsvp.event');
    	$view_data['eventServ'] = $eventServ;
    	    	
    	$events = $eventServ->getAll();
    	$view_data['events'] = $events;

        return $this->render('UKMRSVPBundle:Event:list.html.twig', $view_data);
    }
    
    public function viewAction($id, $name) {
		$view_data = array();
    	$eventServ = $this->get('ukmrsvp.event');
    	$view_data['eventServ'] = $eventServ;
	    
	    $event = $eventServ->get( $id );
	    $view_data['event'] = $event;
	    
	    $securityContext = $this->container->get('security.authorization_checker');
		$view_data['logged_in'] = $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
    
	    return $this->render('UKMRSVPBundle:Event:view.html.twig', $view_data);
    }
    
}
