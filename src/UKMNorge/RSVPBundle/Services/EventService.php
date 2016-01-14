<?php
namespace UKMNorge\RSVPBundle\Services;
class EventService {
	
	var $container;
	
	public function __construct( $container, $router ) {
		$this->container = $container;
		$this->doctrine = $this->container->get('doctrine');
		$this->em 		= $this->doctrine->getManager();
		$this->repo 	= $this->doctrine->getRepository('UKMRSVPBundle:Event');
		$this->router 	= $router;
	}
	
	public function get( $id ) {
		return $this->repo->findOneById( $id );
	}
	
	public function getAll() {
		return $this->repo->findAll();
	}
	
	public function getName( $event ) {
		return $event->getName();
	}
	
	public function getDate( $event ) {
		return $event->getDate();
	}
	
	public function getPlace( $event ) {
		return $event->getPlace();
	}
	
	public function isOpen( $event ) {
		return 0 < $this->getSpotsAvailable( $event );
	}
	
	public function getSpots( $event ) {
		return $event->getSpots();
	}
	
	public function getSpotsAvailable( $event ) {
		$spots = $this->getSpots( $event );
		$taken = $this->getSpotsTaken( $event );
		return (int) $spots - (int) $taken;
	}
	
	public function getSpotsTaken( $event ) {
		return rand(0,1);
	}

	public function getUrl( $event ) {
		$data = array();
		$data['id'] = $event->getId();
		$data['name'] = $this->getName( $event );
		return $this->router->generate('ukmrsvp_event', $data);
	}
}