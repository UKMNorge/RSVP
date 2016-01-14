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

		if ($res = $this->responseRepo->findOneBy(array('event' => $event, 'user' => $user->getDeltaId()))) {
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

        $this->em->persist($res);
	    $this->em->flush();
	}

	public function alertUserPromoted( $user, $event ) {
		$data = array();

		$data['id'] = $event->getId();
		$data['name'] = $this->getName( $event );
		$data['response'] = 'no';

		$link = $this->router->generate('ukmrsvp_event_response', 'no');
		
		$message = 'Noen har meldt seg av '. $event->getName() .' og du er nå påmeldt! Hvis du ikke kan komme, klikk på lenken: '. $link;
		
		$UKMSMS = $this->container->get('ukmsms');
		try {
		    $UKMSMS->sendSMS( $user->getPhone(), $message );
		} catch( Exception $e ) {
			mail('support@ukm.no', 'RSVP ERROR', 'Denne SMS ble ikke sendt: '. $message);
		}
	}
}