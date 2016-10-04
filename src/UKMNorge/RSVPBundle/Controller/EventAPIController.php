<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use UKMNorge\RSVPBundle\Entity\Response;
use UKMNorge\RSVPBundle\Entity\Waiting;
use Exception;
use stdClass;
use DateTime;

use UKMNorge\APIBundle\Util\Access;

class EventAPIController extends Controller {
	
	public function allAction(Request $request) {
		$response = new stdClass();
		try {
			$access = $this->getAccessFromRequest($request);	
			#$response->success = false;
			#$response->errors[] = 'UKMRSVPBundle:EventAPIController: Ikke implementert enda. Ta kontakt med support.';

			if($access->got('readEvents')) {
				$response->success = true;
				$allEvents = $this->get('ukmrsvp.event')->getAll();
				$events = [];
				foreach($allEvents as $event) {
					// TODO: Move URL into event.
					$event->url = 'http://rsvp.ukm.no/'.$event->getId().'-'.urlencode($event->getName());
					$events[] = $event->expose();
				}
				$response->data = $events;
			}
			else {
				$response->success = false;
				$response->errors = $access->errors();
				$response->errors[] = "UKMRSVPBundle:EventAPIController: Du har ikke tilgang til å lese events. Krever 'readEvents'-tilgangen.";
			}
			return new JsonResponse($response);
		}
		catch (Exception $e) {
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Det oppsto en ukjent feil. Ta kontakt med support. Feilmelding: '.$e->getMessage();
			return new JsonResponse($response);
		}	
	}

	public function newAction(Request $request) {
		$response = new stdClass();
		try {
			$access = $this->getAccessFromRequest($request);

			if($access->got('createEvents')) {
				$name = $request->request->get('name');
				$place = $request->request->get('place');
				$owner = $request->request->get('owner');
				$spots = $request->request->get('spots');
				$image = $request->request->get('image');
				$date_start = $request->request->get('date_start');
				$start = new DateTime($date_start);
				$date_stop = $request->request->get('date_stop');
				$stop = new DateTime($date_stop);
				$description = $request->request->get('description');
				
				$event = $this->get('ukmrsvp.event')->create($name, $place, $owner, $spots, $image, $start, $stop, $description);
				$response->success = true;
			}
			else {
				$response->success = false;
				$response->errors = $access->errors();
				$response->errors[] = "UKMRSVPBundle:EventAPIController: Du har ikke tilgang til å opprette events. Krever 'createEvents'-tilgangen. Kontakt support om du mener dette er feil";
			}
			return new JsonResponse($response);
		}
		catch (Exception $e) {
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Det oppsto en ukjent feil. Ta kontakt med support. Feilmelding: '.$e->getMessage();
			return new JsonResponse($response);
		}
	}

	public function editAction(Request $request) {
		$response = new stdClass();
		try {
			$access = $this->getAccessFromRequest($request);

			if($access->got('createEvents')) {
				$id = $request->request->get('event_id');
				$name = $request->request->get('name');
				$place = $request->request->get('place');
				$owner = $request->request->get('owner');
				$spots = $request->request->get('spots');
				$image = $request->request->get('image');
				$date_start = $request->request->get('date_start');
				$start = new DateTime($date_start);
				$date_stop = $request->request->get('date_stop');
				$stop = new DateTime($date_stop);
				$description = $request->request->get('description');
				
				$event = $this->get('ukmrsvp.event')->edit($id, $name, $place, $owner, $spots, $image, $start, $stop, $description);
				$response->success = true;
			}
			else {
				$response->success = false;
				$response->errors = $access->errors();
				$response->errors[] = "UKMRSVPBundle:EventAPIController: Du har ikke tilgang til å opprette events. Krever 'createEvents'-tilgangen. Kontakt support om du mener dette er feil";
			}
			return new JsonResponse($response);
		}
		catch (Exception $e) {
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Det oppsto en ukjent feil. Ta kontakt med support. Feilmelding: '.$e->getMessage();
			return new JsonResponse($response);
		}
	}

	public function ownerAction(Request $request) {
		$response = new stdClass();
		#$this->get('logger')->info('UKMRSVPBundle: Matched ownerAction: '.var_export($request, true));
		try {
			$access = $this->getAccessFromRequest($request);

			if($access->got('readEvents')) {
				$response->success = true;
				$owner = $request->request->get('owner');
				$events = array();
				$eventList = $this->get('ukmrsvp.event')->getByOwner($owner);
				#$this->get('logger')->info('UKMRSVPBundle: OwnerAction: '.var_export($eventList, true));
				foreach($eventList as $event) {
					// TODO: Move URL into event.
					$event->url = 'http://rsvp.ukm.no/'.$event->getId().'-'.urlencode($event->getName());
					$events[] = $event->expose();
				}
				$response->data = $events;
			}
			else {
				$response->success = false;
				$response->errors = $access->errors();
				$response->errors[] = "UKMRSVPBundle:EventAPIController: Du har ikke tilgang til å lese events. Krever 'readEvents'-tilgangen.";
			}
			return new JsonResponse($response);
		}
		catch(Exception $e) {
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Det oppsto en ukjent feil. Ta kontakt med support. Feilmelding: '.$e->getMessage();
			return new JsonResponse($response);
		}
	}

	private function getAccessFromRequest($request) {
		try {
			if($request->getMethod() == 'GET') {
				$this->access = new Access($request->query->get('API_KEY'), $this->getParameter('ukmapi.api_key'), $this->getParameter('ukmapi.api_secret'));
				$this->access->validate($request->query->all());
			} 
			else {
				$this->access = new Access($request->request->get('API_KEY'), $this->getParameter('ukmapi.api_key'), $this->getParameter('ukmapi.api_secret'));
				$this->access->validate($request->request->all());
			}
			
			return $this->access;
		}
		catch(Exception $e) {
			throw new Exception('Die');
		}
	}


}