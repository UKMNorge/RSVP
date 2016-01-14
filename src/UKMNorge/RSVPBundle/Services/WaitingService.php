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
	
	public function getCount( $user, $event ) {
#		$response = $this->waitingRepo->findOneBy( array('user'=>$user->getDeltaId(), 'event'=>$event->getId()) );
		return rand(0,20);
	}
	
	public function getMyNumber( $user, $event ) {
		return 123;
	}
}