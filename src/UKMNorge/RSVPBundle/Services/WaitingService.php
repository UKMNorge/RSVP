<?php
namespace UKMNorge\RSVPBundle\Services;

use UKMNorge\RSVPBundle\Entity\Waiting;
use Exception;

class WaitingService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container	= $container;
		$this->doctrine 	= $this->container->get('doctrine');
		$this->em 			= $this->doctrine->getManager();
		$this->repo			= $this->doctrine->getRepository('UKMRSVPBundle:Waiting');
		#$this->eventServ	= $this->container->get('ukmrsvp.event');
		$this->responseServ	= $this->container->get('ukmrsvp.response');
#		$this->eventRepo	= $this->doctrine->getRepository('UKMRSVPBundle:Event');
#		$this->responseRepo = $this->doctrine->getRepository('UKMRSVPBundle:Response');
		$this->router 		= $router;
	}

	public function get( $user, $event ) {
		return $this->repo->findOneBy( array('user'=>$user->getDeltaId(), 'event'=>$event->getId()) );
	}

	
	public function isWaiting( $user, $event ) {
		$result = $this->get( $user, $event );
		return null !== $result;
	}

	public function add($user, $event) {
		// Legg til brukeren på venteliste
	    $wait = new Waiting();
	    $wait->setEvent($event);
	    $wait->setUser($user->getDeltaId());
	    $this->em->persist($wait);
    	$this->em->flush();
    }
	
	public function getCount( $user, $event ) {
		return $this->repo->getCount( $event );
	}
	
	public function getMyNumber( $user, $event ) {
		return $this->repo->getCountInFront( $user, $event );
	}

	public function moveNextInLine($event) {
		$next = $this->getNextInLine($event);
		$userProvider = $this->container->get('dipb_user_provider');
		if ($next) {
			$nextUser = $userProvider->loadUserByUsername($next);
			// Neste person deltar
			$this->responseServ->setResponse($nextUser, $event, 'yes');
			$this->alertUserPromoted( $nextUser, $event );
		}
	}

	public function setWaiting($user, $event) {
        if ($wait = $this->repo->findOneBy(array('event' => $event, 'user' => $user->getDeltaId() ))) {
            // Brukeren står på venteliste, fjern h*n
            $em->remove($wait);
            $em->flush();
        }
        else {
            // legg til brukeren i ventelista
            $this->add($user, $event);
        }

	}

	public function getNextInLine( $event ) {
		return $this->repo->getNextInLine( $event );
	}
	
	public function alertUserPromoted( $user, $event ) {
		$this->eventServ = $this->container->get('ukmrsvp.event');

		$data = array();

		$data['id'] = $event->getId();
		$data['name'] = $this->eventServ->getName( $event );
		$data['response'] = 'no';

		$link = $this->router->generate('ukmrsvp_event_response', $data, true);
		
		$message = 'Noen har meldt seg av '. $event->getName() .' og du er nå påmeldt! Hvis du ikke kan komme, klikk på lenken: '. $link;
		
		$UKMSMS = $this->container->get('ukmsms');
		try {
		    $UKMSMS->sendSMS( $user->getPhone(), $message );
		} catch( Exception $e ) {
			mail('support@ukm.no', 'RSVP ERROR', 'Denne SMS ble ikke sendt: '. $message);
		}
	}

}