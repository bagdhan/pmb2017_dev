<?php

namespace app\modules\cruddb\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modelsDB\Orang;

/**
 * SearchOrang represents the model behind the search form about `app\modelsDB\Orang`.
 */
class SearchOrang extends Orang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'jenisKelamin', 'statusPerkawinan_id', 'negara_id'], 'integer'],
            [['nama', 'KTP', 'tempatLahir', 'tanggalLahir', 'NPWP', 'waktuBuat', 'waktuUbah'], 'safe'],
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
        $query = Orang::find();

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
            'tanggalLahir' => $this->tanggalLahir,
            'jenisKelamin' => $this->jenisKelamin,
            'statusPerkawinan_id' => $this->statusPerkawinan_id,
            'waktuBuat' => $this->waktuBuat,
            'waktuUbah' => $this->waktuUbah,
            'negara_id' => $this->negara_id,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'KTP', $this->KTP])
            ->andFilterWhere(['like', 'tempatLahir', $this->tempatLahir])
            ->andFilterWhere(['like', 'NPWP', $this->NPWP]);

        return $dataProvider;
    }
}
