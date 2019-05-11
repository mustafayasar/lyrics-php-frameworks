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
    public function getListWithCache($initial = false, $order = 'name', $limit = 14, $page = 0)
    {
        $singers    = $this->createQueryBuilder('s')->andWhere("s.status = 1");

        if (!empty($initial)) {
            if ($initial == '09') {
                $singers->andWhere("s.slug LIKE '0%' OR s.slug LIKE '1%' OR s.slug LIKE '2%' OR s.slug LIKE '3%' OR s.slug LIKE '4%' OR s.slug LIKE '5%' OR s.slug LIKE '6%' OR s.slug LIKE '7%' OR s.slug LIKE '8%' OR s.slug LIKE '9%'");
            } else {
                $singers->andWhere("s.slug LIKE '$initial%'");
            }
        }

        if ($limit === 'get_count') {
            return $singers->select('COUNT(s.id)')->getQuery()->getSingleScalarResult();
        } elseif ($limit > 0) {
            $page   = $page < 2 ? 1 : $page;
            $offset = ($page - 1) * $limit;

            $singers->setMaxResults($limit)->setFirstResult($offset);
        }

        if ($order == 'hit') {
            $singers->orderBy("s.hit","DESC");
        } elseif ($order == 'name') {
            $singers->orderBy("s.slug","ASC");
        }

        return $singers->getQuery()->getResult();
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
