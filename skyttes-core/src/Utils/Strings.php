<?php declare(strict_types=1);

namespace Skyttes\Core\Utils;

use Nette\Utils\Random;
use Nette\Utils\Strings as BaseStrings;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Strings extends BaseStrings
{
  public static function ip(string $ip): bool
  {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
  }

  public static function id(string $uuid): string
  {
      return ($arr = explode("-", $uuid))[array_key_last($arr)];
  }

  public static function fillId(string $str, string $uuid, mixed ...$args): string
  {
    return sprintf($str, self::id($uuid), ...$args);
  }

  public static function slug(string $str, bool $randomPrefix = true): string
  {
      $slug = (new AsciiSlugger())->slug($str)->toString();

      if (!$randomPrefix) {
          return $slug;
      }

      return sprintf("%s-%s", Random::generate(6), $slug);
  }
}
