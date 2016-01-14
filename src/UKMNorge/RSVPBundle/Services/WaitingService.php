<?php
namespace UKMNorge\RSVPBundle\Services;

use UKMNorge\RSVPBundle\Entity\Waiting;
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
	
	public function isWaiting( $user, $event ) {
		$result = $this->repo->findOneBy( array('user'=>$user->getDeltaId(), 'event'=>$event->getId()) );
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

	public function moveNextInLine($event, $user) {
		$next = $this->getNextInLine($user, $event);
		$userProvider = $this->container->get('dipb_user_provider');
		if ($next) {
			$nextUser = $userProvider->loadUserByUsername($next);
			// Neste person deltar
			$this->responseServ->setResponse($event, $nextUser, 'yes');
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

	public function getNextInLine( $user, $event ) {
		return $this->repo->getNextInLine( $user, $event );
	}
}