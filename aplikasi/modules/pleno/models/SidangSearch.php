<?php

namespace app\modules\pleno\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pleno\models\Sidang;

/**
 * SidangSearch represents the model behind the search form of `app\modules\pleno\models\Sidang`.
 */
class SidangSearch extends Sidang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kunci', 'jenisSidang_id', 'paketPendaftaran_id'], 'integer'],
            [['tanggalSidang', 'deskripsi'], 'safe'],
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
        $query = Sidang::find();

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
            'tanggalSidang' => $this->tanggalSidang,
            'kunci' => $this->kunci,
            'jenisSidang_id' => $this->jenisSidang_id,
            'paketPendaftaran_id' => $this->paketPendaftaran_id,
        ]);

        $query->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
