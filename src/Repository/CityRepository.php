<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
        $this->connection = $this->getEntitymanager()->getConnection();
    }

    public function getCityIndexNumber(string $cityName): int
    {
        $sql = "
            SELECT `index_number` FROM `city`
            WHERE `name` = :cityName 
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "cityName" => $cityName,
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC)["index_number"] ?? -1;
    }
}
