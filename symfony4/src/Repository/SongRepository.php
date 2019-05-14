<?php

namespace App\Repository;

use App\Entity\Singer;
use App\Entity\Song;
use App\Utils\AdminHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    private $cache;

    public function __construct(RegistryInterface $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Song::class);

        $this->cache = RedisAdapter::createConnection($container->getParameter('redis_dsn'));
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


    public function getListWithCache($singer_id = 0, $initial = false, $order = 'name', $limit = 14, $page = 0)
    {
        $cache_key  = 'song-list_'.$singer_id.'_'.$initial.'_'.$order.'_'.$limit.'_'.$page;

        if ($this->cache->exists($cache_key)) {
            return unserialize($this->cache->get($cache_key));
        }

        $songs  = $this->createQueryBuilder('s')
                    ->andWhere('s.status = :status')
                    ->setParameter('status', Song::STATUS_ACTIVE);

        if ($singer_id > 0) {
            $songs->andWhere('s.singer_id = :singer_id')
                ->setParameter('singer_id', $singer_id);
        }

        if (!empty($initial)) {
            if ($initial == '09') {
                $songs->andWhere("s.slug LIKE '0%' OR s.slug LIKE '1%' OR s.slug LIKE '2%' OR s.slug LIKE '3%' OR s.slug LIKE '4%' OR s.slug LIKE '5%' OR s.slug LIKE '6%' OR s.slug LIKE '7%' OR s.slug LIKE '8%' OR s.slug LIKE '9%'");
            } else {
                $songs->andWhere("s.slug LIKE '$initial%'");
            }
        }

        if ($limit === 'get_count') {
            $result = $songs->select('COUNT(s.id)')->getQuery()->getSingleScalarResult();

            $this->cache->set($cache_key, serialize($result), Song::CD_LIST);

            return $result;
        } elseif ($limit > 0) {
            $page   = $page < 2 ? 1 : $page;
            $offset = ($page - 1) * $limit;

            $songs->setMaxResults($limit)->setFirstResult($offset);
        }

        if ($order == 'new') {
            $songs->addOrderBy("s.created_at","DESC");
            $songs->addOrderBy("s.id","DESC");
        } elseif ($order == 'name') {
            $songs->addOrderBy("s.created_at","ASC");
            $songs->addOrderBy("s.id","ASC");
        } elseif ($order == 'hit') {
            $songs->orderBy("s.hit","DESC");
        } elseif ($order == 'name') {
            $songs->orderBy("s.slug","ASC");
        }

        $result = $songs->getQuery()->getResult();

        $this->cache->set($cache_key, serialize($result), Song::CD_LIST);

        return $result;
    }


    public function findOneBySlugWithCache($singer_id, $song_slug)
    {
        $cache_key  = 'find_song_by_slugs_'.$singer_id.'_'.$song_slug;

        if ($this->cache->exists($cache_key)) {
            return unserialize($this->cache->get($cache_key));
        }

        $song   = $this->findOneBy(['singer_id' => $singer_id, 'slug' => $song_slug, 'status' => Song::STATUS_ACTIVE]);

        $this->cache->set($cache_key, serialize($song), Song::CD_ITEM);

        return $song;
    }


    public function getRandomSong()
    {
        $cache_key  = 'randoma_songs';

        if ($this->cache->exists($cache_key)) {
            $songs  = unserialize($this->cache->get($cache_key));
        } else {
            $songs  = $this->createQueryBuilder('s')
                ->orderBy("s.hit","DESC")
                ->setMaxResults(5000)
                ->getQuery()->getResult();

            shuffle($songs);

            $this->cache->set($cache_key, serialize($songs), Song::CD_RANDOM_LIST);
        }

        return $songs[rand(0, (count($songs)-1))];
    }


    public function plusHit($song_id)
    {
        $em     = $this->getEntityManager();

        $song   = $this->find($song_id);

        $song->setHit($song->getHit() + 1);

        $em->persist($song);

        $em->flush();
    }

    public function updateSongsStatus($singer_id, $current_status, $new_status)
    {
        $em     = $this->getEntityManager();

        $songs  = $this->findBy(['singer_id' => $singer_id, 'status' => $current_status]);

        foreach ($songs as $song) {
            $song->setStatus($new_status);

            $em->persist($song);
        }

        $em->flush();
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
