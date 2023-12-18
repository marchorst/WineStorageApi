<?php
// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        if($_ENV['API_KEY_CHECK'] == 1) {
            return $request->headers->has('X-AUTH-TOKEN');
        } else {
            return true;
        }
    }

    public function authenticate(Request $request): Passport
    {
        if($_ENV['API_KEY_CHECK'] == 1) {        
            $apiToken = $request->headers->get('X-AUTH-TOKEN');
            $apiToken = $_ENV['API_KEY'];
            if (null === $apiToken) {
                // The token header was empty, authentication fails with HTTP Status
                // Code 401 "Unauthorized"
                throw new CustomUserMessageAuthenticationException('No API token provided');
            }

            if ($apiToken !== $_ENV['API_KEY']) {
                // The token header was empty, authentication fails with HTTP Status
                // Code 401 "Unauthorized"
                throw new CustomUserMessageAuthenticationException('API token invalid');
            }
        } else {
            $apiToken = "";
        }
        return new SelfValidatingPassport(new UserBadge($apiToken, fn() => new class implements \Symfony\Component\Security\Core\User\UserInterface {
            public function getRoles(): array { return ['IS_AUTHENTICATED_FULLY'];}
            public function eraseCredentials() {}
            public function getUserIdentifier(): string
            {
                return (string) "AUTH";
            }
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}