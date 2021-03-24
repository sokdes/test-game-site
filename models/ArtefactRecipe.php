<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Artefact;
use app\models\admin\Loot;

class ArtefactRecipe extends \yii\db\ActiveRecord
{
	// public $name;
	public $image;
	
	public function rules()
	{
	    return [
	        [['loot_id', 'artefact_id', 'quantity_loot'], 'integer'],
	        [['loot_id', 'artefact_id', 'quantity_loot'], 'required'],
	        ['loot_id', 'validateLootId'], 
	        ['artefact_id', 'validateArtefactId'], 
	        ['loot_id', 'validateExistsLoot'], 
	         
	    ];
	}

	public static function tableName()
	{
	    return 'knb_artefact_recipe';
	}

	public function validateLootId($attribute, $params)
	{
		if(!Loot::find()->where('id=:id', [':id'=>$this->loot_id])->one()){
			$this->addError($attribute, 'Ошибка, номер лута не верный');
		}
	}
	

	public function validateArtefactId($attribute, $params)
	{	
		
		if(!Artefact::find()->where('id=:id', [':id'=>$this->artefact_id])->one()){
			$this->addError($attribute, 'Ошибка, номер артефакта не верный');
		}
	}

	public function validateExistsLoot($attribute, $params)
	{
		if(ArtefactRecipe::find()->where('loot_id=:loot_id and artefact_id=:artefact_id', [':artefact_id'=>$this->artefact_id, ':loot_id'=>$this->loot_id])->one()){
			$this->addError($attribute, 'Ошибка, лут уже добавлен');
		}
	}

	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loot_id' => 'Лут',
            'artefact_id' => 'Артефакт',
            'quantity_loot' => 'Кол-во',
            
        ];
    }
}