<?php

namespace App\Repository;

use App\Entity\Song;
use App\Utils\AdminHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Song::class);
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param Song $object
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
        } elseif (!empty($object->getTitle())) {
            $slug   = AdminHelper::slugify($object->getTitle());
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
                ->setParameter('slug', $slug)
                ->andWhere('s.singer_id = :singer_id')
                ->setParameter('singer_id', $object->getSinger()->getId());

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


    /**
     * @return Song[] Returns an array of Song objects
     */


    /*
    public function findOneBySomeField($value): ?Song
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
