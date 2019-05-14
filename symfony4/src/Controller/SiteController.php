<?php

namespace App\Controller;

use App\Repository\SingerRepository;
use App\Repository\SongRepository;
use App\Utils\SearchItems;
use App\Utils\SiteHelper;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    private $session;

    public $letters = ['hit' => 'Hit', 'a'=>'A', 'b'=>'B', 'c'=>'C', 'd'=>'D', 'e'=>'E', 'f'=>'F', 'g'=>'G', 'h'=>'H', 'i'=>'I', 'j'=>'J', 'k'=>'K', 'l'=>'L', 'm'=>'M', 'n'=>'N', 'o'=>'O', 'p'=>'P', 'q'=>'Q', 'r'=>'R', 's'=>'S', 't'=>'T', 'u'=>'U', 'v'=>'V', 'w'=>'W', 'x'=>'X', 'y'=>'Y', 'z'=>'Z', '09'=>'0-9'];

    public function __construct(SessionInterface $session)
    {
        $this->session  = $session;
    }

    /**
     * @Route("/", name="home")
     *
     * @param SongRepository $songRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SongRepository $songRepository)
    {
        $new_songs  = $songRepository->getListWithCache(0, false, 'new', 8);

        return $this->render('site/index.html.twig', ['songs' => $new_songs]);
    }

    /**
     * @Route("/singers/{i}/{page}", name="singers", requirements={"page"="\d+"}, defaults={"page"="1"})
     *
     * @param SingerRepository $singerRepository
     * @param $i
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function singers(SingerRepository $singerRepository, $i, $page = 1)
    {
        $limit  = 20;

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Singers';
        } elseif (isset($this->letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Singers Beginning with '.$this->letters[$i];
        } else {
            return $this->render('site/error.html.twig', ['message' => 'Not Found']);
        }

        $total      = $singerRepository->getListWithCache($initial, $order, 'get_count');
        $total_page = ceil($total / $limit);

        if ($page > $total_page) {
            $page   = $total_page;
        } elseif ($page < 1) {
            $page   = 1;
        }

        $singers    = $singerRepository->getListWithCache($initial, $order, $limit, $page);

        return $this->render('site/singers.html.twig', [
            'title'         => $title,
            'letters'       => $this->letters,
            'singers'       => $singers,
            'total_page'    => $total_page,
            'page'          => $page,
            'i'             => $i,
        ]);
    }

    /**
     * @Route("/songs/{i}/{page}", name="songs", requirements={"page"="\d+"}, defaults={"page"="1"})
     *
     * @param SongRepository $songRepository
     * @param $i
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function songs(SongRepository $songRepository, $i, $page = 1)
    {
        $limit  = 20;

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Songs';
        } elseif (isset($this->letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Songs Beginning with '.$this->letters[$i];
        } else {
            return $this->render('site/error.html.twig', ['message' => 'Not Found']);
        }

        $total      = $songRepository->getListWithCache(0, $initial, $order, 'get_count');
        $total_page = ceil($total / $limit);

        if ($page > $total_page) {
            $page   = $total_page;
        } elseif ($page < 1) {
            $page   = 1;
        }

        $songs  = $songRepository->getListWithCache(0, $initial, $order, $limit, $page);

        return $this->render('site/songs.html.twig', [
            'title'         => $title,
            'letters'       => $this->letters,
            'songs'         => $songs,
            'total_page'    => $total_page,
            'page'          => $page,
            'i'             => $i,
        ]);
    }

    /**
     * @Route("/{singer_slug}-songs/{page}", name="singer_songs", requirements={"page"="\d+", "singer_slug"=".+"}, defaults={"page"="1"})
     *
     * @param SingerRepository $singerRepository
     * @param SongRepository $songRepository
     * @param $singer_slug
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function singerSongs(SingerRepository $singerRepository, SongRepository $songRepository, $singer_slug, $page = 1)
    {
        $singer = $singerRepository->findOneBySlugWithCache($singer_slug);

        if ($singer) {
            $limit      = 6;
            $total      = $songRepository->getListWithCache($singer->getId(), false, 'new', 'get_count');
            $total_page = ceil($total / $limit);

            if ($page > $total_page) {
                $page   = $total_page;
            } elseif ($page < 1) {
                $page   = 1;
            }

            $songs  = $songRepository->getListWithCache($singer->getId(), false, 'new', $limit, $page);


            if ($this->session->get('last_singer') != $singer->getId()) {
                $singerRepository->plusHit($singer->getId());
            }

            $this->session->set('last_singer', $singer->getId());

            return $this->render('site/singer-songs.html.twig', [
                'singer'        => $singer,
                'songs'         => $songs,
                'total_page'    => $total_page,
                'page'          => $page
            ]);
        }

        return $this->render('site/error.html.twig', ['message' => 'Not Found']);
    }

    /**
     * @Route("/{singer_slug}/{song_slug}-lyrics", name="song_view", requirements={"singer_slug"=".+", "song_slug"=".+"})
     *
     * @param SingerRepository $singerRepository
     * @param SongRepository $songRepository
     * @param $singer_slug
     * @param $song_slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function songView(SingerRepository $singerRepository, SongRepository $songRepository, $singer_slug, $song_slug)
    {
        $singer = $singerRepository->findOneBySlugWithCache($singer_slug);

        if ($singer) {
            $song   = $songRepository->findOneBySlugWithCache($singer->getId(), $song_slug);

            if ($song) {
                $song->setHit($song->getHit() + 1);

                if ($this->session->get('last_song') != $song->getId()) {
                    $songRepository->plusHit($song->getId());
                    $singerRepository->plusHit($song->getSingerId());
                }

                $this->session->set('last_song', $song->getId());

                return $this->render('site/song-view.html.twig', [
                    'song'  => $song
                ]);
            }
        }

        return $this->render('site/error.html.twig', ['message' => 'Not Found']);
    }

    /**
     * @Route("/random-lyrics", name="random_song")
     *
     * @param SingerRepository $singerRepository
     * @param SongRepository $songRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function randomSongView(SingerRepository $singerRepository, SongRepository $songRepository)
    {
        $song   = $songRepository->getRandomSong();

        if ($song) {
            $song->setHit($song->getHit() + 1);

            if ($this->session->get('last_song') != $song->getId()) {
                $songRepository->plusHit($song->getId());
                $singerRepository->plusHit($song->getSingerId());
            }

            $this->session->set('last_song', $song->getId());

            return $this->render('site/song-view.html.twig', [
                'song'  => $song
            ]);
        }

        return $this->render('site/error.html.twig', ['message' => 'Not Found']);
    }



    /**
     * @Route("/search", name="search")
     *
     * @param $q
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $q  = $request->get('q');

        $search_items   = new SearchItems();

        $result = $search_items->search($q);

        return $this->render('site/search.html.twig', [
            'q'         => $q,
            'result'    => $result
        ]);
    }
}
