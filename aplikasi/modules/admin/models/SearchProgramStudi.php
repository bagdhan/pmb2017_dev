<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProgramStudi;

/**
 * SearchProgramStudi represents the model behind the search form about `app\models\ProgramStudi`.
 */
class SearchProgramStudi extends ProgramStudi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'aktif', 'strata', 'departemen_id'], 'integer'],
            [['kode', 'nama', 'nama_en', 'inisial', 'kode_nasional', 'sk_pendirian', 'mandat', 'visi_misi'], 'safe'],
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
        $query = ProgramStudi::find();

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
            'aktif' => $this->aktif,
            'strata' => $this->strata,
            'departemen_id' => $this->departemen_id,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nama_en', $this->nama_en])
            ->andFilterWhere(['like', 'inisial', $this->inisial])
            ->andFilterWhere(['like', 'kode_nasional', $this->kode_nasional])
            ->andFilterWhere(['like', 'sk_pendirian', $this->sk_pendirian])
            ->andFilterWhere(['like', 'mandat', $this->mandat])
            ->andFilterWhere(['like', 'visi_misi', $this->visi_misi]);

        return $dataProvider;
    }
}
