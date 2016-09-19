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
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Ikke implementert enda. Ta kontakt med support.';
			return new JsonResponse($response);
		}
		catch (Exception $e) {
			$response->success = false;
			$response->errors[] = 'UKMRSVPBundle:EventAPIController: Det oppsto en ukjent feil. Ta kontakt med support.';
			return new JsonResponse($response);
		}
		
	}

	private function getAccessFromRequest($request) {
		try {
			$this->access = new Access($request->query->get('API_KEY'), $this->getParameter('ukmapi.api_key'), $this->getParameter('ukmapi.api_secret'));
			if($request->getMethod() == 'GET') {
				$this->access->validate($request->query->all());
			}
			if(!$this->access->got('readEvents')) {
				#throw new JsonException();
				return false;
			}
		}
		catch(Exception $e) {
			throw new Exception('Die');
		}
	}
}