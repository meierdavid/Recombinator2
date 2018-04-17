<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Semantics;

/**
 * SemanticsSearch represents the model behind the search form about `app\models\Semantics`.
 */
class SemanticsSearch extends Semantics
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_semantics', 'length', 'nb_genes', 'nb_parts'], 'integer'],
            [['semantics'], 'safe'],
            [['gene_at_ends'], 'boolean'],
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
        $query = Semantics::find();

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
            'id_semantics' => $this->id_semantics,
            'length' => $this->length,
            'nb_genes' => $this->nb_genes,
            'nb_parts' => $this->nb_parts,
            'gene_at_ends' => $this->gene_at_ends,
        ]);

        $query->andFilterWhere(['like', 'semantics', $this->semantics]);

        return $dataProvider;
    }
}
