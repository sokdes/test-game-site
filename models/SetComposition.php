<?php 
namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\admin\Artefact;
use app\models\admin\Sets;
use yii\helpers\Url;
use yii\helpers\Html;

class SetComposition extends \yii\db\ActiveRecord
{

	public $image;
	
	public static function tableName()
    {
        return 'knb_sets_composition';
    }

    public function rules()
	{
	    return [
	        [['artefact_id', 'set_id'], 'integer'],
	        [['artefact_id', 'set_id'], 'required'],
	        ['artefact_id', 'validateExistsArtefactId'],
		    ['artefact_id', 'validateArtefactId'],
		    ['set_id', 'validateSetsId'],
	    ];
	}

	


    public function validateSetsId($attribute, $params)
	{	
		
		if(!Sets::find()->where('id=:id', [':id'=>$this->set_id])->one()){
			$this->addError($attribute, 'Ошибка, номер сета не верный');
		}
	}

	public function validateArtefactId($attribute, $params)
	{	
		
		if(!Artefact::find()->where('id=:id', [':id'=>$this->artefact_id])->one()){
			$this->addError($attribute, 'Ошибка, номер артефакта не верный');
		}
	}

	public function validateExistsArtefactId($attribute, $params)
	{
		if($model = self::find()->where('artefact_id=:artefact_id', [':artefact_id'=>$this->artefact_id])->one()){
			if($model->set_id != $this->set_id){

				$link = Html::a('другой сет', Url::to(['set-update', 'id'=>$model->set_id]), ['class'=>'color_red link_border']);
			}else{
				$link = 'этот сет';
			}
			$this->addError($attribute, 'Ошибка, артефакт уже добавлен в '.$link );
		}
	}

	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'artefact_id' => 'Артефакт',
            'set_id' => 'Сет',
        ];
    }

    

}