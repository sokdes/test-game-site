<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = 'Административная часть';
$currentController = Yii::$app->controller->action->controller->module->requestedRoute;
$pageName = "Добавить артефакт";
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
                <div class="content"><div class="row">
                    <div class="col-lg-12">
                        <h3><?php echo $this->title ." - ".$pageName;?></h3>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-9">
                    <!-- $artefactModel -->
                    <?php $form = ActiveForm::begin(['id' => 'artefact-create-form']); ?>

                    <?= $form->field($artefactModel, 'name')->textInput() ?>
                    
                    
                    <?= $form->field($artefactModel, 'image')->hiddenInput(['class'=>'uploadImageHiddenInput'])->label(false) ?>

                    <?= $form->field($artefactModel, 'about')->textarea(['rows' => '6'])  ?>
                    
                    

                    <?= $form->field($artefactModel, 'active')->checkbox(['label' => 'Показывать', 'labelOptions' => ['style' => 'padding-left:20px;'], 'checked' => 'checked']) ?>


                    <div class="form-group">
                        <div class="col-lg-6">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'artefact-button']) ?>
                            </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 text-right">
                            <?= Html::a('Отменить', 'artefact', ['class' => 'btn btn-danger', 'name' => 'artefact-button-cansel']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                    </div>
                    <!--Image upload -->
                    <div class="col-lg-3">
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

                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($modelUploadImage, 'razdelName')->hiddenInput(['value'=>'sectors'])->label(false); ?>
                                </div>


                                <?php ActiveForm::end(); ?>
                                    
                                
                                <div id="btnUploadFiles"></div>
                                <div class="error_image_upload_text color_red"></div>
                            </div>
                            <div class="view-image-block">
                                <div class="image-header mb_2 mt_3">
                                    <strong>Изображение</strong><span class="remove-image color_red ml_2 pointer">Х</span>
                                </div>
                                <div id="viewImage" class="text-center"></div>
                                    
                            </div>
                    </div>

                    <!-- Image upload  end-->
                </div>
                <!-- row  end-->
                </div>    
            </div>
            
        </div>
    </div>

</div>

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
                    $("#viewImage").html("<img src=\"'.Yii::$app->params['site_path'].'"+data.name+"\" width=\"auto\" height=\"150px\">");
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



    ',
    View::POS_READY,
    'w0'
);

?>