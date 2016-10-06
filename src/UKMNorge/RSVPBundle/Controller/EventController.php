<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UKMNorge\RSVPBundle\Entity\Response;
use UKMNorge\RSVPBundle\Entity\Waiting;
use Exception;

class EventController extends Controller
{
    public function listAction()
    {
        // Dette er også entry-point for innlogging fra DIP
        $referer = $this->get('session')->get('referer');
        if ($referer) {
            $this->get('session')->remove('referer');
            return $this->redirect($referer);
        }

        


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
    	$view_data['waitingServ'] = $this->get('ukmrsvp.waiting');
	    
	    $event = $eventServ->get( $id );
	    $view_data['event'] = $event;
	    
	    $securityContext = $this->container->get('security.authorization_checker');
	    $logged_in = $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
		$view_data['logged_in'] = $logged_in;
    
		// Hva er brukerens svar?
		if( $logged_in ) {
			$user = $this->get('security.token_storage')->getToken()->getUser();
			$view_data['my_user'] = $user;
			
			$responseServ = $this->get('ukmrsvp.response');
			$response = $responseServ->get( $user, $event );
			if( null !== $response ) {
				$view_data['my_response'] = $response->getStatus();
			}
			
			$waitingServ = $this->get('ukmrsvp.waiting');
			$waiting = $waitingServ->isWaiting( $user, $event );
			if( null !== $waiting ) {
				$view_data['waiting'] = $waiting;
			}
		}
		
		//$view_data['waitingServ']->getNextInLine( $user, $event );
	    return $this->render('UKMRSVPBundle:Event:view.html.twig', $view_data);
    }

    public function loginAction() {
        // Side som viser en innloggingsknapp
        $view_data = array();
        return $this->render('UKMRSVPBundle:login.html.twig', $view_data);
    }
    
    public function responseAction($id, $name, $response) {
        $response = strtolower($response);
        $responseServ = $this->get('ukmrsvp.response');
        // Trenger ikke tenke på sikring, siden brannmur og token_storage fikser det for oss.
        $eventServ = $this->get('ukmrsvp.event');
        $em = $this->get('doctrine')->getManager();

        $event = $eventServ->get($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Sjekk at ingenting er null
        if(!$event || !$user || !$response) {
            var_dump($event);
            var_dump($user);
            var_dump($response);
            throw new Exception('Noe mangler...', 20001);
        }
        if ($response == 'yes' || $response == 'no' || $response == 'maybe') {
            $responseServ->setResponse($user, $event, $response);
        }
        else if ($response == 'wait' || $response == 'donotwait') {
            $waitServ = $this->container->get('ukmrsvp.waiting');
            $waitServ->setWaiting($user, $event);
        }
        else {
            throw new Exception('Svar må enten være ja, nei, kanskje, vent eller ikke vent på engelsk.', 20002);
        }
    
        $route_data['id'] = $id;
        $route_data['name'] = $name;
        return $this->redirectToRoute('ukmrsvp_event', $route_data);
    }

    public function ventelisteAction($id, $name) {
        $eventServ = $this->get('ukmrsvp.event');
        $em = $this->get('doctrine')->getManager();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $event = $eventServ->get($id);
        
    }

    public function testAction() {
        $eventServ = $this->get('ukmrsvp.event');
        $attending = $eventServ->getAttending(1);

        var_dump($attending);

        throw new Exception('teeeeest');
    }
}
