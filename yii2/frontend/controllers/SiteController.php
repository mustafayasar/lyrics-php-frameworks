<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\ElasticItem;
use common\models\Singer;
use common\models\Song;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'songs' => Song::getListWithCache(0, false, 'new', 6)
        ]);
    }

    /**
     * Displays singers
     *
     * @param string $i a letter or hit
     *
     * @return string
     */
    public function actionSingers($i = 'hit')
    {
        $letters    = Yii::$app->params['letters'];

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Singers';
        } elseif (isset($letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Singers Beginning with '.$letters[$i];
        } else {
            return $this->render('error', ['message' => 'Not Found']);
        }

        $pages  = new Pagination([
            'totalCount'        => Singer::getListWithCache($initial, $order, 'get_count'),
            'pageSize'          => 20,
            'defaultPageSize'   => 20,
            'forcePageParam'    => false,
        ]);

        $singers    = Singer::getListWithCache($initial, $order, $pages);

        return $this->render('singers', [
            'title'     => $title,
            'letters'   => $letters,
            'singers'   => $singers,
            'pages'     => $pages,
        ]);
    }

    /**
     * Displays songs
     *
     * @param string $i a letter or hit
     *
     * @return string
     */
    public function actionSongs($i='top')
    {
        $letters    = Yii::$app->params['letters'];

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Songs';
        } elseif (isset($letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Songs Beginning with '.$letters[$i];
        } else {
            return $this->render('error', ['message' => 'Not Found']);
        }

        $pages  = new Pagination([
            'totalCount'        => Song::getListWithCache(0, $initial, $order, 'get_count'),
            'pageSize'          => 20,
            'defaultPageSize'   => 20,
            'forcePageParam'    => false,
        ]);

        $songs  = Song::getListWithCache(0, $initial, $order, $pages);

        return $this->render('songs', [
            'title'     => $title,
            'letters'   => $letters,
            'songs'     => $songs,
            'pages'     => $pages,
        ]);
    }

    /**
     * Displays songs of a singer with singer's slug.
     *
     * @param $singer_slug
     *
     * @return string
     */
    public function actionSingerSongs($singer_slug)
    {
        $singer = Singer::findOneBySlugWithCache($singer_slug);

        if ($singer) {
            $pages  = new Pagination([
                'totalCount'        => Song::getListWithCache($singer->id, false, 'name', 'get_count'),
                'pageSize'          => 20,
                'defaultPageSize'   => 20,
                'forcePageParam'    => false,
            ]);

            $songs  = Song::getListWithCache($singer->id, false, 'name', $pages);

            $singer->hit    = $singer->hit + 1;

            if (Yii::$app->session->get('last_page') != Yii::$app->request->getReferrer()) {
                Singer::plusHit($singer->id);
            }

            return $this->render('singer-songs', [
                'singer'    => $singer,
                'songs'     => $songs,
                'pages'     => $pages,
            ]);

        } else {
            Yii::$app->response->statusCode = 404;

            return $this->render('error', ['name' => 'Not Found']);
        }
    }

    /**
     * Displays a song with slugs
     *
     * @param $singer_slug
     * @param $song_slug
     *
     * @return string
     */
    public function actionSongView($singer_slug, $song_slug)
    {
        $song   = Song::findOneBySlugsWithCache($singer_slug, $song_slug);

        if ($song) {
            $song->hit    = $song->hit + 1;

            if (Yii::$app->session->get('last_page') != Yii::$app->request->getReferrer()) {
                Song::plusHit($song->id);
                Singer::plusHit($song->singer->id);
            }

            return $this->render('song-view', [
                'song'  => $song
            ]);
        } else {
            Yii::$app->response->statusCode = 404;

            return $this->render('error', ['name' => 'Not Found']);
        }
    }

    /**
     * Displays a random song.
     *
     * @return string
     */
    public function actionRandomSongView()
    {
        $song   = Song::getRandomSong();

        if ($song) {
            $song->hit = $song->hit + 1;

            if (Yii::$app->session->get('last_page') != Yii::$app->request->getReferrer()) {
                Song::plusHit($song->id);
                Singer::plusHit($song->singer->id);
            }

            return $this->render('song-view', [
                'song'  => $song
            ]);
        } else {
            Yii::$app->response->statusCode = 404;

            return $this->render('error', ['name' => 'Not Found']);
        }
    }

    /**
     * Searches with a term
     *
     * @param $q
     *
     * @return string
     */
    public function actionSearch($q)
    {
        $result = ElasticItem::search($q);

        return $this->render('search', [
            'q'         => $q,
            'result'    => $result
        ]);
    }

    /**
     * afterAction
     *
     * @param \yii\base\Action $action
     * @param mixed $result
     *
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        // We set a last_page not to increase hit when reloading
        Yii::$app->session->set('last_page', Yii::$app->request->getReferrer());

        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }
}
