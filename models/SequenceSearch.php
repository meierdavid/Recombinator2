<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sequence;

/**
 * SequenceSearch represents the model behind the search form about `app\models\Sequence`.
 */
class SequenceSearch extends Sequence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permutations_class', 'id_dick_functionnal_structure', 'id_semantics'], 'integer'],
            [['weak_constraint', 'strong_constraint'], 'boolean'],
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
        $query = Sequence::find();

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
            'permutations_class' => $this->permutations_class,
            'weak_constraint' => $this->weak_constraint,
            'strong_constraint' => $this->strong_constraint,
            'id_dick_functionnal_structure' => $this->id_dick_functionnal_structure,
            'id_semantics' => $this->id_semantics,
        ]);

        return $dataProvider;
    }
}
