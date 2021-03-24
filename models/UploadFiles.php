<?php

namespace app\models\admin;	
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFiles extends Model
{
	public $imageFile;
	public $imagePath;
    public $localImagePath;
    public $razdelName;

	public function rules()
    {
        return [
            ['razdelName', 'string'],
            [['razdelName'], 'required'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024*1024*2, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload()
    {
    	if ($this->validate()) {
        	$dir_upload = '/images/'.$this->razdelName;
        	$path =  $_SERVER['DOCUMENT_ROOT'].Yii::getAlias('@web');
            // $path =  $_SERVER['DOCUMENT_ROOT'].Yii::getAlias('@web');
            $name = $this->imageFile->baseName.'_'.mt_rand().'.'.$this->imageFile->extension;
            
            $this->existsDir($path.$dir_upload);

        	$this->imagePath = $path.$dir_upload.'/'.$name;
            $this->localImagePath = '/web'.$dir_upload.'/'.$name;
            //$this->localImagePath = Yii::getAlias('@web').$dir_upload.'/'.$name;

            $this->imageFile->saveAs($this->imagePath);
            
            return true;

        } else {
            return false;
        }
    }

    public function remove()
    {
        
    }

    protected function existsDir($path)
    {
        
        if(!file_exists($path))
        {
            mkdir($path);   
        }
    }

}