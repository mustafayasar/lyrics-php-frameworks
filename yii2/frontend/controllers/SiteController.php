<?php
namespace frontend\controllers;

use common\models\ElasticItem;
use common\models\Singer;
use common\models\Song;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

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
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'songs' => Song::getListWithCache(0, false, 'new', 6)
        ]);
    }

    /**
     * @param string $i
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
     * Displays homepage.
     *
     * @return mixed
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
     * @param $singer_slug
     * @param $song_slug
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
     * Displays homepage.
     *
     * @return mixed
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

    public function actionSearch($q)
    {
        $result = ElasticItem::find()->query(["match" => ["title" => $q]])->limit(200)->all();

        return $this->render('search', [
            'q'         => $q,
            'result'    => $result
        ]);
    }


    public function afterAction($action, $result)
    {
        Yii::$app->session->set('last_page', Yii::$app->request->getReferrer());

        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }

}
