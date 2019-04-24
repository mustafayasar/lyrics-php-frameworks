<?php
namespace backend\controllers;

use backend\models\SingerSearch;
use backend\models\SongSearch;
use common\models\Singer;
use common\models\Song;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Song controller
 */
class SongController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
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


    public function actionIndex()
    {
        $singers = Singer::selectList();

        $searchModel    = new SongSearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'singers'       => $singers,
        ]);
    }

    public function actionCreate()
    {
        $singers    = Singer::selectList();
        $model      = new Song();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'The song is created.');

                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "The song couldn't be created.");
            }
        }

        return $this->render('create', [
            'model'     => $model,
            'singers'   => $singers
        ]);
    }

    public function actionUpdate($id)
    {
        $singers    = Singer::selectList();
        $model      = Song::findOne($id);

        $model->lyrics  = str_replace(["\n", "\t", "\r"], "", $model->lyrics);
        $model->lyrics  = str_replace(['<br />', '<br>'], "\n", $model->lyrics);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'The song is updated.');

                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "The song couldn't be updated.");
            }
        }

        return $this->render('update', [
            'model'     => $model,
            'singers'   => $singers
        ]);
    }

    public function actionDelete($id)
    {
        $song   = Song::findOne($id);

        if ($song->delete()) {
            Yii::$app->session->setFlash('success', 'The song is deleted.');
        }

        return $this->redirect(Yii::$app->request->referrer);;
    }
}