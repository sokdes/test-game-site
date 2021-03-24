<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = 'Административная часть';
$currentController = Yii::$app->controller->action->controller->module->requestedRoute;
$pageName = "Сет - ". $setsModel->name;
?>
<div class="site-index">

    <div class="body-content">
    	<div class="row">
            <div class="col-lg-3 col-md-3 col-sx-3 col-sm-3 mt_3 menu-left">
                  
                    <?php 
                    
                    

                    foreach ($adminMenu as $menuItem) {

                        $link = 'admin/'.$menuItem->link;
                        

                        if($link == $currentController){
                            $pageName = $menuItem->name; 
                        }  

                        echo '<div class="row"><div class="col-lg-12">';
                        echo Html::a($menuItem->name, Url::to(['admin/'.$menuItem->link]));
                        echo '</div></div>';
                        
                    
                    }
                    
                    ?>

            </div>
            <div class="col-lg-8 col-md-8 col-sx-8 col-sm-8">
                <div class="visible-sx hidden-sm  hidden-md hidden-lg mt_3">
                    

                </div>
                <div class="content">
                    <h3><?php echo $this->title ." - ".$pageName;?></h3>
                    <div class="col-lg-12 mt_1 mb_3 pt_2 block-update-main-info">

                        <div class="row">
                            <div class="col-lg-9">
                            <!-- $artefactModel -->
                            <?php $form = ActiveForm::begin(['id' => 'set-update-form']); ?>

                            <?= $form->field($setsModel, 'name')->textInput() ?>
                            
                            
                            <?= $form->field($setsModel, 'image')->hiddenInput()->label('') ?>

                            
                            
                            <?php // echo $form->field($monsterModel, 'image')->fileInput(['class'=>'btn']); ?>

                            <?= $form->field($setsModel, 'about')->textarea(['rows' => '6'])  ?>

                            <?php 
                                $check = ($setsModel->active) ? 'checked' : 'unchecked';
                                echo $form->field($setsModel, 'active')->checkbox(['label' => 'Показывать', 'labelOptions' => ['style' => 'padding-left:20px;'], $check => $check]); 
                                
                                ?>
                            </div> 
                            <div class="col-lg-2 mt_4">
                                <img src="<?php echo Yii::$app->params['site_path'].$setsModel->image; ?>" width="auto" height="100px" class="artefact-image">
                            </div>
                        </div>    

                            <?php ActiveForm::end(); ?>
                            
                    </div>


                    <!--  Set Composition  start -->
                    <div class="sector-cons-sections-from block-update-second-info col-lg-12 mt_1 mb_3">


                        <div class="row">
                            <h4><strong>Состав:</strong></h4>
                        </div>
                        <div class="row">
                            <table class="table table-bordered sector-cons-sections-from-table"><tbody>
                                <?php 
                                foreach($setComposition as $compositionItem)
                                {
                                    echo '<tr class="set-composition set-composition-id-'.$compositionItem->id.'">';

                                    $image_block = '<a href="'.Url::to(['artefact-update', 'id'=>$compositionItem->artefact_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$compositionItem->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';

                                    $artefact_name = ($compositionItem->artefact_id && isset($artefactList[$compositionItem->artefact_id])) ? $artefactList[$compositionItem->artefact_id] : "---Удалено---";
                                    
                                    echo ($compositionItem->artefact_id && isset($artefactList[$compositionItem->artefact_id])) ? Html::a($artefact_name, Url::to(['artefact-update', 'id'=>$compositionItem->artefact_id])) : $artefact_name;

                                    echo '</td>';
                                    echo '<td class=""><span item_id="'.$compositionItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeSetComposition('.$compositionItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 

                            </tbody></table>
                        </div>

                        <div class="row">
                            <div class="col-lg-10">
                                <?php if($artefactList): ?>

                            <?php $formComposition = ActiveForm::begin(['id' => 'set_composition_form']); ?>
                                
                                    <div class="col-lg-7"><?php echo $formComposition->field($setCompositionModel, 'artefact_id')->dropDownList($artefactList, ['id'=>'set-composition_artefact_id', 'prompt'=>'- Выбрать артефакт'])->label(''); ?></div>
                                    <div class="col-lg-3 ">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addSetComposition", "form"=>"set_composition_form", "class"=>"btn mt_3"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 
                            </div>
                        </div>
                        <div class="error_composition_text color_red"></div>
                    </div>

                    <!-- Set Composition end -->

                    <div class="form-group">
                        <div class="col-lg-6">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', "form"=>"set-update-form", 'name' => 'set-button']) ?>
                        </div>
                        <div class="col-lg-6 text-right">
                            <?= Html::a('Отменить', 'sets', ['class' => 'btn btn-danger', "form"=>"set-update-form", 'name' => 'set-button-cansel']) ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function removeSetComposition(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 
        $(".error_text").text(""); 
        $.ajax({
            type: "POST",
            url: "set-composition-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".set-composition-id-"+id).remove(); 

            }else{
                $(".error_to_text").text(data.error);
            }
            
        })
        .fail(function (data) {
            console.log(data);
        })
    }

</script>

<?php 

$this->registerJs(

    '
    $("#addSetComposition").on("click", function (e) {
        e.preventDefault();

        var artefact_id = $("#set-composition_artefact_id").val();
        var artefact_name = $("#set-composition_artefact_id option:selected").text();
        var set_id = '.$setsModel->id.';
        
        $(".error_from_text").text(""); 
        
        $.ajax({
            type: "POST",
            url: "set-composition-add",
            data: {"artefact_id": artefact_id, "set_id": set_id},
            cache: false,
        
        }).done(function(data) {

            //console.log(data);

            if(data.success) {
                var tr = "<tr class=\"set-composition set-composition-id-"+data.id+"\"><td></td><td><a href=\"'.Url::to(['artefact-update', 'id'=>'']).'"+artefact_id+"\">"+artefact_name+"</a></td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeSetComposition("+data.id+")\"></span></td></tr>";
                $(".sector-cons-sections-from-table").append(tr);

            }else{
                //console.log(data);
                $(".error_composition_text").html(data.error);
            }
            
        })
        .fail(function (data) {
        })
    
    })


    ',
    View::POS_READY,
    'w0'
);

?>