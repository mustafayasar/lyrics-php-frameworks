<?php

namespace App\Repository;

use App\Entity\Singer;
use App\Utils\AdminHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Singer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Singer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Singer[]    findAll()
 * @method Singer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingerRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Singer::class);
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param Singer $object
     * @param int $c
     *
     * @return string
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function createSlug($object, $c = 0)
    {
        if (!empty($object->getSlug())) {
            $slug   = AdminHelper::slugify($object->getSlug());
        } elseif (!empty($object->getName())) {
            $slug   = AdminHelper::slugify($object->getName());
        } else {
            return '';
        }

        if ($c > 0) {
            $slug   = $slug.'-'.$c;
        }

        if ($slug != '') {
            $slug_query = $this->createQueryBuilder('s')
                            ->select('count(s.id)')
                            ->andWhere('s.slug = :slug')
                            ->setParameter('slug', $slug);

            if ($object->getId() > 0) {
                $slug_query->andWhere('s.id != :id')
                        ->setParameter('id', $object->getId());
            }

            if ($slug_query->getQuery()->getSingleScalarResult() > 0) {
                return $this->createSlug($object, $c+1);
            }
        }

        return $slug;
    }


    /*
    public function findOneBySomeField($value): ?Singer
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
