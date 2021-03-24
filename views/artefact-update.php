<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = 'Административная часть';
$currentController = Yii::$app->controller->action->controller->module->requestedRoute;
$pageName = "Артефакт - ". $artefactModel->name;
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
                            <?php $form = ActiveForm::begin(['id' => 'artefact-update-form']); ?>

                            <?= $form->field($artefactModel, 'name')->textInput() ?>
                            
                            
                            <?= $form->field($artefactModel, 'image')->hiddenInput(['class'=>'uploadImageHiddenInput'])->label('') ?>

                            

                            <?= $form->field($artefactModel, 'about')->textarea(['rows' => '6'])  ?>

                            <?php 
                                $check = ($artefactModel->active) ? 'checked' : 'unchecked';
                                echo $form->field($artefactModel, 'active')->checkbox(['label' => 'Показывать', 'labelOptions' => ['style' => 'padding-left:20px;'], $check => $check]); 
                                
                                ?>
                            </div> 
                            <div class="col-lg-3 mt_4">

                                <div class="load-image-block">    
                                    <div class="mb_2">
                                        <strong>Загрузка изображений</strong>
                                    </div>
                                    
                                        <?php $form = ActiveForm::begin([
                                            'id' => 'load-image-form',
                                            'options' => ['class' => '',
                                            
                                            ]
                                        ]);
                                        ?>

                                    <div class="form-group">
                                        <?php echo $form->field($modelUploadImage, 'imageFile')->fileInput(['id'=>'inputLoadImage'])->label(false); ?>
                                        <button class="btn btn-primary btn-upload-image">Загрузить</button>

                                    </div>
                                    <div class="form-group">
                                        <?php echo $form->field($modelUploadImage, 'razdelName')->hiddenInput(['value'=>'sectors'])->label(false); ?>
                                    </div>


                                    <?php ActiveForm::end(); ?>
                                        
                                    
                                    <div id="btnUploadFiles"></div>
                                    <div class="error_image_upload_text color_red"></div>
                                </div>

                                <div class="view-image-block">
                                    <div class="image-header mb_2">
                                        <strong>Изображение</strong><span class="remove-image color_red ml_2 pointer">Х</span>
                                    </div>
                                    <div id="viewImage" class="text-left">
                                        <img src="<?php echo Yii::$app->params['site_path'].$artefactModel->image; ?>" width="100px" height="auto" class="artefact-image">
                                    </div>
                                </div>

                            </div>
                        </div>    

                            <?php ActiveForm::end(); ?>
                            
                    </div>


                    <!--Set Composition Start-->

                    <div class="set-composition block-update-second-info col-lg-12 mt_1 mb_3">
                        <div class="row">
                            <h4><strong>Сет: <?php 
                                if(isset($setCompositionInfo['set_id']) && isset($setCompositionInfo['name']))
                                    {
                                        echo Html::a($setCompositionInfo['name'], Url::to(['set-update', 'id'=>$setCompositionInfo['set_id']]));
                                    }else{
                                        echo "Не указан";
                                    } 
                                    ?></strong></h4>
                        </div>
                        <div class="row">
                            <table class="table table-bordered set-composition-table">
                                <tbody>
                                    <?php 
                                    // var_dump($setCompositionList);
                                    // die;
                                foreach($setCompositionList as $artefactItem)
                                {
                                    echo '<tr class="set-composition set-composition-id-'.$artefactItem['id'].'">';
                                    $image_block = '<a href="'.Url::to(['artefact-update', 'id'=>$artefactItem['id']]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$artefactItem["image"].'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';


                                    $artefactLink = ($artefactItem['id'] && isset($artefactItem['name']) && $artefactItem['id'] != $artefactModel['id']) ? Html::a($artefactItem['name'], Url::to(['artefact-update', 'id'=>$artefactItem['id']])) : $artefactItem['name'];
                                    
                                    echo $artefactLink;
                                    echo '</td>';

                                    //echo '<td class=""><span item_id="'.$artefactItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeArtefactChance('.$artefactItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>    
                    <!--Set Composition End-->


                    <div class="required-loot block-update-second-info col-lg-12 mt_1 mb_3">
                        <div class="row">
                            <h4><strong>Рецепт создания</strong></h4>
                        </div>
                        <div class="row">
                            <table class="table table-bordered artefact-recipe-table">
                                <tbody>
                                    <?php 
                                foreach($artefactRecipeList as $lootItem)
                                {
                                    echo '<tr class="loot loot-id-'.$lootItem->id.'">';

                                    $image_block = '<a href="'.Url::to(['loot-update', 'id'=>$lootItem->loot_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$lootItem->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';

                                    $lootLink = ($lootItem->loot_id && isset($lootList[$lootItem->loot_id])) ? Html::a($lootList[$lootItem->loot_id], Url::to(['loot-update', 'id'=>$lootItem->loot_id])) : "----";
                                    echo $lootLink;
                                    echo '</td>';


                                    echo '<td>'.$lootItem->quantity_loot.' шт.';
                                    echo '</td>';

                                    echo '<td class=""><span item_id="'.$lootItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeLoot('.$lootItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">

                                <?php if($lootList): ?>

                            <?php $formArtefactRecipe = ActiveForm::begin(['id' => 'form-artefact-recipe-form']); ?>
                                
                                <div class="row">
                                    <div class="col-lg-7"><?php echo $formArtefactRecipe->field($artefactRecipeModel, 'loot_id')->dropDownList($lootList, ['prompt'=>'- Выбрать лут'])->label(''); ?></div>
                                    <div class="col-lg-2">
                                        <?php echo $formArtefactRecipe->field($artefactRecipeModel, 'quantity_loot')->textInput(['template'=>'{input}', 'placeholder' => 'шт.'])->label(''); ?>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addArtefactRecipe", "form"=>"form-artefact-recipe-form", "class"=>"btn mt_3"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                                </div>
                                <div class="error_artefact_text color_red"></div>
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 
                            </div>
                        </div>
                    </div> 

                    <!--Artefact-Chance-Get Start-->
                    <div class="artefact-chance-get block-update-second-info col-lg-12 mt_1 mb_3">
                        <div class="row">
                            <h4><strong>Шанс получения при прохождении</strong></h4>
                        </div>
                        <div class="row">
                            <table class="table table-bordered artefact-chance-get-table">
                                <tbody>
                                    <?php 
                                foreach($artefactChanceGet as $artefactItem)
                                {
                                    echo '<tr class="artefact-chance artefact-chance-id-'.$artefactItem->id.'">';
                                    $image_block = '<a href="'.Url::to(['sector-update', 'id'=>$artefactItem->sector_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$artefactItem->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';
                                    $artefactLink = ($artefactItem->artefact_id && isset($artefactItem->name)) ? Html::a($artefactItem->name, Url::to(['sector-update', 'id'=>$artefactItem->sector_id])) : "----";
                                    echo $artefactLink;
                                    echo '</td>';


                                    echo '<td>'.$artefactItem->chance_get.' %';
                                    echo '</td>';

                                    echo '<td class=""><span item_id="'.$artefactItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeArtefactChance('.$artefactItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12"> 

                                   <?php if($sectorList): ?>

                            <?php $formArtefactChanceGet = ActiveForm::begin(['id' => 'artefact-chance-get-form']); ?>
                                
                                <div class="row">
                                    <div class="col-lg-7"><?php echo $formArtefactChanceGet->field($artefactChanceGetModel, 'sector_id')->dropDownList($sectorList, ['prompt'=>'- Выбрать сектор', 'id'=>'artefactchanceget-sector_id'])->label(false); ?></div>
                                    <div class="col-lg-2">
                                        <?php echo $formArtefactChanceGet->field($artefactChanceGetModel, 'chance_get')->textInput(['template'=>'{input}', 'placeholder' => '%', 'id'=>'artefactchanceget-chance_get'])->label(false); ?>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addArtefactChance", "form"=>"artefact-chance-get-form", "class"=>"btn"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                                </div>
                                <div class="error_artefact_chance_text color_red"></div>
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 

                            </div>
                        </div>
                    </div>    

                    <!--Artefact-Chance-Get Ends-->




                        <div class="form-group">
                            <div class="col-lg-6">
                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', "form"=>"artefact-update-form", 'name' => 'artefact-button']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 text-right">
                                <?= Html::a('Отменить', 'artefact', ['class' => 'btn btn-danger', "form"=>"artefact-update-form", 'name' => 'artefact-button-cansel']) ?>
                            </div>
                        </div>

                        
                    </div>    
            </div>
            
        </div>
    </div>

</div>


<script type="text/javascript">
    function removeArtefactChance(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "artefact-chance-get-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".artefact-chance-id-"+id).remove(); 

            }else{

            }
            
        })
        .fail(function (data) {
            console.log(data);
        })   
    }

    function removeLoot(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "artefact-recipe-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".loot-id-"+id).remove(); 

            }else{

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
    
    $(".remove-image").on("click", function(e){

        
        
        if(!confirm("Вы хотите удалить изображение?")){
            return false;
        } 

        $(".load-image-block").show();
        $(".view-image-block").hide();
        $("#viewImage").html("");
        $(".uploadImageHiddenInput").val("");
        $("#inputLoadImage").val("");
        
    });

    $("#inputLoadImage").on("change", function (e) {
        
        var form = $("#load-image-form");
        var formdata = new FormData(form[0]);

        
        $(".error_image_upload_text").text("");

        $.ajax({
            type: "POST",
            url: "'.Yii::$app->params['site_path'].'/web/admin/upload-image/upload",
            data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#btnUploadFiles img").hide();
                $("#btnUploadFiles").append("<div class=\"progress margin-bottom-0\"><div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\"></div>");
            },
            complete: function() {
                $("div.progress").remove();
                $("#btnUploadFiles img").show();
            },
            success: function(data) {
                
                if(data.success){
                    $(".load-image-block").hide();
                    $("#viewImage").html("<img src=\"'.Yii::$app->params['site_path'].'"+data.name+"\" width=\"100px\" height=\"auto\">");
                    $(".uploadImageHiddenInput").val(data.name);
                    $(".load-image-block").hide();
                    $(".view-image-block").show();

                }else{
                    $(".error_image_upload_text").text(data.error);
                }
            },
            error: function(data) {
                $(".error_image_upload_text").text(data.error);
            }

        });
    })


    $("#addArtefactChance").on("click", function (e) {
        e.preventDefault();

        $(".error_artefact_chance_text").text("");

        var sector_id = $("#artefactchanceget-sector_id").val();
        var sector_name = $("#artefactchanceget-sector_id option:selected").text();
        var chance_get = $("#artefactchanceget-chance_get").val();
        var artefact_id = '.$artefactModel->id.';
         
        
        $.ajax({
            type: "POST",
            url: "artefact-chance-get-add",
            data: {"sector_id": sector_id, "chance_get": chance_get, "artefact_id": artefact_id},
            cache: false,
        
        }).done(function(data) {
            if(data.success) {
            var tr = "<tr class=\"artefact-chance artefact-chance-id-"+data.id+"\"><td><a href=\"'.Url::to(['sector-update', 'id'=>'']).'"+sector_id+"\">"+sector_name+"</a></td><td>"+chance_get+" %</td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeArtefactChance("+data.id+")\"></span></td></tr>";
                $(".artefact-chance-get-table").append(tr);
                }else{
                    $(".error_artefact_chance_text").text(data.error);
                }
        })
        .fail(function (data) {
            
        })
    
    })


    
    $("#addArtefactRecipe").on("click", function (e) {
        e.preventDefault();

        $(".error_artefact_text").text("");

        var loot_id = $("#artefactrecipe-loot_id").val();
        var loot_name = $("#artefactrecipe-loot_id option:selected").text();
        var quantity_loot = $("#artefactrecipe-quantity_loot").val();
        var artefact_id = '.$artefactModel->id.';
         
        
        $.ajax({
            type: "POST",
            url: "artefact-recipe-add",
            data: {"loot_id": loot_id, "quantity_loot": quantity_loot, "artefact_id": artefact_id},
            cache: false,
        
        }).done(function(data) {

            //console.log(data);

            if(data.success) {
                var tr = "<tr class=\"loot loot-id-"+data.id+"\"><td><a href=\"'.Url::to(['loot-update', 'id'=>'']).'"+loot_id+"\">"+loot_name+"</a></td><td>"+quantity_loot+" шт.</td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeLoot("+data.id+")\"></span></td></tr>";
                $(".artefact-recipe-table").append(tr);

            }else{
                //console.log(data);
                $(".error_artefact_text").text(data.error);
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