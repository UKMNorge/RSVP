<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use UKMNorge\RSVPBundle\Entity\Response;
use UKMNorge\RSVPBundle\Entity\Waiting;
use Exception;

class JSONController extends Controller
{
	public function listAction()
	{
		$eventServ = $this->get('ukmrsvp.event');
		
		$eventData = [];
		foreach( $eventServ->getAll() as $event ) {

			
			$eventData[] = $event->expose();
		}

		return new JsonResponse( $eventData );
	}
	
	public function placeAction( $pl_id )
	{
		$eventServ = $this->get('ukmrsvp.event');
		
		$eventData = [];
		foreach( $eventServ->getAll() as $event ) {
			if( $event->getOwner() == $pl_id ) {
				$eventData[] = $event->expose();
			}
		}

		return new JsonResponse( $eventData );
	}
}