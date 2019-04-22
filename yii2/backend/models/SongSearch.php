<?php
namespace backend\models;

use common\models\Singer;
use common\models\Song;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class SongSearch extends Song
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'singer_id', 'status'], 'integer'],
            ['title', 'safe'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Song::find()->with('singer');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->id > 0) {
            $query->andFilterWhere(['id' => $this->id]);
        }

        if ($this->singer_id > 0) {
            $query->andFilterWhere(['singer_id' => $this->singer_id]);
        }

        if ($this->title != '') {
            $query->andFilterWhere(['like', 'title', $this->title]);
        }

        if (ctype_digit($this->status)) {
            $query->andFilterWhere(['status' => $this->status]);
        }

        return $dataProvider;
    }
}