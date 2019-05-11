<?php

namespace App\Controller;

use App\Repository\SingerRepository;
use App\Utils\SearchItems;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    public $letters = ['hit' => 'Hit', 'a'=>'A', 'b'=>'B', 'c'=>'C', 'd'=>'D', 'e'=>'E', 'f'=>'F', 'g'=>'G', 'h'=>'H', 'i'=>'I', 'j'=>'J', 'k'=>'K', 'l'=>'L', 'm'=>'M', 'n'=>'N', 'o'=>'O', 'p'=>'P', 'q'=>'Q', 'r'=>'R', 's'=>'S', 't'=>'T', 'u'=>'U', 'v'=>'V', 'w'=>'W', 'x'=>'X', 'y'=>'Y', 'z'=>'Z', '09'=>'0-9'];

    /**
     * @Route("/", name="home")
     */
    public function index(SingerRepository $singerRepository)
    {
        $limit  = 23;
        $page   = 1;

        $total  = $singerRepository->getListWithCache(false, 'hit', 'get_count');
        $total_page = round($total / $limit);

        echo $total_page;

        foreach ($singerRepository->getListWithCache() as $singer)
        {
            echo $singer->getName().'<br />';
        }

        exit;
        return $this->render('site/index.html.twig');
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
}
