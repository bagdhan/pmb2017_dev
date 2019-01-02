<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\GenNrp;

/**
 * GenNrpSearch2 represents the model behind the search form about `app\modules\admin\models\GenNrp`.
 */
class GenNrpSearch extends GenNrp
{
    /**
     * @inheritdoc
     */

    public $nrp;

    public function rules()
    {
        return [
            [['id', 'kodeKhusus', 'noUrut', 'kodeMasuk', 'lockNrp'], 'integer'],
            [['noPendaftaran', 'kodeProdi', 'tahunMasuk', 'dateCreate', 'dateUpdate', 'nrp'], 'safe'],
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
        $query = GenNrp::find();

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

        $kodeprodi = substr($this->nrp, 0, 4);
        $nourut = substr($this->nrp, 7, 2);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kodeKhusus' => $this->kodeKhusus,
            'noUrut' => $this->noUrut,
            'kodeMasuk' => $this->kodeMasuk,
            'lockNrp' => $this->lockNrp,
            'dateCreate' => $this->dateCreate,
            'dateUpdate' => $this->dateUpdate,
        //    'nourut' => (int)$nourut,
        ]);

        if (strlen($kodeprodi) > 0)
            $query->andFilterWhere([
                   'noUrut' => (int)$nourut,
            ])->andFilterWhere(['like', 'kodeProdi', $kodeProdi]);

        $query->andFilterWhere(['like', 'noPendaftaran', $this->noPendaftaran])
            ->andFilterWhere(['like', 'kodeProdi', $this->kodeProdi])
            //->andFilterWhere(['like', 'kodeprodi', $kodeprodi])
            ->andFilterWhere(['like', 'tahunMasuk', $this->tahunMasuk]);

        return $dataProvider;
    }
}
