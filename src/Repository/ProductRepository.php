<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllProducts()
    {
        return $this->createQueryBuilder('p') // SELECT * FROM product p
            ->addSelect('c', 't')
            ->leftJoin('p.category', 'c') // LEFT JOIN category c ON c.id = p.category_id
            ->leftJoin('p.tags', 't') // LEFT JOIN ... LEFT JOIN ...
            ->getQuery() // query('...')
            ->getResult(); // fetchAll()
    }

    public function findAllGreatherThanPrice($price)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.price > :price') // WHERE price > 100
            ->setParameter('price', $price * 100)
            ->orderBy('p.price', 'ASC')
            ->setMaxResults(4) // LIMIT 4
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findAllFromCategory($name)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
