<?php
namespace UKMNorge\RSVPBundle\Services;
class ResponseService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container	= $container;
		$this->doctrine 	= $this->container->get('doctrine');
		$this->em 			= $this->doctrine->getManager();
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

}