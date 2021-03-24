<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Sector;

class SectorConnections extends \yii\db\ActiveRecord
{
	public $image;
	
	public function rules()
	{
	    return [
	        [['sector_parent_id', 'sector_children_id'], 'integer'],
	        [['sector_parent_id', 'sector_children_id'], 'required'],
	        ['sector_children_id', 'validateChildrenId'], 
	        ['sector_parent_id', 'validateParentId'], 
	        ['sector_children_id', 'validateExistsSector'], 
	    ];
	}

	public static function tableName()
	{
	    return 'knb_sector_connections';
	}

	public function validateChildrenId($attribute, $params)
	{
		if(!Sector::find()->where('id=:id', [':id'=>$this->sector_children_id])->one() || !$this->sector_children_id){
			$this->addError($attribute, 'Ошибка, номер сектора не верный');
		}
	}

	public function validateParentId($attribute, $params)
	{	
		if($this->sector_parent_id == $this->sector_children_id){
			 $this->addError($attribute, 'Ошибка, сектор не может указывать на самого себя');
		}else if(!Sector::find()->where('id=:id', [':id'=>$this->sector_parent_id])->one() || !$this->sector_parent_id){
			$this->addError($attribute, 'Ошибка, номер сектора не верный');
		}
	}

	public function validateExistsSector($attribute, $params)
	{
		if($this::find()->where('sector_children_id=:sector_children_id and sector_parent_id=:sector_parent_id', [':sector_children_id'=>$this->sector_children_id, ':sector_parent_id'=>$this->sector_parent_id])->one()){
			$this->addError($attribute, 'Ошибка, сектор уже прикреплен');
		}
	}

	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sector_parent_id' => 'Сектор',
            'sector_children_id' => 'Сектор',
            
        ];
    }

}