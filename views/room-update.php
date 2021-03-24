<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = 'Административная часть';
$currentController = Yii::$app->controller->action->controller->module->requestedRoute;
$pageName = "Комната - ". $roomModel->name;
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
                            <!-- $Model -->
                            <?php $form = ActiveForm::begin(['id' => 'room-update-form']); ?>

                            <?= $form->field($roomModel, 'name')->textInput() ?>
                            
                            
                            <?= $form->field($roomModel, 'image')->hiddenInput()->label('') ?>

                            
                            
                            <?php // echo $form->field($monsterModel, 'image')->fileInput(['class'=>'btn']); ?>

                            <?= $form->field($roomModel, 'about')->textarea(['rows' => '6'])  ?>

                            <?php 
                                $check = ($roomModel->active) ? 'checked' : 'unchecked';
                                echo $form->field($roomModel, 'active')->checkbox(['label' => 'Показывать', 'labelOptions' => ['style' => 'padding-left:20px;'], $check => $check]); 
                                
                                ?>
                            </div> 
                            <div class="col-lg-2 mt_4">
                                <img src="<?php echo Yii::$app->params['site_path'].$roomModel->image; ?>" width="120px" height="auto" class="artefact-image">
                            </div>
                        </div>    

                            <?php ActiveForm::end(); ?>
                            
                    </div>
                    <!--  room  connection from srtucture start -->
                    <div class="room-cons-structure-from block-update-second-info col-lg-12 mt_1 mb_3">


                        <div class="row">
                            <h4><strong>В какой структуре находится комната</strong></h4>
                        </div>
                        
                        <div class="row">
                            <table class="table table-bordered room-connection-from-table"><tbody>
                                <?php 
                                foreach($roomConnectionsFromList as $roomFrom)
                                {
                                    echo '<tr class="room-connection-from room-connection-from-id-'.$roomFrom->id.'">';
                                    $image_block = '<a href="'.Url::to(['structure-update', 'id'=>$roomFrom->structura_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$roomFrom->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';
                                    $room_name = ($roomFrom->name) ? $roomFrom->name : "---Удалено---";
                                    
                                    echo ($roomFrom->name) ? Html::a($room_name, Url::to(['structure-update', 'id'=>$roomFrom->structura_id])) : $room_name;

                                    echo '</td>';
                                    echo '<td>'.$roomType[$roomFrom->parent].'</td>';
                                    echo '<td class=""><span item_id="'.$roomFrom->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeRoomConnectionFrom('.$roomFrom->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 

                            </tbody></table>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <?php if($structuresList): ?>

                            <?php $formRoomConnectionsFrom = ActiveForm::begin(['id' => 'room_connection_from_structures_form']); ?>
                                
                                    <div class="col-lg-6"><?php echo $formRoomConnectionsFrom->field($roomsConnectionsModel, 'structura_id')->dropDownList($structuresList, ['id'=>'room_connections_from_id', 'prompt'=>'- Выбрать структуру'])->label(''); ?></div>

                                    <div class="col-lg-3"><?php echo $formRoomConnectionsFrom->field($roomsConnectionsModel, 'parent')->dropDownList($roomType, ['id'=>'parent_room_connections_from_id', 'prompt'=>'- Выбрать тип комнаты'])->label(''); ?></div>

                                    <div class="col-lg-3 ">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addRoomsConnectionsFrom", "form"=>"room_connection_from_structures_form", "class"=>"btn mt_3"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 
                            </div>
                        </div>
                        <div class="error_room_from_text color_red"></div>
                    </div>

                        
                    <!-- room  connection from srtucture end -->

                    <!--  room connections to structure start -->
                    <!-- <div class="room-cons-structure-to block-update-second-info col-lg-12 mt_1 mb_3">


                        <div class="row">
                            <h4><strong>В какую структуру можно попать из комнаты</strong></h4>
                        </div>
                        

                        <div class="row">
                            <table class="table table-bordered room-connection-to-table"><tbody>
                                <?php 
                               /* foreach($roomConnectionsToList as $roomToItem)
                                {
                                    echo '<tr class="room-connection-to room-connection-to-id-'.$roomToItem->id.'"><td>';
                                    $room_name = ($roomToItem->name) ? $roomToItem->name : "---Удалено---";
                                    
                                    echo ($roomToItem->name) ? Html::a($room_name, Url::to(['structure-update', 'id'=>$roomToItem->structura_id])) : $room_name;

                                    echo '</td>';
                                    echo '<td class=""><span item_id="'.$roomToItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeRoomConnectionTo('.$roomToItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }*/
                                ?> 

                            </tbody></table>
                        </div>


                        <div class="row">
                            <div class="col-lg-10">
                                <?php // if($structuresList): ?>

                            <?php /*$formRoomConnections = ActiveForm::begin(['id' => 'room_connection_to_structures_form']); */ ?>
                                
                                    <div class="col-lg-7"><?php /* echo $formRoomConnections->field($roomsConnectionsModel, 'structura_id')->dropDownList($structuresList, ['id'=>'room_connections_to_id', 'prompt'=>'- Выбрать структуру'])->label(''); */ ?></div>
                                    <div class="col-lg-3 ">
                                        <div class="form-group">
                                        <?php /* echo Html::button('Добавить', ["id"=>"addRoomsConnectionsTo", "form"=>"room_connection_to_structures_form", "class"=>"btn mt_3"]); */ ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                            <?php // ActiveForm::end(); ?> 
                            <?php //endif; ?> 
                            </div>
                        </div>
                        <div class="error_room_to_text color_red"></div>
                    </div>
 -->
                        
                    <!-- room connections to structure end -->
                    
                    


                     

                    <!--  room to room connections start -->
                    <div class="room-to-room-cons block-update-second-info col-lg-12 mt_1 mb_3">


                        <div class="row">
                            <h4><strong>В какую комнату можно перейти</strong></h4>
                        </div>
                        
                        <div class="row">
                            <table class="table table-bordered room-to-room-connection-table"><tbody>
                                <?php 
                                foreach($roomToRoomConnectList as $roomToRoom)
                                {
                                    echo '<tr class="room-to-room-connection room-to-room-connection-id-'.$roomToRoom->id.'">';

                                    $image_block = '<a href="'.Url::to(['room-update', 'id'=>$roomToRoom->children_room_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$roomToRoom->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';

                                    $room_name = ($roomToRoom->name) ? $roomToRoom->name : "---Удалено---";
                                    
                                    echo ($roomToRoom->name) ? Html::a($room_name, Url::to(['room-update', 'id'=>$roomToRoom->children_room_id])) : $room_name;

                                    echo '</td>';
                                    echo '<td class=""><span item_id="'.$roomToRoom->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeRoomToRoomConnection('.$roomToRoom->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?> 

                            </tbody></table>
                        </div>

                        <div class="row">
                            <div class="col-lg-10">
                                <?php if($roomsList): ?>

                            <?php $formRoomToRoomConnections = ActiveForm::begin(['id' => 'room_to_room_connection_form']); ?>
                                
                                    <div class="col-lg-7"><?php echo $formRoomToRoomConnections->field($roomToRoomConnectionModel, 'children_room_id')->dropDownList($roomsList, ['id'=>'room_to_room_connection_id', 'prompt'=>'- Выбрать структуру'])->label(''); ?></div>
                                    <div class="col-lg-3 ">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addRoomToRoomConnections", "form"=>"room_to_room_connection_form", "class"=>"btn mt_3"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 
                            </div>
                        </div>
                        <div class="error_room_room_text color_red"></div>
                    </div>


                    <!-- Monster start -->
                    <div class="monster-where-find block-update-second-info col-lg-12 mt_1 mb_3">
                        <div class="row">
                            <h4><strong>Существа которых можно встретить:</strong></h4>
                        </div>
                        <div class="row">
                            <table class="table table-bordered monster-where-find-table"><tbody>
                                <tr><td></td><td class=""><strong>Наименование</strong></td><td class="text-center time_of_day"><strong>Время появления</strong><br><span>Утро</span><span>День</span><span>Вечер</span><span>Ночь</span></td><td class="text-center"><strong>Управление</strong></td></tr>

                                <?php 
                                $time_of_day_text = "";

                                foreach($whoLiveInStructure as $monsterItem)
                                {
                                    
                                    if(isset($monsterItem->time_of_day)) {
                                        $time_of_day = str_split($monsterItem->time_of_day);
                                        $time_of_day_text = '<div class="col-lg-12">';
                                        
                                        foreach ($time_of_day as $key=>$value) {
                                            $time_of_day_text .= '<div class="col-lg-3"><span class="glyphicon ';
                                            $time_of_day_text .= ($value) ? 'glyphicon-check color_green' : 'glyphicon-ban-circle';
                                            $time_of_day_text .= '"></span></div>';    
                                        }

                                        $time_of_day_text .= '</div>';

                                    }else{
                                        $time_of_day_text = "---";
                                    }

                                    echo '<tr class="monster monster-id-'.$monsterItem->id.'">';
                                    $image_block = '<a href="'.Url::to(['monster-update', 'id'=>$monsterItem->monster_id]).'"><div class="thumb-image-in-list" style="background:url(\''.Yii::$app->params['site_path'].$monsterItem->image.'\') no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; -moz-background-size: 100% 100%;"></div></a>';
                                    echo '<td class="col-lg-1">'.$image_block.'</td>';
                                    echo '<td>';

                                    echo Html::a($monsterItem->name, Url::to(['monster-update', 'id'=>$monsterItem->monster_id]));
                                    // echo $sectorList[$monsterItem->sector_id];
                                    echo '</td>';
                                    echo '<td class="col-lg-4">'.$time_of_day_text;
                                    echo '</td>';
                                    echo '<td class="text-center col-lg-1"><span item_id="'.$monsterItem->id.'" class="remove glyphicon glyphicon-remove-circle color_red pointer" onclick="removeMonsterWF('.$monsterItem->id.')"></span></td>';
                                    echo '</tr>';    
                                }
                                ?>     
                            </tbody></table>
                        </div>
                        <div class="row">
                             <div class="col-lg-12">
                                <?php if($monsterList): ?>

                            <?php $formMWSFind = ActiveForm::begin(['id' => 'monster-where-find-room-form']); ?>
                                
                                    <div class="col-lg-4">
                                        <?php echo $formMWSFind->field($monsterWFSModel, 'monster_id')->dropDownList($monsterList, ['id'=>'monster-where-find-room-monster-id', 'prompt'=>'- Выбрать существо'])->label(''); ?>
                                            
                                    </div>
                                    <div class="col-lg-4  ml_3">
                                        <?php 
                                            $dayPartList = ["1"=>"", "2"=>"", "3"=>"", "4"=>""];
                                            echo $formMWSFind->field($monsterWFSModel, 'time_of_day', ['template' => '<div class="label_time_of_day"><span>Утро</span><span>День</span><span>Вечер</span><span>Ночь</span></div><div class="time_of_day_items">{input}</div><div class="">{error}</div>'])->checkboxList($dayPartList)->label(false);
                                        ?>
                                    </div>
                                    <div class="col-lg-2 ">
                                        <div class="form-group">
                                        <?= Html::button('Добавить', ["id"=>"addMonsterWhereFindRoom", "form"=>"monster-where-find-room-form", "class"=>"btn mt_3"]); ?>
                                            
                                        </div>
                                    </div>
                                    
                             
                            <?php ActiveForm::end(); ?> 
                            <?php endif; ?> 
                            </div>
                            <div class="error_monster_to_room_text color_red"></div>
                        </div>

                    </div>


                    <!-- Monster end -->
                        
                    <!-- room to room connections end -->
                    </div>


                    <div class="form-group">
                        <div class="col-lg-6">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', "form"=>"room-update-form", 'name' => 'room-button']) ?>
                        </div>
                        <div class="col-lg-6 text-right">
                            <?= Html::a('Отменить', 'rooms', ['class' => 'btn btn-danger', "form"=>"room-update-form", 'name' => 'room-button-cansel']) ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function removeRoomConnectionFrom(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 

        $.ajax({
            type: "POST",
            url: "rooms-connections-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".room-connection-from-id-"+id).remove(); 

            }else{

            }
            
        })
        .fail(function (data) {
            console.log(data);
        })
    }
    function removeRoomConnectionTo(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 

        $.ajax({
            type: "POST",
            url: "rooms-connections-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".room-connection-to-id-"+id).remove(); 

            }else{

            }
            
        })
        .fail(function (data) {
            console.log(data);
        })
    }
    function removeRoomToRoomConnection(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 

        $.ajax({
            type: "POST",
            url: "room-to-room-connections-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".room-to-room-connection-id-"+id).remove(); 

            }else{

            }
            
        })
        .fail(function (data) {
            console.log(data);
        })
    }

    function removeMonsterWF(id)
    {
        if(!confirm("Вы хотите удалить строку?")){
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "monster-where-find-room-remove",
            data: {"id": id},
            cache: false,
        
        }).done(function(data) {

            // console.log(data);
            if(data.success) {
                $(".monster-id-"+id).remove(); 

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
    
    $("#addMonsterWhereFindRoom").on("click", function (e) {
        e.preventDefault();

        $(".error_monster_to_room_text").text("");
        
        var monster_id = $("#monster-where-find-room-monster-id").val();
        var monster_name = $("#monster-where-find-room-monster-id option:selected").text();

        var room_id = '.$roomModel->id.';

        var time_of_day_array = $("#monsterwherefindtoroom-time_of_day input");
        var time_of_day = "";
        var time_of_day_text = "<div class=\"col-lg-12\">";

        time_of_day_array.each(function( index ) {

            time_of_day_text += "<div class=\"col-lg-3\"><span  class=\"glyphicon ";
            if($(this).prop("checked")){
                time_of_day += "1";
                time_of_day_text += "glyphicon-check color_green";
            }else{
                time_of_day += "0";
                time_of_day_text += "glyphicon-ban-circle";

            }
            time_of_day_text +="\"></span></div>";
        });
            time_of_day_text += "</div>";

        // console.log(time_of_day+" "+room_id+" "+monster_id); 
        
        $.ajax({
            type: "POST",
            url: "monster-where-find-room-add",
            data: {"structure_id": room_id, "monster_id": monster_id, "time_of_day": time_of_day},
            cache: false,
        
        }).done(function(data) {
              //  console.log("ok "+data.success);

            if(data.success) {
                var tr = "<tr class=\"monster monster-id-"+data.id+"\"><td></td><td><a href=\"'.Url::to(['monster-update', 'id'=>'']).'"+monster_id+"\">"+monster_name+"</a></td><td class=\"col-lg-4\">"+time_of_day_text+"</td><td class=\"text-center\"><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeMonsterWF("+data.id+")\"></span></td></tr>";
                $(".monster-where-find-table").append(tr);

            }else{
               // console.log("err1 "+data.error);
                $(".error_monster_to_room_text").text(data.error);
            }
            
        })
        .fail(function (data) {
              //  console.log("err2 "+data);
        })
    
    })


    // $("#addRoomsConnectionsTo").on("click", function (e) {
    //     e.preventDefault();

    //     var structura_id = $("#room_connections_to_id").val();
    //     var structure_name = $("#room_connections_to_id option:selected").text();
    //     var room_id = '.$roomModel->id.';
    //     var parent = 1;

    //     $(".error_room_to_text").text(""); 
        
    //     $.ajax({
    //         type: "POST",
    //         url: "rooms-connections-add",
    //         data: {"structura_id": structura_id, "room_id": room_id, "parent": parent},
    //         cache: false,
        
    //     }).done(function(data) {

    //         //console.log(data);

    //         if(data.success) {
    //             var tr = "<tr class=\"room-connection-to room-connection-to-id-"+data.id+"\"><td><a href=\"'/*.Url::to(['structure-update', 'id'=>''])*/.'"+structura_id+"\">"+structure_name+"</a></td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeRoomConnectionTo("+data.id+")\"></span></td></tr>";
    //             $(".room-connection-to-table").append(tr);

    //         }else{
    //             //console.log(data);
    //             $(".error_room_to_text").html(data.error);
    //         }
            
    //     })
    //     .fail(function (data) {
    //     })
    
    // })

    $("#addRoomsConnectionsFrom").on("click", function (e) {
        e.preventDefault();

        var structura_id = $("#room_connections_from_id").val();
        var structure_name = $("#room_connections_from_id option:selected").text();
        var room_id = '.$roomModel->id.';
        var parent = $("#parent_room_connections_from_id").val();
        var parent_name = $("#parent_room_connections_from_id option:selected").text();

        $(".error_room_from_text").text(""); 
        
        $.ajax({
            type: "POST",
            url: "rooms-connections-add",
            data: {"structura_id": structura_id, "room_id": room_id, "parent": parent},
            cache: false,
        
        }).done(function(data) {

            //console.log(data);

            if(data.success) {
                var tr = "<tr class=\"room-connection-from room-connection-from-id-"+data.id+"\"><td></td><td><a href=\"'.Url::to(['structure-update', 'id'=>'']).'"+structura_id+"\">"+structure_name+"</a></td><td>"+parent_name+"</td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeRoomConnectionFrom("+data.id+")\"></span></td></tr>";
                $(".room-connection-from-table").append(tr);

            }else{
                //console.log(data);
                $(".error_room_from_text").html(data.error);
            }
            
        })
        .fail(function (data) {
        })
    
    })

    $("#addRoomToRoomConnections").on("click", function (e) {
        e.preventDefault();

        var children_room_id = $("#room_to_room_connection_id").val();
        var children_room_name = $("#room_to_room_connection_id option:selected").text();
        var parent_room_id = '.$roomModel->id.';
        
        //console.log(parent_room_id);

        $(".error_room_room_text").text(""); 
        
        $.ajax({
            type: "POST",
            url: "room-to-room-connections-add",
            data: {"children_room_id": children_room_id, "parent_room_id": parent_room_id},
            cache: false,
        
        }).done(function(data) {


            if(data.success) {
                var tr = "<tr class=\"room-to-room-connection room-to-room-connection-id-"+data.id+"\"><td></td><td><a href=\"'.Url::to(['room-update', 'id'=>'']).'"+children_room_id+"\">"+children_room_name+"</a></td><td><span item_id=\""+data.id+"\" class=\"remove glyphicon glyphicon-remove-circle color_red pointer\" onclick=\"removeRoomToRoomConnection("+data.id+")\"></span></td></tr>";
                $(".room-to-room-connection-table").append(tr);

            }else{
                
                $(".error_room_room_text").html(data.error);
            }
            
        })
        .fail(function (data) {
            //console.log(data);
        })
    
    })

    ',
    View::POS_READY,
    'w0'
);

?>