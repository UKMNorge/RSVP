<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UKMNorge\RSVPBundle\Entity\Response;
use Exception;

class EventController extends Controller
{
    public function listAction()
    {
        // Dette er også entry-point for innlogging fra DIP
        $referer = $this->get('session')->get('referer');
        if ($referer) {
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
	    
	    $event = $eventServ->get( $id );
	    $view_data['event'] = $event;
	    
	    $securityContext = $this->container->get('security.authorization_checker');
	    $logged_in = $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
		$view_data['logged_in'] = $logged_in;
    
		// Hva er brukerens svar?
		if( $logged_in ) {
			$user = $this->get('security.token_storage')->getToken()->getUser();

			$responseServ = $this->get('ukmrsvp.response');
			$response = $responseServ->get( $user, $event );

			if( null !== $response ) {
				$view_data['my_response'] = $response;
			}
		}
    
	    return $this->render('UKMRSVPBundle:Event:view.html.twig', $view_data);
    }

    public function loginAction() {
        // Side som viser en innloggingsknapp
        $view_data = array();
        return $this->render('UKMRSVPBundle:login.html.twig', $view_data);
    }
    
    public function responseAction($id, $name, $response) {
        $response = strtolower($response);
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
        if ($response != 'yes' && $response != 'no' && $response != 'maybe') {
            throw new Exception('Svar må enten være ja, nei eller kanskje, på engelsk.', 20002);
        }

        $resRepo = $this->get('doctrine')->getRepository('UKMRSVPBundle:Response');
        if ($res = $resRepo->findOneBy(array('event' => $event, 'user' => $user->getDeltaId()))) {
            $res->setStatus($response);
        }
        else {
            $res = new Response();
            $res->setEvent($event);
            $res->setUser($user->getDeltaId());
            $res->setStatus($response);
        }

        $em->persist($res);
        $em->flush();

        $route_data['id'] = $id;
        $route_data['name'] = $name;
        return $this->redirectToRoute('ukmrsvp_event', $route_data);
    }
}
