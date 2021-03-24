<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Sets extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
	    return 'knb_sets';
	}

	public function rules()
	{
	    return [
	        [['name', 'about', 'image'], 'string', 'min'=>2],
	        ['active', 'boolean'],
	        //[['level'], 'integer'],
	        [['name', 'about', 'image'], 'required'],
	    ];
	}

	public function search($params)
    {
    	
        $query = Sets::find();
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
            //'level' => 'Уровень лута',
            'active' => 'Показать',
        ];
    }
}
