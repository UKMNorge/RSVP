<?php
namespace UKMNorge\RSVPBundle\Services;

use UKMNorge\RSVPBundle\Entity\Response;
class ResponseService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container	= $container;
		$this->doctrine 	= $this->container->get('doctrine');
		$this->em 			= $this->doctrine->getManager();
		#$this->waitServ		= $this->container->get('ukmrsvp.waiting');
		#$this->eventServ	= $this->container->get('ukmrsvp.event');
		$this->waitRepo		= $this->doctrine->getRepository('UKMRSVPBundle:Waiting');
		$this->eventRepo	= $this->doctrine->getRepository('UKMRSVPBundle:Event');
		$this->responseRepo = $this->doctrine->getRepository('UKMRSVPBundle:Response');
		$this->router 		= $router;
	}
	
	public function get( $user, $event ) {
		$response = $this->responseRepo->findOneBy( array('user'=>$user->getDeltaId(), 'event'=>$event->getId()) );
		return $response;
	}
	
	public function getCount( $event, $status='yes' ) {
		return $this->responseRepo->getCount( $event, $status );
	}
	
	public function getCountYes( $event ) {
		return $this->getCount( $event, 'yes' );
	}

	public function getCountNo( $event ) {
		return $this->getCount( $event, 'no' );
	}

	public function getCountMaybe( $event ) {
		return $this->getCount( $event, 'maybe' );
	}

	public function setResponse($user, $event, $response) {
		$this->waitServ	= $this->container->get('ukmrsvp.waiting');
		$this->eventServ= $this->container->get('ukmrsvp.event');
		
		$res = $this->get( $user, $event );
		if ( $res ) {
            $res->setStatus($response);
        }
        else {
            $res = new Response();
	        $res->setEvent($event);
	        $res->setUser($user->getDeltaId());
	        $res->setStatus($response);
	        
        }
        $this->em->persist($res);
	    $this->em->flush();

	    // Sjekk om personen har stått på venteliste. I så fall, fjern de derfra
	    $waiting = $this->waitServ->isWaiting($user, $event);
	    if( $waiting ) {
	    	$waiting_row = $this->waitServ->get( $user, $event );
	        $this->em->remove( $waiting_row );
		    $this->em->flush();
	    }
	    
	    if( $this->eventServ->isOpen( $event ) ) {
	       	// Flytt neste person fra venteliste over til attending
	        $this->waitServ->moveNextInLine($event);
	    }
	}
}