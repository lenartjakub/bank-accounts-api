<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BankAccount;
use App\Repository\Interfaces\BankAccountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class BankAccountRepository extends ServiceEntityRepository implements BankAccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccount::class);
    }

    public function add(BankAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BankAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByPersonalIdNumber(string $personalId): ?BankAccount
    {
        return $this->createQueryBuilder('ba')
            ->andWhere('ba.personalIdNumber = :personalId')
            ->setParameter('personalId', $personalId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
