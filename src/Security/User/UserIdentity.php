<?php declare(strict_types=1);

namespace Skyttes\Security\User;

use InvalidArgumentException;
use Nette\Security\SimpleIdentity;
use StringBackedEnum;

class UserIdentity extends SimpleIdentity
{
  private array $data;

  public function __construct(UserInterface $user)
  {
    parent::__construct($user->getId(), $this->getNormalizedUserRoles($user), $user->toArray());
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

  /**
   * @param UserInterface $user
   * @return array
   */
  private function getNormalizedUserRoles(UserInterface $user): array
  {
    $roleOrRoles = $user->getRole();

    if (is_string($roleOrRoles)) {
      return [$roleOrRoles];
    }

    if (is_array($roleOrRoles)) {
      return array_map(function ($role) {
        if ($role instanceof StringBackedEnum) {
          return $role->value;
        }

        if (is_string($role)) {
          return $role;
        }

        throw new InvalidArgumentException("Invalid role value found in roles array for class " . get_class($user));
      }, $roleOrRoles);
    }

    if (is_object($roleOrRoles) && enum_exists(get_class($roleOrRoles))) {
      return [$roleOrRoles->value];
    }

    throw new InvalidArgumentException("Invalid role value found for class " . get_class($user));
  }
    
}
