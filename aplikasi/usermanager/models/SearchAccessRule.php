<?php

namespace app\usermanager\Models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\usermanager\models\AccessRole;

/**
 * SearchAccessRule represents the model behind the search form about `app\usermanager\models\AccessRole`.
 */
class SearchAccessRule extends AccessRole
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['roleName', 'roleDescription', 'ruleSettings', 'dateCreate', 'dateUpdate'], 'safe'],
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
        $query = AccessRole::find();

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

        $query->andFilterWhere(['like', 'roleName', $this->roleName])
            ->andFilterWhere(['like', 'roleDescription', $this->roleDescription])
            ->andFilterWhere(['like', 'ruleSettings', $this->ruleSettings]);

        return $dataProvider;
    }
}
