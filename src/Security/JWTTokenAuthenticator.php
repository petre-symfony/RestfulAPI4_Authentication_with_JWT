<?php
namespace App\Security;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTTokenAuthenticator extends AbstractGuardAuthenticator {
	/**
	 * @var JWTEncoderInterface
	 */
	private $JWTEncoder;
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	public function __construct(JWTEncoderInterface $JWTEncoder, UserRepository $userRepository){

		$this->JWTEncoder = $JWTEncoder;
		$this->userRepository = $userRepository;
	}

	public function supports(Request $request){
		$request->headers->has('Authorization');
	}

	public function getCredentials(Request $request){
		$extractor = new AuthorizationHeaderTokenExtractor(
			'Bearer',
			'Authorization'
		);
		$token = $extractor->extract($request);

		if(!$token){
			return;
		}

		return $token;
	}

	public function getUser(
		$credentials,
		UserProviderInterface
		$userProvider
	){
		$data = $this->JWTEncoder->decode($credentials);

		if ($data === false){
			throw new CustomUserMessageAuthenticationException('Invalid tken');
		}

		$username = $data['username'];

		$this->userRepository->findOneBy(['username' => $username]);
	}

	public function checkCredentials($credentials, UserInterface $user){
		return true;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception){
		// TODO: Implement onAuthenticationFailure() method.
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
		// do nothing
	}

	public function supportsRememberMe(){
		return false;
	}

	public function start(Request $request, AuthenticationException $authException = null){
		// TODO: Implement start() method.
	}


}