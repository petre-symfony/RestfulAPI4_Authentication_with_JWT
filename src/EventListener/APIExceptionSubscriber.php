<?php
namespace App\EventListener;


use App\API\ApiProblem;
use App\API\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class APIExceptionSubscriber implements EventSubscriberInterface {

	private $debug;

	public function __construct($debug){
		$this->debug = $debug;
	}

	/**
	 * @param GetResponseForExceptionEvent $event
	 */
	public function onKernelException(GetResponseForExceptionEvent $event){
		$request = $event->getRequest();
		if (strpos($request->getPathInfo(), '/api') !== 0){
			return;
		}

		$e = $event->getException();
		$statusCode = $e instanceof  HttpExceptionInterface ? $e->getStatusCode() : 500;

		if($statusCode == 500 && $this->debug){
			return;
		}

		if($e instanceof ApiProblemException) {
			$apiProblem = $e->getApiProblem();
		} else {

			$apiProblem = new ApiProblem($statusCode);

			if($e instanceof HttpExceptionInterface){
				$apiProblem->set('detail', $e->getMessage());
			}
		}

		$data = $apiProblem->toArray();
		if ($data['type'] !== 'about:blank'){
			$data['type'] = 'http://localhost/docs/errors#'.$data['type'];
		}

		$response =  new JsonResponse(
			$data,
			$apiProblem->getStatusCode()
		);

		$response->headers->set('Content-Type', 'application/problem+json');

		$event->setResponse($response);

	}

	public static function getSubscribedEvents(){
		return [
			KernelEvents::EXCEPTION => 'onKernelException'
		];
	}



}