<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ListView;

$this->title = 'Административная часть';
$currentController = Yii::$app->controller->action->controller->module->requestedRoute;

?>
<div class="site-index">

    <div class="body-content">
    	<div class="row">
            <div class="col-lg-3 col-md-3 col-sx-3 col-sm-3 mt_3 menu-left">
                  
                    <?php 
                    
                    $pageName = "";

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
                    <div class="row mb_2 mt_3">
                        <div class="col-lg-12">
                            <?= Html::a('Создать', Url::to(['room-create']), ['class'=>'btn btn-primary']) ?>
                        </div>
                    </div>
                    <div class="row mb_3">
                        <div class="col-lg-12">
                            
                            <table class="table table-bordered rooms-table">
                              <thead class="thead-dark">
                                <tr>
                                  <th class="col-lg-1"></th>
                                  <th scope="col"><?= $roomsDataProvider->sort->link('name') ?></th>
                                  <th scope="col"><?= $roomsDataProvider->sort->link('structura') ?></th>
                                  
                                  <th scope="col"><?= $roomsDataProvider->sort->link('active') ?></th>
                                  <th scope="col">Управление</th>
                                </tr>
                              </thead>
                              <tbody>


                                <?= ListView::widget([
                                    'dataProvider' => $roomsDataProvider,
                                    'emptyText'=>'',
                                    'itemView' => '_room',
                                    'layout' => "{items}\n{pager}",
                                    // 'viewParams' => ['artefactList' => $artefactList],
                                    
                                    ])
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>    
            </div>
            
        </div>
    </div>

</div>

<?php
$this->registerJs(

    '
    function clearAlert(){
        $(".alert").remove();
    }
    
    setTimeout(clearAlert, 10000);

    $(".rooms-table .remove").on("click", function (e) {
        e.preventDefault();
        var removeBlock = $(this);
        remove_id = removeBlock.attr("item_id");
        
        if(confirm("Вы хотите удалить строку?")){
            $.ajax({
                type: "GET",
                url: "room-remove",
                data: {"id": remove_id},
                cache: false,
            
            }).done(function(data) {

                $(".rooms-table .row_"+remove_id).remove();
                
            })
            .fail(function () {
                 console.log(data);   

            })
        }
    })

    ',
    View::POS_READY,
    'w0'
);

?>