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

class ParticipantAPIController extends Controller {

	public function attendingAction(Request $request) {
		$response = new stdClass();
		try {
			if($access->got('readParticipants')) {
				$response->success = false;
				$response->errors[] = "Not implemented yet";
				$event = $request->request->get('event_id');
				$event = $this->get('ukmrsvp.event')->get($event_id);
				$participants = $this->get('ukmrsvp.event')->getAttending($event);
				#$response->data = $participants;
			}
			else {
				$response->success = false;
				$response->errors = $access->errors();
				$response->errors[] = "UKMRSVPBundle:EventAPIController: Du har ikke tilgang til å lese events. Krever 'readParticipants'-tilgangen.";
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