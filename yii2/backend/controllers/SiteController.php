<?php
namespace backend\controllers;

use common\models\ElasticItem;
use common\models\Singer;
use common\models\Song;
use yii\helpers\Url;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'mysql-to-elastic'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
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
            'singers_count'         => Singer::find()->count(),
            'songs_count'           => Song::find()->count(),
            'search_items_count'    => ElasticItem::find()->count(),
        ]);
    }


    public function actionMysqlToElastic()
    {
        ElasticItem::deleteIndex();

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);


        $singers    = Singer::find()->select(['id', 'slug', 'name', 'status'])->all();

        foreach ($singers as $singer) {
            ElasticItem::saveItem($singer->id, 'singer', $singer);
        }

        $songs      = Song::find()->select(['id', 'slug', 'singer_id', 'title', 'lyrics', 'status'])->with('singer')->all();

        foreach ($songs as $song) {
            ElasticItem::saveItem($song->id, 'song', $song);
        }

        Yii::$app->session->setFlash('success', 'The singer is created.');


        return $this->redirect(['site/index']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
