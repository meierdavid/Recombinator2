<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sequences;

/**
 * SequencesSearch represents the model behind the search form about `app\models\Sequences`.
 */
class SequencesSearch extends Sequences
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sequence', 'weak_constraint', 'strong_constraint', 'size', 'nb_genes', 'genes_at_ends', 'id_permutation_class'], 'integer'],
            [['semantics', 'functional_structure'], 'safe'],
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
        $query = Sequences::find();

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
            'id_sequence' => $this->id_sequence,
            'weak_constraint' => $this->weak_constraint,
            'strong_constraint' => $this->strong_constraint,
            'size' => $this->size,
            'nb_genes' => $this->nb_genes,
            'genes_at_ends' => $this->genes_at_ends,
            'id_permutation_class' => $this->id_permutation_class,
        ]);

        $query->andFilterWhere(['like', 'semantics', $this->semantics])
            ->andFilterWhere(['like', 'functional_structure', $this->functional_structure]);

        return $dataProvider;
    }
}
