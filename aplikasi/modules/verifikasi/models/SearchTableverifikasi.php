<?php

namespace app\modules\verifikasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\verifikasi\models\Tableverifikasi;

/**
 * SearchTableverifikasi represents the model behind the search form about `app\modules\verifikasi\models\Tableverifikasi`.
 */
class SearchTableverifikasi extends Tableverifikasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'deskripsi', 'dateCreate', 'dateUpdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tableverifikasi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'dateCreate' => $this->dateCreate,
            'dateUpdate' => $this->dateUpdate,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
