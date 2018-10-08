<?php
namespace App\API;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends HttpException {
	private $apiProblem;

	public function __construct(ApiProblem $apiProblem, string $message = null, \Exception $previous = null, array $headers = array(), ?int $code = 0){
		$this->apiProblem = $apiProblem;
		$statusCode = $apiProblem->getStatusCode();

		parent::__construct($statusCode, $message, $previous, $headers, $code);
	}


	public function getApiProblem(){
		return $this->apiProblem;
	}
}