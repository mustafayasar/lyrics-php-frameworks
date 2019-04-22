<?php
namespace backend\models;

use common\models\Singer;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class SingerSearch extends Singer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            ['name', 'safe'],
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
        $query = Singer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->id > 0) {
            $query->andFilterWhere(['id' => $this->id]);
        }

        if ($this->name != '') {
            $query->andFilterWhere(['like', 'name', $this->name]);
        }

        if (ctype_digit($this->status)) {
            $query->andFilterWhere(['status' => $this->status]);
        }

        return $dataProvider;
    }
}