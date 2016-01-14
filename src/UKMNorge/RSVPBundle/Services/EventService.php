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
	
	public function getDateStart( $event ) {
		return $event->getDateStart();
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
	
	public function getStatusCountYes( $event ) {
		return rand(0,10);		
	}
	
	public function getStatusCountNo( $event ) {
		return rand(0,10);
	}
	
	public function getStatusCountMaybe( $event ) {
		return rand(0,10);
	}

	public function getUrl( $event ) {
		$data = array();
		$data['id'] = $event->getId();
		$data['name'] = $this->getName( $event );
		return $this->router->generate('ukmrsvp_event', $data);
	}
	
	public function getDescription( $event ) {
#		return $event->getDescription();
	return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eget erat ac tellus molestie condimentum. Pellentesque in est tortor. Mauris vestibulum purus et libero imperdiet, suscipit facilisis neque hendrerit. Suspendisse a nunc eget mi aliquam pretium eget eget felis. Suspendisse quis lacinia metus. Nam a maximus lacus. Aliquam neque ex, dignissim non risus iaculis, varius vehicula ligula. Cras a urna eget ligula ultricies convallis.';
	}
}