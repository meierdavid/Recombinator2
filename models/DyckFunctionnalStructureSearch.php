<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DyckFunctionnalStructure;

/**
 * DyckFunctionnalStructureSearch represents the model behind the search form about `app\models\DyckFunctionnalStructure`.
 */
class DyckFunctionnalStructureSearch extends DyckFunctionnalStructure
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dick_functionnal_structure', 'nb_excisions', 'nb_inversions'], 'integer'],
            [['dick_functionnal_structure'], 'safe'],
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
        $query = DyckFunctionnalStructure::find();

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
            'id_dick_functionnal_structure' => $this->id_dick_functionnal_structure,
            'nb_excisions' => $this->nb_excisions,
            'nb_inversions' => $this->nb_inversions,
        ]);

        $query->andFilterWhere(['like', 'dick_functionnal_structure', $this->dick_functionnal_structure]);

        return $dataProvider;
    }
}
