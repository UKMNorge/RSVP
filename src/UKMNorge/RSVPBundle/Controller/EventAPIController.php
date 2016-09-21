<?php

namespace UKMNorge\RSVPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use UKMNorge\RSVPBundle\Entity\Response;
use UKMNorge\RSVPBundle\Entity\Waiting;
use Exception;
use stdClass;

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