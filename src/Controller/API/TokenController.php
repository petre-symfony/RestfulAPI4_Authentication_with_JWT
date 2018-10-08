<?php
namespace App\Controller\API;

use App\Controller\APIBaseController;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends APIBaseController {
	/**
	 * @Route("/api/tokens", methods="POST")
	 */
	public function newTokenAction(
		Request $request,
		UserRepository $userRepository,
		UserPasswordEncoderInterface $userPasswordEncoder,
		JWTEncoderInterface $lexikEncoder
	){
		$user = $userRepository->findOneBy(['username' => $request->getUser()]);
		if(!$user){
			$this->createNotFoundException('No user');
		}

		$isValid = $userPasswordEncoder
					->isPasswordValid($user, $request->getPassword());

		if(!$isValid){
			throw new BadCredentialsException();
		}

		$token = $lexikEncoder
			->encode(['username' => $user->getUsername()]);

		return new JsonResponse([
			'token' => $token
		]);
	}
}
