<?php
namespace backend\controllers;

use backend\models\SingerSearch;
use common\models\Singer;
use common\models\Song;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Singer controller
 */
class SingerController extends Controller
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
        $searchModel = new SingerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model  = new Singer();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'The singer is created.');

                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "The singer couldn't be created.");
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model  = Singer::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'The singer is updated.');

                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "The singer couldn't be updated.");
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $singer = Singer::findOne($id);

        if ($singer->delete()) {
            $singer_songs   = Song::findAll(['singer_id' => $singer->id]);

            foreach ($singer_songs as $singer_song) {
                $singer_song->delete();
            }

            Yii::$app->session->setFlash('success', 'The singer is deleted.');
        }

        return $this->redirect(Yii::$app->request->referrer);;
    }
}