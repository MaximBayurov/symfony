<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Serializer\SerializerInterface;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    private ApiTokenRepository $apiTokenRepository;
    private LoggerInterface $apiLogger;
    private RequestStack $requestStack;
    private SerializerInterface $serializer;
    
    public function __construct(
        ApiTokenRepository $apiTokenRepository,
        LoggerInterface $apiLogger,
        RequestStack $requestStack,
        SerializerInterface $serializer
    )
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->apiLogger = $apiLogger;
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
    }
    
    public function supports(Request $request)
    {
        return (
            $request->headers->has('Authorization')
            && 0 === mb_strpos($request->headers->get('Authorization'), 'Bearer ')
        );
    }

    public function getCredentials(Request $request)
    {
        return mb_substr($request->headers->get('Authorization'),  7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials
        ]);
        
        if (empty($token)) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid token'
            );
        }
        
        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException(
                'Token expired'
            );
        }
        
        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $request = $this->requestStack->getCurrentRequest();
        
        $this->apiLogger->info("Информация об API авторизации.", [
            'user' => $this->serializer->normalize($user,null, ['groups' => 'log']),
            'token' => $credentials,
            'route' => $request->get('_route'),
            'url' => $request->getRequestUri(),
        ]);
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // continue
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new \Exception('Never called!');
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
