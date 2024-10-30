<?php declare(strict_types=1);

namespace Skyttes\Security\User;

use Nette\Security\SimpleIdentity;

class UserIdentity extends SimpleIdentity
{
    private array $data;

    public function __construct(UserInterface $user)
    {
        $role = $user->getRole();
        parent::__construct($user->getId(), $role ? is_array($role) ? $role : [$role] : [], $user->toArray());
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array|UserInterface $data): void
    {
        $this->data = $data instanceof UserInterface ? $data->toArray() : $data;
        $this->setId($this->data["id"]);
    }
    
}
