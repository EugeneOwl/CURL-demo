<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function isUsernameFree(?string $username): bool
    {
        $sql = "
            SELECT `user`.`id` FROM `user` `user`
            WHERE `user`.`username` = :username;
            ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "username" => $username,
        ]);
        return count($stmt->fetchAll()) === 0;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
