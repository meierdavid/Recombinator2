<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PermutationClass;

/**
 * PermutationClassSearch represents the model behind the search form about `app\models\PermutationClass`.
 */
class PermutationClassSearch extends PermutationClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permutation_class', 'nb_inputs'], 'integer'],
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
        $query = PermutationClass::find();

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
            'permutation_class' => $this->permutation_class,
            'nb_inputs' => $this->nb_inputs,
        ]);

        return $dataProvider;
    }
}
