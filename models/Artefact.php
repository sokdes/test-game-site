<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Artefact extends \yii\db\ActiveRecord
{

	public $set_id;
    public $set_name;

	// public $chance_of_get;


	public static function tableName()
	{
	    return 'knb_artefact';
	}

	
	public function rules()
	{
	    return [
	        [['name', 'about', 'image'], 'string', 'min'=>2],
	        ['active', 'boolean'],
	        [['name', 'about', 'image'], 'required'],
            
	    ];
	}

	public function search($params)
    {
    	
        $query = Artefact::find()
            ->select('knb_sets_composition.set_id as set_id, knb_sets.name as set_name, knb_artefact.id, knb_artefact.name, knb_artefact.about, knb_artefact.image, knb_artefact.active')
            ->leftJoin('knb_sets_composition', 'knb_sets_composition.artefact_id = knb_artefact.id')
            ->leftJoin('knb_sets', 'knb_sets.id = knb_sets_composition.set_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25
            ],
        ]);
        $dataProvider->sort->attributes['name'] = [
        	'asc'=> ['name'=>SORT_ASC],
        	'desc'=> ['name'=>SORT_DESC],
            'label' => 'Наименование',
        	// 'default'=> 'DESC',
        ];
        $dataProvider->sort->attributes['set_name'] = [
            'asc'=> ['set_name'=>SORT_ASC],
            'desc'=> ['set_name'=>SORT_DESC],
            'label' => 'Сет',
            // 'default'=> 'DESC',
        ];
        $dataProvider->sort->defaultOrder['name'] = SORT_ASC;
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'active', $this->active]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'about' => 'Описание',
            'image' => 'Изображение',
            'set_name' => 'Сет',
            'active' => 'Показать',
        ];
    }
}

