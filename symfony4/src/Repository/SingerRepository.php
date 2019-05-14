<?php

namespace App\Repository;

use App\Entity\Singer;
use App\Utils\AdminHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Singer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Singer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Singer[]    findAll()
 * @method Singer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingerRepository extends ServiceEntityRepository
{
    private $cache;

    public function __construct(RegistryInterface $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Singer::class);

        $this->cache = RedisAdapter::createConnection($container->getParameter('redis_dsn'));
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
        $cache_key  = 'singer-list_'.$initial.'_'.$order.'_'.$limit.'_'.$page;

        if ($this->cache->exists($cache_key)) {
            return unserialize($this->cache->get($cache_key));
        }

        $singers    = $this->createQueryBuilder('s')->andWhere("s.status = 1");

        if (!empty($initial)) {
            if ($initial == '09') {
                $singers->andWhere("s.slug LIKE '0%' OR s.slug LIKE '1%' OR s.slug LIKE '2%' OR s.slug LIKE '3%' OR s.slug LIKE '4%' OR s.slug LIKE '5%' OR s.slug LIKE '6%' OR s.slug LIKE '7%' OR s.slug LIKE '8%' OR s.slug LIKE '9%'");
            } else {
                $singers->andWhere("s.slug LIKE '$initial%'");
            }
        }

        if ($limit === 'get_count') {
            $result = $singers->select('COUNT(s.id)')->getQuery()->getSingleScalarResult();

            $this->cache->set($cache_key, serialize($result), Singer::CD_LIST);

            return $result;
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

        $result = $singers->getQuery()->getResult();

        $this->cache->set($cache_key, serialize($result), Singer::CD_LIST);

        return $result;
    }


    public function findOneBySlugWithCache($slug)
    {
        $cache_key  = 'find_singer_by_slug_'.$slug;

        if ($this->cache->exists($cache_key)) {
            return unserialize($this->cache->get($cache_key));
        }

        $singer = $this->findOneBy(['slug' => $slug, 'status' => Singer::STATUS_ACTIVE]);

        $this->cache->set($cache_key, serialize($singer), Singer::CD_ITEM);

        return $singer;
    }


    public function plusHit($singer_id)
    {
        $em     = $this->getEntityManager();

        $singer = $this->find($singer_id);

        $singer->setHit($singer->getHit() + 1);

        $em->persist($singer);

        $em->flush();
    }
}
