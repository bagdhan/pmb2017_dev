<?php

namespace app\modules\cruddb\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modelsDB\KabupatenKota;

/**
 * SearchKabupatenKota represents the model behind the search form about `app\modelsDB\KabupatenKota`.
 */
class SearchKabupatenKota extends KabupatenKota
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'namaID', 'namaEN', 'propinsi_kode'], 'safe'],
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
        $query = KabupatenKota::find();

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
        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'namaID', $this->namaID])
            ->andFilterWhere(['like', 'namaEN', $this->namaEN])
            ->andFilterWhere(['like', 'propinsi_kode', $this->propinsi_kode]);

        return $dataProvider;
    }
}
