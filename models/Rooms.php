<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Rooms extends \yii\db\ActiveRecord
{   
    public $structura_name;
    

	public static function tableName()
	{
	    return 'knb_rooms';
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
    	
        $query = Rooms::find()
            ->select('knb_structures.name as structura_name, knb_rooms.id, knb_rooms.name, knb_rooms.about, knb_rooms.image, knb_rooms.active')
            ->leftJoin('knb_rooms_connections', 'knb_rooms_connections.room_id = knb_rooms.id')
            ->leftJoin('knb_structures', 'knb_structures.id = knb_rooms_connections.structura_id');
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
        $dataProvider->sort->attributes['structura'] = [
            'asc'=> ['structura_name'=>SORT_ASC],
            'desc'=> ['structura_name'=>SORT_DESC],
            'label' => 'Структура',
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
            'active' => 'Показать',
        ];
    }
}
