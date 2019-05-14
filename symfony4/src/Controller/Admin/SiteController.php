<?php

namespace App\Controller\Admin;

use App\Repository\SingerRepository;
use App\Repository\SongRepository;
use App\Utils\SearchItems;
use App\Utils\SiteHelper;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/admin/mysql-to-elastic", name="mysql_to_elastic")
     *
     * @param SingerRepository $singerRepository
     * @param SongRepository $songRepository
     *
     * @return string
     */
    public function mysqlToElastic(SingerRepository $singerRepository, SongRepository $songRepository)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $search_items   = new SearchItems();

        $search_items->deleteIndex();

        sleep(4);

        $search_items->createIndex();


        $all_singers    = $singerRepository->findAll();

        foreach ($all_singers as $singer)
        {
            $search_items->saveItem($singer->getId(), 'singer',
                $this->generateUrl('singer_songs', ['singer_slug' => $singer->getSlug()]),
                $singer->getName(),
                '',
                $singer->getStatus());
        }


        $all_songs  = $songRepository->findAll();

        foreach ($all_songs as $song)
        {
            $search_items->saveItem($song->getId(), 'song',
                $this->generateUrl('song_view', ['singer_slug' => $song->getSinger()->getSlug(), 'song_slug' => $song->getSlug()]),
                $song->getTitle().' - '.$song->getSinger()->getName(),
                $song->getLyrics(),
                $song->getStatus());
        }

        $this->addFlash('success', 'Completed!');

        return $this->redirect('/admin');
    }


    /**
     * @Route("/admin/flush-redis", name="flush_redis")
     *
     * @param ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function flushRedis(ContainerInterface $container)
    {
        $cache = RedisAdapter::createConnection($container->getParameter('redis_dsn'));

        $cache->flushAll();

        $this->addFlash('success', 'Completed!');

        return $this->redirect('/admin');
    }

    /**
     * @Route("/admin/info", name="info")
     *
     * @param ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function info(ContainerInterface $container)
    {
        $search_items   = new SearchItems();

        dump($search_items->getTotalCount());
        echo '<br />';
        echo '<br />';
        dump($search_items->search('Moby'));

        exit;
    }
}
