<?php declare(strict_types=1);

namespace Skyttes\Doctrine;

use Doctrine\ORM\EntityRepository;

/**
 * @template R of EntityRepository
 */
interface EntityServiceInterface {
    /**
     * @return R
     */
    public function getRepository(): EntityRepository;
}
