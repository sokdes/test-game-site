<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
?>


<tr class="row_<?= $model->id?>">
  	<td>
  		<a href="<?php echo Url::to(['set-update', 'id'=>$model->id]);?>">
  			<div class="thumb-image-in-list" style="background:url('<?php echo Yii::$app->params['site_path'].$model->image; ?>') no-repeat center center; background-size: cover; "></div>
  		</a>
  	</td>
	<td><?= HtmlPurifier::process(Html::a($model->name, Url::to(['set-update', 'id'=>$model->id]))) ?></td>
	
	<td><span class="mr_2"><?php 
  			echo ($model->active) ? '<span class="glyphicon glyphicon-eye-open color_green"></span>' : '<span class="glyphicon glyphicon-eye-close color_light_red"></span>' ?>
		</span>
	</td>
	<td>
		<span class="mr_2"><span item_id="<?php echo $model->id; ?>" class="remove glyphicon glyphicon-remove-circle color_red pointer"></span></span>
	</td>
</tr>


    
    
