<?php

declare(strict_types=1);

namespace Skyttes\Doctrine\Positionable;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Skyttes\Core\Application\Kernel;

class Positionable
{
    public static function sort(
        string $entity,
        string $itemId,
        ?string $prevId = null,
        ?string $nextId = null,
        $positionCol = "position"
    ): void {
        $em = Kernel::getContainer()
            ->getByType(EntityManagerInterface::class);

        $repository = $em->getRepository($entity);

        $item = $repository->find($itemId);

        if (!$item) {
            throw new \LogicException("Cannot find the sorted item");
        }

        $prev = $prevId ? $repository->find($prevId) : null;
        $next = $nextId ? $repository->find($nextId) : null;

        $moveUp = $repository->createQueryBuilder('r')
            ->where("r.$positionCol <= :prevPosition")
            ->andWhere("r.$positionCol > :currentPosition")
            ->setParameter('prevPosition', $prev?->getPosition() ?? 0)
            ->setParameter('currentPosition', $item->getPosition())
            ->getQuery()
            ->getResult();

        foreach ($moveUp as $up) {
            $up->setPosition($up->getPosition() - 1);
            $em->persist($up);
        }

        $moveDown = $repository->createQueryBuilder('r')
            ->where("r.$positionCol >= :nextPosition")
            ->andWhere("r.$positionCol < :currentPosition")
            ->setParameter('nextPosition', $next?->getPosition() ?? 0)
            ->setParameter('currentPosition', $item->getPosition())
            ->getQuery()
            ->getResult();

        foreach ($moveDown as $down) {
            $down->setPosition($down->getPosition() + 1);
            $em->persist($down);
        }

        if ($prev) {
            $item->setPosition($prev->getPosition() + 1);
        } else if ($next) {
            $item->setPosition($next->getPosition() - 1);
        } else {
            $item->setPosition(1);
        }

        $em->persist($item);
        $em->flush();
    }

    /**
     * @param class-string<Positioned> $entity
     */
    public static function current(
        string $entity,
        string $positionCol = "position"
    ): int {
        /** @var QueryBuilder $qb */
        $qb = Kernel::getContainer()
            ->getByType(EntityManagerInterface::class)
            ->createQueryBuilder();

        $position = 0;

        try {
            $qb->select("q")
                ->from($entity, 'q')
                ->orderBy("q.$positionCol", 'DESC')
                ->setMaxResults(1);

            $result = $qb->getQuery()->getSingleResult();

            if ($result) {
                assert($result instanceof Positioned);
            }

            $position = $result ? $result->getPosition() : 0;
        } catch (NoResultException) {
            // no-op
        }

        return $position;
    }

    /**
     * @param class-string<Positioned> $entity
     */
    public static function next(
        string $entity,
        string $positionCol = "position"
    ): int {
        return self::current($entity, $positionCol) + 1;
    }

    /**
     * @param class-string<Positioned> $entity
     */
    public static function previous(
        string $entity,
        string $positionCol = "position"
    ): int {
        return self::current($entity, $positionCol) - 1;
    }

}