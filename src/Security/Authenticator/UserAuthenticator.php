<?php declare(strict_types=1);

namespace Skyttes\Security\Authenticator;

use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Skyttes\Security\Authenticator\Exception\CannotFindUserException;
use Skyttes\Security\User\UserIdentity;
use Skyttes\Security\User\UserServiceInterface;

readonly class UserAuthenticator implements Authenticator, IdentityHandler
{
    public function __construct(
        private UserServiceInterface $userService,
        private Passwords $passwords,
    ) {
    }

    public function authenticate(string $user, string $password): IIdentity
    {
        $user = $this->userService->findByIdentifier($user);

        if (!$user || !$this->passwords->verify($password, $user->getPasswordHash())) {
            $ex = new CannotFindUserException();
            $ex->identifier = $user;

            throw $ex;
        }

        return new UserIdentity($user);
    }

    public function sleepIdentity(IIdentity $identity): IIdentity
	{
		return $identity;
	}

    public function wakeupIdentity(IIdentity $identity): ?IIdentity
    {
        $user = $this->userService->findByIdentifier($identity->getId());
        return $user ? new UserIdentity($user) : null;
    }
    
}