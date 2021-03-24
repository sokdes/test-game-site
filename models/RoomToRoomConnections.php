<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Rooms;

class RoomToRoomConnections extends \yii\db\ActiveRecord
{
	 public $name;
	 public $image;
	// public $room_parent_id;
	// public $room_children_id;
	
	public function rules()
	{
	    return [
	        [['parent_room_id', 'children_room_id'], 'integer'],
	        [['parent_room_id', 'children_room_id'], 'required'],
	        ['parent_room_id', 'validateParentRoomId'], 
	        ['children_room_id', 'validateChildrenRoomId'], 
	        ['children_room_id', 'validateRoomToRoom'], 
	        ['parent_room_id', 'validateExistsConnection'], 
	    ];
	}

	public static function tableName()
	{
	    return 'knb_room_to_room_connections';
	}

	public function validateParentRoomId($attribute, $params)
	{
		
		if(!Rooms::find()->where('id=:id', [':id'=>$this->parent_room_id])->one()){
			$this->addError($attribute, 'Ошибка, номер комнаты не верный');
		}
	}

	public function validateChildrenRoomId($attribute, $params)
	{
		
		if(!Rooms::find()->where('id=:id', [':id'=>$this->children_room_id])->one()){
			$this->addError($attribute, 'Ошибка, номер комнаты не верный');
		}
	}

	public function validateRoomToRoom($attribute, $params)
	{

		if($this->parent_room_id == $this->children_room_id){
			$this->addError($attribute, 'Ошибка, комната не может ссылаться на себя');	
		}

	}

	public function validateExistsConnection($attribute, $params)
	{
		if(self::find()->where('parent_room_id=:parent_room_id and children_room_id=:children_room_id', [':children_room_id'=>$this->children_room_id, ':parent_room_id'=>$this->parent_room_id])->one()){
			$this->addError($attribute, 'Ошибка, данная комната уже прикреплена к комнате.');
		}

		
	}

	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_room_id' => 'Комната1',
            'children_room_id' => 'Комната2',
        ];
    }

}