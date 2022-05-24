<?php

namespace App\Repository;

use App\Entity\Wallet;
use App\Repository\Interfaces\WalletRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class WalletRepository extends ServiceEntityRepository implements WalletRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    public function add(Wallet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wallet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByIban(string $iban): ?Wallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.iban = :iban')
            ->setParameter('iban', $iban)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
