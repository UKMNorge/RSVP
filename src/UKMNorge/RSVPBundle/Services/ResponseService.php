<?php
namespace UKMNorge\RSVPBundle\Services;
class ResponseService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container	= $container;
		$this->doctrine 	= $this->container->get('doctrine');
		$this->em 			= $this->doctrine->getManager();
		$this->waitServ		= $this->container->get('ukmrsvp.waiting');
		$this->eventServ	= $this->container->get('ukmrsvp.event');
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
		return $this->getCount( $event, 'yes' );;
	}

	public function getCountNo( $event ) {
		return $this->getCount( $event, 'no' );;
	}

	public function getCountMaybe( $event ) {
		return $this->getCount( $event, 'maybe' );;
	}

	public function setResponse($user, $event, $response) {
		if ($res = $this->$resRepo->findOneBy(array('event' => $event, 'user' => $user->getDeltaId()))) {
            $res->setStatus($response);
        }
        else {
            $res = new Response();
	        $res->setEvent($event);
	        $res->setUser($user->getDeltaId());
	        $res->setStatus($response);
	        
        }
	    // Sjekk om personen har stått på venteliste. I så fall, fjern de derfra
        if ($wait = $this->waitRepo->findOneBy(array('event' => $event, 'user' => $user->getDeltaId() ))) {
       	    // Flytt neste person fra venteliste over til attending
            $this->waitServ->moveNextInLine($event, $user);
        }

        $em->persist($res);
	    $em->flush();
	}

}