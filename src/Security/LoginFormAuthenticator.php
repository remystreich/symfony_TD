<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
use TargetPathTrait;

private RouterInterface $router;

public function __construct(RouterInterface $router)
{
$this->router = $router;
}

protected function getLoginUrl(Request $request): string
{
return $this->router->generate('app_login');
}

public function authenticate(Request $request): Passport
{
$email = $request->request->get('email', '');
$password = $request->request->get('password', '');

return new Passport(
new UserBadge($email),
new PasswordCredentials($password),
[
new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
]
);
}

public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
{
if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
return new RedirectResponse($targetPath);
}

return new RedirectResponse($this->router->generate('book_index'));
}

public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
{
$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

return new RedirectResponse($this->router->generate('app_login'));
}
}
