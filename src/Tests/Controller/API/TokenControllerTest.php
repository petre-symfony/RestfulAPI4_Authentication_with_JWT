<?php
namespace App\Tests\Controller\API;

use App\Tests\ApiTestCase;

class TokenControllerTest extends ApiTestCase {
	public function testPostCreateToken(){
		$this->createUser('weaverryan', 'I<3Pizza');

		$response = $this->client->post('/api/tokens', [
			'auth' => ['weaverryan', 'I<3Pizza']
		]);
		$this->assertEquals(200, $response->getStatusCode());
		$this->asserter()->assertResponsePropertyExists($response, 'token');
	}

	public function testPostTokenInvalidCredentials(){
		$this->createUser('weaverryan', 'I<3Pizza');

		$response = $this->client->post('/api/tokens', [
			'auth' => ['weaverryan', 'IH8Pizza']
		]);
		$this->assertEquals(401, $response->getStatusCode());
		$this->asserter()->assertResponsePropertyExists($response, 'token');
	}
}