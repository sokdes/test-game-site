<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Rooms;
use app\models\admin\Structures;

class RoomsConnections extends \yii\db\ActiveRecord
{
	public $name;
	public $image;
	
	
	public function rules()
	{
	    return [
	        [['room_id', 'structura_id', 'parent'], 'integer'],
	        [['room_id', 'structura_id', 'parent'], 'required'],
	        // ['parent', 'boolean'], 
	        ['room_id', 'validateRoomId'], 
	        ['structura_id', 'validateStructuraId'], 
	        ['structura_id', 'validateExistsStructura'], 
	    ];
	}

	public static function tableName()
	{
	    return 'knb_rooms_connections';
	}

	public function getTypeRoom()
	{
		return [1=>'Комната вход', 2=>'Промежуточная комната', 3=>'Комната выход'];
	}

	public function validateRoomId($attribute, $params)
	{
		if(!Rooms::find()->where('id=:id', [':id'=>$this->room_id])->one()){
			$this->addError($attribute, 'Ошибка, номер комнаты не верный');
		}
	}

	public function validateStructuraId($attribute, $params)
	{	
		
		if(!Structures::find()->where('id=:id', [':id'=>$this->structura_id])->one()){
			$this->addError($attribute, 'Ошибка, номер структуры не верный');
		}
	}

	public function validateExistsStructura($attribute, $params)
	{
		if(self::find()->where('structura_id=:structura_id and room_id=:room_id', [':structura_id'=>$this->structura_id, ':room_id'=>$this->room_id])->one()){
			$this->addError($attribute, 'Ошибка, данная комната уже прикреплена к данной структуре.');
		}

		
	}

	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'structura_id' => 'Структура',
            'room_id' => 'Комната',
            
        ];
    }

}