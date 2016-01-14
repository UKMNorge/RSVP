<?php
namespace UKMNorge\RSVPBundle\Services;
class WaitingService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container	= $container;
		$this->doctrine 	= $this->container->get('doctrine');
		$this->em 			= $this->doctrine->getManager();
		$this->repo			= $this->doctrine->getRepository('UKMRSVPBundle:Waiting');
#		$this->eventRepo	= $this->doctrine->getRepository('UKMRSVPBundle:Event');
#		$this->responseRepo = $this->doctrine->getRepository('UKMRSVPBundle:Response');
		$this->router 		= $router;
	}
	
	public function isWaiting( $user, $event ) {
		$result = $this->repo->findOneBy( array('user'=>$user->getDeltaId(), 'event'=>$event->getId()) );
		return null !== $result;
	}
	
	public function getCount( $user, $event ) {
		return $this->repo->getCount( $event );
	}
	
	public function getMyNumber( $user, $event ) {
		return $this->repo->getCountInFront( $user, $event );
	}
}