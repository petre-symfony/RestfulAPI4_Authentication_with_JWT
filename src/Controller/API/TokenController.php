<?php
namespace App\Controller\API;

use App\Controller\APIBaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends APIBaseController {
	/**
	 * @Route("/api/tokens", methods="POST")
	 */
	public function newTokenAction(){
		return new Response('Token!');
	}
}