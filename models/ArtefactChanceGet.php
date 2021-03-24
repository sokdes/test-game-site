<?php
namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Sector;
use app\models\admin\Artefact;

class ArtefactChanceGet extends \yii\db\ActiveRecord
{
	public $name;
	public $image;

	public function rules()
	{
	    return [
	        [['sector_id', 'artefact_id', 'chance_get'], 'integer'],
	        [['sector_id', 'artefact_id', 'chance_get'], 'required'],
	        ['sector_id', 'validateExistsSector'],
	        ['sector_id', 'validateSectorId'],
	        ['artefact_id', 'validateArtefactId'],
	         
	    ];
	}

	public static function tableName()
	{
	    return 'knb_artefact_chance_get';
	}

	public function validateSectorId($attribute, $params)
	{	
		
		if(!Sector::find()->where('id=:id', [':id'=>$this->sector_id])->one()){
			$this->addError($attribute, 'Ошибка, номер сектора не верный');
		}
	}

	public function validateArtefactId($attribute, $params)
	{	
		
		if(!Artefact::find()->where('id=:id', [':id'=>$this->artefact_id])->one()){
			$this->addError($attribute, 'Ошибка, номер сектора не верный');
		}
	}

	public function validateExistsSector($attribute, $params)
	{
		if(ArtefactChanceGet::find()->where('artefact_id=:artefact_id and sector_id=:sector_id', [':sector_id'=>$this->sector_id, ':artefact_id'=>$this->artefact_id])->one()){
			$this->addError($attribute, 'Ошибка, сектор уже добавлен');
		}
	}
	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sector_id'=> 'Сектор', 
            'artefact_id'=> 'Артефакт', 
            'chance_get'=> 'Шанс получения'
            
        ];
    }
}