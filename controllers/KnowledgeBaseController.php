<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

use yii\helpers\ArrayHelper;

use yii\filters\VerbFilter;
use app\models\admin\UploadFiles;
use app\models\admin\AdminMenu;
use app\models\admin\Sector;
use app\models\admin\SectorConnections;
use app\models\admin\Monster;
use app\models\admin\MonsterRang;
use app\models\admin\MonsterWhereFind;
use app\models\admin\MonsterWhereFindToRoom;
use app\models\admin\MonsterClass;
use app\models\admin\Loot;
use app\models\admin\Sets;
use app\models\admin\LootWhitchGet;
use app\models\admin\Artefact;
use app\models\admin\ArtefactRecipe;
use app\models\admin\ArtefactChanceGet;
use app\models\admin\SetComposition;
use app\models\admin\Structures;
use app\models\admin\StructuresConnections;
use app\models\admin\StructuresType;
use app\models\admin\Effects;
use app\models\admin\EffectsType;
use app\models\admin\EffectsInfluence;
use app\models\admin\EffectToEffect;
use app\models\admin\EffectOfSkill;
use app\models\admin\Figures;
use app\models\admin\FigureFormation;
use app\models\admin\FigureOptionsTitle;
use app\models\admin\FigureOptionsValue;
use app\models\admin\MonsterOptionsValue;
use app\models\admin\Rooms;
use app\models\admin\RoomsConnections;
use app\models\admin\RoomToRoomConnections;
use app\models\admin\Skills;
use app\models\admin\SkillsOfFigure;
use app\models\admin\SkillsOfMonster;
use app\models\admin\Formations;
use app\models\admin\Terms;
use app\models\admin\FormationEffect;
// use app\models\Pagination;






class KnowledgeBaseController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['logout'],
                'denyCallback' => function ($rule, $action) {
                    if(1) {
                        return $this->redirect(['site/login']); 
                    } else {
                        throw new \yii\web\ForbiddenHttpException('Даная страница закрыта для доступа'); 
                    }
                },
                'rules' => [
                    
                    [
                        'allow'=>true,
                        'actions'=>['index', 'error'],
                        'roles'=>['?'],
                    ],
                    
                    [
                        'actions' => ['index', 'test', 
                        'sector', 'sector-update', 'sector-create', 'sector-remove', 
                        'sector-connections-add', 'sector-connections-remove',
                        'monster', 'monster-update', 'monster-create', 'monster-remove',
                        'monster-class', 'monster-class-create', 'monster-class-update', 'monster-class-remove', 
                        'loot', 'loot-update', 'loot-create', 'loot-remove',
                        'artefact','artefact-update', 'artefact-create', 'artefact-remove',
                        'monster-where-find-add', 'monster-where-find-remove','monster-where-find-structure-add', 'monster-where-find-structure-remove','monster-where-find-room-add', 'monster-where-find-room-remove',
                        'loot-whitch-get-add', 'loot-whitch-get-remove',
                        'artefact-recipe-add', 'artefact-recipe-remove',
                        'artefact-chance-get-add', 'artefact-chance-get-remove', 'sets', 'set-create', 'set-update', 'set-remove', 'set-composition-add', 'set-composition-remove',
                        'structures', 'structure-create', 'structure-update', 'structure-remove', 'structure-connection-add', 'structure-connection-remove', 'effects', 'effect-create', 'effect-update', 'effect-remove', 'effect-to-effect-add', 'effect-to-effect-remove', 'get-effects-list', 'effect-of-skill-add', 'effect-of-skill-remove', 'figures', 'figure-create', 'figure-update','figure-remove', 'rooms', 'room-create', 'room-update', 'room-remove', 'rooms-connections-add','rooms-connections-remove', 'room-to-room-connections-add', 'room-to-room-connections-remove', 
                        'skills', 'skill-create', 'skill-update', 'skill-remove', 'skill-of-figure-add', 'skill-of-figure-remove', 'skill-of-monster-add', 'skill-of-monster-remove',
                        'terms', 'term-create', 'term-update', 'term-remove', 
                        'formations', 'formation-create', 'formation-update', 'formation-remove',
                        'formation-effect-add', 'formation-effect-remove', 
                        'error'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->getIdentity()->getRole() == (int)1);
                        }
                    ],


                    [   
                        'allow'=>false,
                        'roles'=>['?'],  
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                
            ],
        ];
    }

    public function actionError()
    {
       return $this->goHome();
        
    }

    public function actionTest()
    {
        // echo "<pre>";
        // var_dump();
    }

    public function actionIndex()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
    	return $this->render('index', ['adminMenu'=>$adminMenu]);
    }

    public function actionSector()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $sectorModel = new Sector();



        $sectorsDataProvider = $sectorModel->search(Yii::$app->request->queryParams);
        
        

        return $this->render('sector', ['adminMenu'=>$adminMenu, 'sectorsDataProvider'=>$sectorsDataProvider, 'sectorModel'=>$sectorModel]);
    }

    public function actionSectorCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $sectorModel = new Sector();
        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->all(),'id','name');

        $modelUploadImage = new UploadFiles();

        //Загружаем данные из формы
        if ($sectorModel->load(Yii::$app->request->post())) {

                
                // Сохраняем данные в БД
                if($sectorModel->validate()){
                    $sectorModel->save();
                    $sectorId = $sectorModel->id;
                    
                    Yii::$app->session->setFlash('success', "Данные сектора сохранены.");
                    return $this->redirect(['sector-update', 'id'=>$sectorId]);

                }else{

                    Yii::$app->session->setFlash('error', "Данные не сохранены.");
                }
            
        }

        return $this->render('sector-create', ['adminMenu'=>$adminMenu, 'sectorModel'=>$sectorModel, 'sectorList'=>$sectorList, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionSectorUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        $sectorModel = Sector::findOne($id);
        
        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->all(),'id','name');
        
        $sectorConnectionsModel = new SectorConnections();
        
        $sectorConnectionsFromList = SectorConnections::find()
            ->select('knb_sector.image, knb_sector_connections.id, knb_sector_connections.sector_children_id, knb_sector_connections.sector_parent_id')
            ->leftJoin('knb_sector', 'knb_sector.id = knb_sector_connections.sector_parent_id')
            ->where('sector_children_id=:id', [':id'=>$id])
            ->all();

        $sectorConnectionsToList = SectorConnections::find()
            ->select('knb_sector.image, knb_sector_connections.id, knb_sector_connections.sector_children_id, knb_sector_connections.sector_parent_id')
            ->leftJoin('knb_sector', 'knb_sector.id = knb_sector_connections.sector_children_id')
            ->where('sector_parent_id=:id', [':id'=>$id])
            ->all();

        $monsterWFList = MonsterWhereFind::find()
        ->select('knb_monster_where_find.id, knb_monster_where_find.sector_id, knb_monster_where_find.monster_id, knb_monster.image, knb_monster.name, knb_monster_where_find.time_of_day')
        ->where('sector_id=:id', [":id"=>$id])
        ->orderBy('knb_monster.name asc')
        ->leftJoin('knb_monster', 'knb_monster.id = knb_monster_where_find.monster_id')
        ->all();

        
        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(),'id','name');;

        $monsterWFModel = new MonsterWhereFind();
        $dayPartList = $monsterWFModel->dayPartList();

        if($sectorModel->load(Yii::$app->request->post())){
            //$sectorModel->parent = ($sectorModel->parent=='') ? 0 : $sectorModel->parent;
            if($sectorModel->validate()){
                $sectorModel->save();

                Yii::$app->session->setFlash('success', "Данные сектора сохранены.");
                return $this->redirect(['sector']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }

            
        }

        return $this->render('sector-update', ['adminMenu'=>$adminMenu, 'sectorModel'=>$sectorModel, 'sectorList'=>$sectorList, 'sectorConnectionsModel'=>$sectorConnectionsModel, 'sectorConnectionsFromList'=>$sectorConnectionsFromList, 'sectorConnectionsToList'=>$sectorConnectionsToList, 
            'monsterWFList'=>$monsterWFList, 'monsterWFModel'=>$monsterWFModel, 'dayPartList'=>$dayPartList,
            'monsterList'=>$monsterList
        ]);
        
    }

    public function actionSectorRemove($id)
    {
        $sectorModel = Sector::findOne($id);


        if($sectorModel && $sectorModel->delete() )
        {

            return 1;

        }else{

            return 0;

        }
    }

    public function actionSectorConnectionsAdd()
    {

        $request = \Yii::$app->getRequest();
        $modelSConn = new SectorConnections();

        if($request->isPost && $modelSConn->load(['SectorConnections'=>$request->post()]) && $modelSConn->validate() ){
            $modelSConn->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelSConn->id];
        }else{
            $error_text = "";
            foreach ($modelSConn->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }

    }
    
    public function actionSectorConnectionsRemove()
    {

        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelSConn = SectorConnections::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelSConn){
                $modelSConn->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSConn->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Сектор не найден'];
            }
        }
    }

    // ==========================================================

    public function actionMonster()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $monsterModel = new Monster();
        $sectorItems = Sector::find()->orderBy('name asc')->all();
        $sectorList = ArrayHelper::map($sectorItems,'id','name');

        // $a = Monster::find()
        //     ->select('knb_monster_rangs.name as rang_name, knb_formations.name as formation_name, knb_monster.id, knb_monster.name, knb_monster.about, knb_monster.image, knb_monster.formation_id, knb_monster.rang_id, knb_monster.active')
        //     ->leftJoin('knb_monster_rangs', 'knb_monster_rangs.id = knb_monster.rang_id')
        //     ->leftJoin('knb_formations', 'knb_formations.id = knb_monster.formation_id')    
        //     // ->leftJoin('knb_formation_effect', 'knb_formation_effect.id = knb_monster.formation_id')
        //     // ->leftJoin('knb_effects', 'knb_effects.id = knb_formation_effect.effect_id')
        //     ->asArray()
        //     ->all();
        //     echo "<pre>";
        //     var_dump($a);
        //     die;


        $monstersDataProvider = $monsterModel->search(Yii::$app->request->queryParams);

        
        return $this->render('monster', ['adminMenu'=>$adminMenu, 'monstersDataProvider'=>$monstersDataProvider, 'monsterModel'=>$monsterModel, 'sectorList'=>$sectorList]);
    }

    public function actionMonsterCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $monsterModel = new Monster();
        $modelUploadImage = new UploadFiles();

        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->all(),'id','name');
        $rangList = ArrayHelper::map(MonsterRang::find()->orderBy('name asc')->all(), 'id', 'name');
        $monsterClassList = ArrayHelper::map(MonsterClass::find()->orderBy('name asc')->all(),'id','name');
        
        $monsterFormation = ArrayHelper::map(Formations::find()->all(), 'id', 'name');
        $monsterOptionsValueModel = new MonsterOptionsValue();
        $figureOptionsTitle = FigureOptionsTitle::find()->all();
        
        //Загружаем данные из формы
        if ($monsterModel->load(Yii::$app->request->post())) {

                
                // Сохраняем данные в БД
                if($monsterModel->validate()){
                    $monsterModel->save();
                    $monster_id = $monsterModel->id;

                    Yii::$app->session->setFlash('success', "Данные существа сохранены.");
                    return $this->redirect(['monster-update', 'id'=>$monster_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }
            
        }

        return $this->render('monster-create', ['adminMenu'=>$adminMenu, 'monsterModel'=>$monsterModel, 'sectorList'=>$sectorList, 'rangList'=>$rangList, 'modelUploadImage'=>$modelUploadImage, 'monsterFormation'=>$monsterFormation, 'monsterOptionsValueModel'=>$monsterOptionsValueModel, 'figureOptionsTitle'=>$figureOptionsTitle , 'monsterClassList'=>$monsterClassList]);
    }

    public function actionMonsterUpdate($id)
    {
        $request = \Yii::$app->getRequest();

        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $monsterModel = Monster::findOne($id);
        
        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->all(),'id','name');
        $roomsList = ArrayHelper::map(Rooms::find()->orderBy('name asc')->all(),'id','name');
        $lootList = ArrayHelper::map(Loot::find()->orderBy('name asc')->all(),'id','name');
        $rangList = ArrayHelper::map(MonsterRang::find()->orderBy('name asc')->all(), 'id', 'name');
        $structureList = ArrayHelper::map(Structures::find()->orderBy('name asc')->all(), 'id', 'name');
        
        $skillList = ArrayHelper::map(Skills::find()->orderBy('name asc')->all(), 'id', 'name');

        $monsterFormation = ArrayHelper::map(Formations::find()->all(), 'id', 'name');

        $monsterClassList = ArrayHelper::map(MonsterClass::find()->orderBy('name asc')->all(),'id','name');

        $skillOfMonsterList = SkillsOfMonster::find()
            ->select('knb_skills.image, knb_skills_of_monster.id,  knb_skills_of_monster.skill_id')
            ->leftJoin('knb_skills', 'knb_skills.id = knb_skills_of_monster.skill_id')
            ->where('knb_skills_of_monster.figure_id = :figure_id', [':figure_id'=>$id])
            ->all();

        $structureListRows = Structures::find()
        ->select('knb_structures.id, knb_structures.name, knb_structures_type.name as type_name')
        ->leftJoin('knb_structures_type', 'knb_structures_type.id = knb_structures.type_id')
        ->asArray()->all();
        
        $structureListFullInfo = [];

        foreach($structureListRows as $structRow){

            $structureListFullInfo[$structRow["id"]] = ["name"=>$structRow["name"], "type_name"=>$structRow["type_name"]];

        }
        
        $monsterWFRModel = new MonsterWhereFindToRoom();
        $monsterWFRList = MonsterWhereFindToRoom::find()
            ->select('knb_monster_where_find_room.id, knb_monster_where_find_room.structure_id, knb_monster_where_find_room.monster_id, knb_monster_where_find_room.time_of_day, knb_rooms.image')
            ->leftJoin('knb_rooms', 'knb_rooms.id = knb_monster_where_find_room.structure_id')
            ->where('monster_id=:id', [':id'=>$id])
            ->asArray()
            ->all();



        $monsterWFModel = new MonsterWhereFind();
        
        $monsterWhereList = $monsterWFRList;
        
        $lootWhitchGetList = LootWhitchGet::find()
        ->select('knb_loot_whitch_get.id, knb_loot_whitch_get.loot_id, knb_loot_whitch_get.monster_id, knb_loot_whitch_get.chance_get, knb_loot_whitch_get.enlarged_chance_get, knb_loot.name, knb_loot.image')
        ->where('monster_id=:id', [':id'=>$id])
        ->leftJoin('knb_loot', 'knb_loot.id = knb_loot_whitch_get.loot_id')
        ->all();

        
        $lootWhitchGetModel = new LootWhitchGet();


        $monsterOptionsValueModel = new MonsterOptionsValue();
        $figureOptionsTitle = FigureOptionsTitle::find()->all();
        $figureOptionsValue = ArrayHelper::map(MonsterOptionsValue::find()->where('figure_id=:id', [':id'=>$id])->all(), 'option_id', 'value');

        
        if($request->isAjax && $request->isPost && $monsterModel->load(['Monster'=>Yii::$app->request->post()])){
            
            if($monsterModel->validate()){
                $monsterModel->save();

                // перебираем значения переменных option
                foreach($request->post() as $key=>$option_value)
                {
                    $error_text = "";
                    // Проверяем есть ли в параметрах не цифровые значения
                    if(preg_match("|option_id_|", $key) && preg_match("|\D|", $option_value)){
                        
                        $error_text = "Ошибка! В поле параметр должны быть только цыфры";
                        \Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['error' => $error_text." ".$key." ".$option_value];

                    }       

                    if($option_value != ""){

                             
                        //плучаем id и значения
                        if(preg_match("|option_id_|", $key)){
                            
                            $option_id =  str_replace("option_id_", "", $key);


                            $figureOption = MonsterOptionsValue::find()->where('figure_id = :figure_id and option_id = :option_id', [':option_id'=>$option_id, ':figure_id'=>$monsterModel->id])->one();
                            
                            if(!$figureOption){
                                
                                $figureOption = new MonsterOptionsValue();
                                $figureOption->figure_id = $monsterModel->id;
                                $figureOption->option_id = $option_id;

                            }
                            
                            $figureOption->value = $option_value;
                            
                            
                            if($figureOption->validate())
                            {

                                $figureOption->save();
                                
                                

                            }else{
                                
                                $error_text = "";
                                foreach ($monsterModel->getErrors() as $key => $value) {
                                    $error_text .= $value[0]."; ";
                                }

                                \Yii::$app->response->format = Response::FORMAT_JSON;
                                return ['error' => $error_text];
                                //die;
                            }

                        }
                    }
                }

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "Данные существа сохранены."];
                // Yii::$app->session->setFlash('success', "Данные сектора сохранены.");
                // return $this->redirect(['monster']);
            }else{

                $error_text = "";
                foreach ($monsterModel->getErrors() as $key => $value) {
                    $error_text .= $value[0]."; ";
                }

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => $error_text];
            }

            
        }

        return $this->render('monster-update', ['adminMenu'=>$adminMenu, 'monsterModel'=>$monsterModel, 'sectorList'=>$sectorList, 'roomsList'=>$roomsList, 'monsterWhereList'=>$monsterWhereList, 'monsterWFModel'=>$monsterWFModel, 'lootWhitchGetList'=>$lootWhitchGetList, 'lootWhitchGetModel'=>$lootWhitchGetModel, 'lootList'=>$lootList, 'structureList'=>$structureList, 'monsterWFRModel'=>$monsterWFRModel, 'structureListFullInfo'=>$structureListFullInfo, 'rangList'=>$rangList, 'skillOfMonsterList'=>$skillOfMonsterList, 'skillList'=>$skillList, 'figureOptionsTitle'=>$figureOptionsTitle, 'monsterOptionsValueModel'=>$monsterOptionsValueModel, 'figureOptionsValue'=>$figureOptionsValue, 'monsterFormation'=>$monsterFormation, 'monsterClassList'=>$monsterClassList]);
        
    }

    public function actionMonsterRemove($id)
    {
        $monsterModel = Monster::findOne($id);

        if($monsterModel && $monsterModel->delete())
        {
            MonsterWhereFind::deleteAll(['monster_id'=>$id]);
            return 1;

        }else{

            return 0;

        }
    }



    public function actionMonsterWhereFindAdd()
    {

        $request = \Yii::$app->getRequest();
        $modelMWF = new MonsterWhereFind();

        if($request->isPost && $modelMWF->load(['MonsterWhereFind'=>$request->post()]) && $modelMWF->validate() ){
            $modelMWF->save();

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelMWF->id, 'place_name'=>'Сектор'];
        }else{
            $error_text = "";
            foreach ($modelMWF->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }

    }

    public function actionMonsterWhereFindStructureAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelMWFS = new MonsterWhereFindToRoom();
        if($request->isPost && $modelMWFS->load(['MonsterWhereFindToRoom'=>$request->post()]) && $modelMWFS->validate() ){
            $modelMWFS->save();
            $placeName = Structures::find()->select('knb_structures_type.name as type_name')->leftJoin('knb_structures_type', 'knb_structures_type.id=knb_structures.type_id')->where('knb_structures.id=:id', [':id'=>$modelMWFS->structure_id])->one();
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelMWFS->id,'place_name'=>$placeName["type_name"]];
        }else{
            $error_text = "";
            foreach ($modelMWFS->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }
    }

    public function actionMonsterWhereFindRemove()
    {

        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelMWF = MonsterWhereFind::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelMWF){
                $modelMWF->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelMWF->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Сектор не найден'];
            }
        }
    }

    public function actionMonsterWhereFindStructureRemove()
    {

        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelMWFS = MonsterWhereFindToRoom::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelMWFS){
                $modelMWFS->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelMWFS->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Сектор не найден'];
            }
        }
    }

    public function actionMonsterWhereFindRoomAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelMWFS = new MonsterWhereFindToRoom();
        if($request->isPost && $modelMWFS->load(['MonsterWhereFindToRoom'=>$request->post()]) && $modelMWFS->validate() ){
            $modelMWFS->save();
            $placeName = "";
            // $placeName = Rooms::find()->select('knb_structures_type.name as type_name')->leftJoin('knb_structures_type', 'knb_structures_type.id=knb_structures.type_id')->where('knb_structures.id=:id', [':id'=>$modelMWFS->structure_id])->one();
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelMWFS->id,'place_name'=>$placeName];
        }else{
            $error_text = "";
            foreach ($modelMWFS->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }
    }

    public function actionMonsterWhereFindRoomRemove()
    {

        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelMWFS = MonsterWhereFindToRoom::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelMWFS){
                $modelMWFS->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelMWFS->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Сектор не найден'];
            }
        }
    }


    public function actionMonsterClass()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $monsterClassModel = new MonsterClass();
        
        
        $monsterClassDataProvider = $monsterClassModel->search(Yii::$app->request->queryParams);

        
        return $this->render('monster-class', ['adminMenu'=>$adminMenu, 'monsterClassDataProvider'=>$monsterClassDataProvider, 'monsterClassModel'=>$monsterClassModel]);
    }

    public function actionMonsterClassCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $monsterClassModel = new MonsterClass();
        $modelUploadImage = new UploadFiles();

        //Загружаем данные из формы
        if ($monsterClassModel->load(Yii::$app->request->post())) {
             
                // Сохраняем данные в БД
                if($monsterClassModel->validate()){
                    $monsterClassModel->save();
                    $monsterClass_id = $monsterClassModel->id;

                    Yii::$app->session->setFlash('success', "Данные класса сохранены.");
                    return $this->redirect(['monster-class-update', 'id'=>$monsterClass_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

            

        }

        return $this->render('monster-class-create', ['adminMenu'=>$adminMenu, 'monsterClassModel'=>$monsterClassModel, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionMonsterClassUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $monsterClassModel = MonsterClass::findOne($id);
        

        if($monsterClassModel->load(Yii::$app->request->post())){
            if($monsterClassModel->validate()){
                $monsterClassModel->save();

                Yii::$app->session->setFlash('success', "Данные класса сохранены.");
                return $this->redirect(['monster-class']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }
        }



        return $this->render('monster-class-update', ['adminMenu'=>$adminMenu, 'monsterClassModel'=>$monsterClassModel]);
    }
    
    public function actionMonsterClassRemove($id)
    {   
        $monsterClassModel = MonsterClass::findOne($id);

        if($monsterClassModel && $monsterClassModel->delete())
        {
            // MonsterWhereFind::deleteAll(['monster_id'=>$id]);
            return 1;

        }else{

            return 0;

        }
    }

    


    //==============================================================
    
    public function actionLoot()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $lootModel = new Loot();
        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(),'id','name');

        $lootDataProvider = $lootModel->search(Yii::$app->request->queryParams);

        
        return $this->render('loot', ['adminMenu'=>$adminMenu, 'lootDataProvider'=>$lootDataProvider, 'lootModel'=>$lootModel, 'monsterList'=>$monsterList]);
    }

    public function actionLootCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $lootModel = new Loot();
        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(),'id','name');
        $modelUploadImage = new UploadFiles();

        //Загружаем данные из формы
        if ($lootModel->load(Yii::$app->request->post())) {

            
                 
                // Сохраняем данные в БД
                if($lootModel->validate()){
                    $lootModel->save();
                    $loot_id = $lootModel->id;

                    Yii::$app->session->setFlash('success', "Данные лута сохранены.");
                    return $this->redirect(['loot-update', 'id'=>$loot_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }
            
        }

        return $this->render('loot-create', ['adminMenu'=>$adminMenu, 'lootModel'=>$lootModel, 'monsterList'=>$monsterList, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionLootUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        $lootModel = Loot::findOne($id);
        
        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(),'id','name');

        $lootWhitchGetModel = new LootWhitchGet();
        $lootWhitchGetList = LootWhitchGet::find()
            ->select('knb_monster.image, knb_loot_whitch_get.loot_id, knb_loot_whitch_get.monster_id, knb_loot_whitch_get.chance_get, knb_loot_whitch_get.enlarged_chance_get')
            ->leftJoin('knb_monster', 'knb_monster.id = knb_loot_whitch_get.monster_id')
            ->where('knb_loot_whitch_get.loot_id=:id', [':id'=>$id])
            ->all();

        $artefactList = ArrayHelper::map(Artefact::find()->orderBy('name asc')->all(),'id','name');
        
        $artefactRecipeModel = new ArtefactRecipe();

        $artefactRecipeList = ArtefactRecipe::find()
        ->select('knb_artefact_recipe.id, knb_artefact_recipe.artefact_id, knb_artefact_recipe.loot_id, knb_artefact_recipe.quantity_loot, knb_artefact.name, knb_artefact.image')
        ->where('knb_artefact_recipe.loot_id = :id', [':id'=>$id])
        ->leftJoin('knb_artefact', 'knb_artefact.id=knb_artefact_recipe.artefact_id')
        ->all();

        if($lootModel->load(Yii::$app->request->post())){
            
            if($lootModel->validate()){
                $lootModel->save();

                Yii::$app->session->setFlash('success', "Данные сектора сохранены.");
                return $this->redirect(['loot']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }

            
        }

        return $this->render('loot-update', ['adminMenu'=>$adminMenu, 'lootModel'=>$lootModel, 'monsterList'=>$monsterList, 'lootWhitchGetList'=>$lootWhitchGetList, 'lootWhitchGetModel'=>$lootWhitchGetModel, 'artefactList'=>$artefactList, 'artefactRecipeModel'=>$artefactRecipeModel, 'artefactRecipeList'=>$artefactRecipeList]);
        
    }

    public function actionLootWhitchGetAdd()
    {

        $request = \Yii::$app->getRequest();
        $modelWGet = new LootWhitchGet();

        if($request->isPost && $modelWGet->load(['LootWhitchGet'=>$request->post()]) && $modelWGet->validate() ){
            $modelWGet->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelWGet->id];
        }else{
            $error_text = "";
            foreach ($modelWGet->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }

    }

    public function actionLootWhitchGetRemove()
    {

        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelWGet = LootWhitchGet::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelWGet){
                $modelWGet->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelWGet->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Сектор не найден'];
            }
        }
    }

    public function actionLootRemove($id)
    {
        $lootModel = Loot::findOne($id);

        if($lootModel && $lootModel->delete())
        {   
            LootWhitchGet::deleteAll(['loot_id'=>$id]);
            return 1;

        }else{

            return 0;

        }
    }

    //==============================================================

    public function actionArtefact()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $artefactModel = new Artefact();
        
        // $artefactList = ArrayHelper::map(Artefact::find()->all(),'id','name');


        $artefactDataProvider = $artefactModel->search(Yii::$app->request->queryParams);

        
        return $this->render('artefact', ['adminMenu'=>$adminMenu, 'artefactDataProvider'=>$artefactDataProvider, 'artefactModel'=>$artefactModel]);
    }

    public function actionArtefactCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $artefactModel = new Artefact();
        
        $modelUploadImage = new UploadFiles();
        
        //Загружаем данные из формы
        if ($artefactModel->load(Yii::$app->request->post())) {

            
                
                // Сохраняем данные в БД
                if($artefactModel->validate()){
                    $artefactModel->save();
                    $artefact_id = $artefactModel->id;

                    Yii::$app->session->setFlash('success', "Данные лута сохранены.");
                    return $this->redirect(['artefact-update', 'id'=>$artefact_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }
            
        }

        return $this->render('artefact-create', ['adminMenu'=>$adminMenu, 'artefactModel'=>$artefactModel, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionArtefactUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $modelUploadImage = new UploadFiles();

        $artefactModel = Artefact::findOne($id);
        
        $lootList = ArrayHelper::map(Loot::find()->orderBy('name asc')->all(),'id','name');
        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->all(),'id','name');

        $artefactRecipeModel = new ArtefactRecipe();

        $artefactRecipeList = ArtefactRecipe::find()
            ->select('knb_loot.image, knb_artefact_recipe.id, knb_artefact_recipe.artefact_id, knb_artefact_recipe.loot_id, knb_artefact_recipe.quantity_loot')
            ->leftJoin('knb_loot', 'knb_loot.id = knb_artefact_recipe.loot_id')
            ->where('knb_artefact_recipe.artefact_id=:id', [':id'=>$id])
            ->all();
        // echo "<pre>";    
        // var_dump($artefactRecipeList);
        // die;    
            
        $artefactChanceGetModel = new ArtefactChanceGet();

        $artefactChanceGet = ArtefactChanceGet::find()
        ->select('knb_sector.image, knb_artefact_chance_get.id, knb_artefact_chance_get.artefact_id, knb_artefact_chance_get.sector_id, knb_artefact_chance_get.chance_get, knb_sector.name')
        ->leftJoin('knb_sector', 'knb_artefact_chance_get.sector_id = knb_sector.id')
        ->where('artefact_id=:id', [':id'=>$id])
        ->all();

        // $setComposition_id = SetComposition::find()->where("artefact_id=:artefact_id", [':artefact_id'=>$id])->one();

        $setCompositionInfo = (new \yii\db\Query())
            ->select('knb_sets.image, knb_sets_composition.set_id, knb_sets.name')
            ->from('knb_sets_composition') 
            ->where('artefact_id=:artefact_id', [':artefact_id'=>$id])
            ->leftJoin('knb_sets', 'knb_sets_composition.set_id=knb_sets.id')
            ->one();
        
        $set_id = (isset($setCompositionInfo['set_id']) && $setCompositionInfo['set_id']) ? $setCompositionInfo['set_id'] : 0;

        $setCompositionList = (new \yii\db\Query())
            ->select('knb_artefact.image, knb_artefact.id, knb_artefact.name')
            ->from('knb_sets_composition')
            ->where('set_id=:set_id', [':set_id'=>$set_id])
            ->leftJoin('knb_artefact', 'knb_sets_composition.artefact_id=knb_artefact.id')
            ->all();         


        

        if($artefactModel->load(Yii::$app->request->post())){

            if($artefactModel->validate()){
                $artefactModel->save();

                Yii::$app->session->setFlash('success', "Данные артефакта сохранены.");
                return $this->redirect(['artefact']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }

            
        }

        return $this->render('artefact-update', ['adminMenu'=>$adminMenu, 'modelUploadImage'=>$modelUploadImage, 'artefactModel'=>$artefactModel, 'lootList'=>$lootList,'artefactRecipeModel'=>$artefactRecipeModel,'artefactRecipeList'=>$artefactRecipeList, 'artefactChanceGet'=>$artefactChanceGet, 'sectorList'=>$sectorList, 'artefactChanceGetModel'=>$artefactChanceGetModel, 'setCompositionList'=>$setCompositionList, 'setCompositionInfo'=>$setCompositionInfo]);
        
    }


    public function actionArtefactRecipeAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelArtefactRecipe = new ArtefactRecipe();

        if($request->isPost && $modelArtefactRecipe->load(['ArtefactRecipe'=>$request->post()]) && $modelArtefactRecipe->validate() ){
            $modelArtefactRecipe->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelArtefactRecipe->id];
        }else{
            $error_text = "";
            foreach ($modelArtefactRecipe->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];
        }
    }

    public function actionArtefactRecipeRemove()
    {
        $request = \Yii::$app->getRequest();
        
        if($request->isPost && isset($request->post()["id"])){

            $modelARecipe = ArtefactRecipe::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelARecipe){
                $modelARecipe->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelARecipe->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, лут не найден'];
            }
        }
    }

    public function actionArtefactRemove($id)
    {
        $artefactModel = Artefact::findOne($id);

        if($artefactModel && $artefactModel->delete())
        {
            ArtefactRecipe::deleteAll(['artefact_id'=>$id]);
            return 1;

        }else{

            return 0;

        }
    }

    public function actionArtefactChanceGetAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelArtefactChanceGet = new ArtefactChanceGet();
        if($request->isPost && $modelArtefactChanceGet->load(['ArtefactChanceGet'=>$request->post()]) && $modelArtefactChanceGet->validate()){
            $modelArtefactChanceGet->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelArtefactChanceGet->id];
        }else{
            $error_text = "";
            foreach ($modelArtefactChanceGet->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }

    }

    public function actionArtefactChanceGetRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelACGet = ArtefactChanceGet::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelACGet){
                $modelACGet->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelACGet->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, лут не найден'];
            }
        }
    }


    public function actionSets()
    {

        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $setsModel = new Sets();
        
        $setsDataProvider = $setsModel->search(Yii::$app->request->queryParams);

        
        return $this->render('sets', ['adminMenu'=>$adminMenu, 'setsDataProvider'=>$setsDataProvider, 'setsModel'=>$setsModel]);

    }

        
    public function actionSetCreate()
    {
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $setsModel = new Sets();
        $modelUploadImage = new UploadFiles();

        //Загружаем данные из формы
        if ($setsModel->load(Yii::$app->request->post())) {
             
                // Сохраняем данные в БД
                if($setsModel->validate()){
                    $setsModel->save();
                    $sets_id = $setsModel->id;

                    Yii::$app->session->setFlash('success', "Данные сета сохранены.");
                    return $this->redirect(['set-update', 'id'=>$sets_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

            

        }

        return $this->render('set-create', ['adminMenu'=>$adminMenu, 'setsModel'=>$setsModel,'modelUploadImage'=>$modelUploadImage]);

        
    } 

    public function actionSetUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $setsModel = Sets::findOne($id);
        $artefactList = ArrayHelper::map(Artefact::find()->orderBy('name asc')->all(),'id','name');

        $setComposition = SetComposition::find()
            ->select('knb_artefact.image, knb_sets_composition.id, knb_sets_composition.artefact_id, knb_sets_composition.set_id')
            ->leftJoin('knb_artefact', 'knb_artefact.id = knb_sets_composition.artefact_id')
            ->where('knb_sets_composition.set_id = :set_id', [':set_id'=>$id])
            ->all();

        $setCompositionModel = new SetComposition();

        if($setsModel->load(Yii::$app->request->post())){
            if($setsModel->validate()){
                $setsModel->save();

                Yii::$app->session->setFlash('success', "Данные сета сохранены.");
                return $this->redirect(['sets']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }
        }



        return $this->render('set-update', ['adminMenu'=>$adminMenu, 'artefactList'=>$artefactList, 'setsModel'=>$setsModel, 'setComposition'=>$setComposition, 'setCompositionModel'=>$setCompositionModel]);

               
    } 

    public function actionSetRemove($id)
    {
        $setsModel = Sets::findOne($id);

        if($setsModel && $setsModel->delete())
        {
            return 1;

        }else{

            return 0;

        }
    } 

    public function actionSetCompositionAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelSetComposition = new SetComposition();
        if($request->isPost && $modelSetComposition->load(['SetComposition'=>$request->post()]) && $modelSetComposition->validate()){
            $modelSetComposition->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelSetComposition->id];
        }else{
            $error_text = "";
            foreach ($modelSetComposition->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }
    }
    public function actionSetCompositionRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelSetComposition = SetComposition::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelSetComposition){
                $modelSetComposition->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSetComposition->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, Артефакт не найден'];
            }
        }
    }
    
    public function actionStructures()
    {

        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $structuresModel = new Structures();
        
        $structuresDataProvider = $structuresModel->search(Yii::$app->request->queryParams);

        
        return $this->render('structures', ['adminMenu'=>$adminMenu, 'structuresDataProvider'=>$structuresDataProvider, 'structuresModel'=>$structuresModel]);

    }

    public function actionStructureCreate()
    {
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $structuresModel = new Structures();
        $modelUploadImage = new UploadFiles();

        $structureTypeList = ArrayHelper::map(StructuresType::find()->orderBy('name asc')->all(), 'id', 'name');

        //Загружаем данные из формы
        if ($structuresModel->load(Yii::$app->request->post())) {
                      
            
                // Сохраняем данные в БД
                if($structuresModel->validate()){
                    $structuresModel->save();
                    $structures_id = $structuresModel->id;

                    Yii::$app->session->setFlash('success', "Данные сета сохранены.");
                    return $this->redirect(['structure-update', 'id'=>$structures_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

            

        }

        return $this->render('structure-create', ['adminMenu'=>$adminMenu, 'structuresModel'=>$structuresModel, 'structureTypeList'=>$structureTypeList, 'modelUploadImage'=>$modelUploadImage]);

        
    } 

    public function actionStructureUpdate($id)
    {
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $structureModel = Structures::findOne($id);
        
        if($structureModel == NULL){
            return $this->redirect(['structures']);
        }

        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(), 'id', 'name');
        $roomsList = ArrayHelper::map(Rooms::find()->orderBy('name asc')->all(), 'id', 'name');

        $monsterWFSModel = new MonsterWhereFindToRoom();

        $sectorList = ArrayHelper::map(Sector::find()->orderBy('name asc')->where('active=1')->all(),'id', 'name');

        $structureTypeList = ArrayHelper::map(StructuresType::find()->orderBy('name asc')->all(), 'id', 'name');

        $structureConnections = StructuresConnections::find()
            ->select('knb_structures_connections.id, knb_structures_connections.parent, knb_structures_connections.sector_id, knb_structures_connections.structure_id, knb_sector.image, knb_sector.name')
            ->where('structure_id = :structure_id', [':structure_id'=>$id])
            ->leftJoin('knb_sector', 'knb_sector.id = knb_structures_connections.sector_id')
            ->all();

           
        
        $roomToStructureList = RoomsConnections::find()
            ->select('knb_rooms.name, knb_rooms.image, knb_rooms_connections.id, knb_rooms_connections.room_id, knb_rooms_connections.structura_id, knb_rooms_connections.parent')
            ->leftJoin('knb_rooms', 'knb_rooms.id = knb_rooms_connections.room_id')
            ->where('structura_id = :structura_id', [':structura_id'=>$id])
            ->all();

        $whoLiveInStructure = MonsterWhereFindToRoom::find()
            ->select('knb_monster_where_find_room.id as id, knb_monster.name, knb_monster.image, knb_monster_where_find_room.monster_id, knb_monster_where_find_room.time_of_day')
            ->leftJoin('knb_monster', 'knb_monster.id=knb_monster_where_find_room.monster_id')
            ->where('structure_id=:id', [':id'=>$id])
            // ->asArray()
            ->all();
        
            
        $structureTo = [];
        $structureFrom = [];

        foreach($structureConnections as $connect){    
            
            //var_dump($connect->attributes);

            if($connect->parent){
                $array_attr =  $connect->attributes;
                $array_attr["image"] = $connect->image;                         
                $structureFrom[] = $array_attr;
            }else{
                $array_attr =  $connect->attributes;
                $array_attr["image"] = $connect->image;
                $structureTo[] = $array_attr;
            }

        }

       

        if($structureModel && $structureModel->load(Yii::$app->request->post())){
            if($structureModel->validate()){
                $structureModel->save();

                Yii::$app->session->setFlash('success', "Данные сета сохранены.");
                return $this->redirect(['structures']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }
        }

        $monsterInStructureList = [];
        $room_array = [];
        
        $roomListInStructure = Yii::$app->db->createCommand(
            "SELECT rc.room_id FROM knb_structures as s left join knb_rooms_connections as rc on rc.structura_id = s.id where s.id = :sructure_id")
                ->bindValue(':sructure_id', $id)
                ->queryAll();
        
        // var_dump($roomListInStructure[0]["room_id"]);
        // die;
        if($roomListInStructure[0]["room_id"]){

            foreach($roomListInStructure as $room)
            {
                $room_array[] = $room["room_id"];
            }

            $monsterId_str = implode($room_array, ",");

            $monsterInStructureRow = Yii::$app->db->createCommand(
                "SELECT m.id as monster_id, m.name, m.image, mwf.structure_id FROM knb_monster_where_find_room as mwf left join  knb_monster as m on m.id = mwf.monster_id  WHERE structure_id in(".$monsterId_str.")")
                    ->queryAll();
            
                    
            foreach($monsterInStructureRow as $mRow){

                $monsterInStructureList[$mRow["name"]][] = ["structure_id"=>$mRow["structure_id"], "monster_id"=>$mRow["monster_id"], 'image'=>$mRow["image"]];

            }                
        }


        $structutesToModel = new StructuresConnections();

        return $this->render('structure-update', ['adminMenu'=>$adminMenu, 'structureModel'=>$structureModel, 'structureTo'=>$structureTo, 'structureFrom'=>$structureFrom, 'sectorList'=>$sectorList, 'structutesToModel'=>$structutesToModel, 'structureTypeList'=>$structureTypeList, 'whoLiveInStructure'=>$whoLiveInStructure, 'monsterWFSModel'=>$monsterWFSModel, 'monsterList'=>$monsterList, 'monsterInStructureList'=>$monsterInStructureList, 'roomsList'=>$roomsList, 'roomToStructureList'=>$roomToStructureList]);

               
    } 

    public function actionStructureRemove($id)
    {
        $structureModel = Structures::findOne($id);

        if($structureModel && $structureModel->delete())
        {
            return 1;

        }else{

            return 0;

        }
    } 

    public function actionStructureConnectionAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelStructureTo = new StructuresConnections();
        if($request->isPost && $modelStructureTo->load(['StructuresConnections'=>$request->post()]) && $modelStructureTo->validate()){
            $modelStructureTo->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelStructureTo->id];
        }else{
            $error_text = "";
            foreach ($modelStructureTo->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }
    }

    public function actionStructureConnectionRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelStructureTo = StructuresConnections::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelStructureTo){
                $modelStructureTo->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelStructureTo->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, сектор не найден'];
            }
        }
    }

    public function actionEffects()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $effectsModel = new Effects();


        $params = Yii::$app->request->queryParams;
        
        $params["type_id"] = 3;
        $effectsDataProvider = $effectsModel->search($params);
        
        $params["type_id"] = 2;
        $effectsSectorsDataProvider = $effectsModel->search($params);
        
        $params["type_id"] = 1;
        $effectsGenerationOfSectorDataProvider = $effectsModel->search($params);


        
        return $this->render('effects', ['adminMenu'=>$adminMenu, 'effectsDataProvider'=>$effectsDataProvider, 'effectsModel'=>$effectsModel, 'effectsSectorsDataProvider'=>$effectsSectorsDataProvider, 'effectsGenerationOfSectorDataProvider'=>$effectsGenerationOfSectorDataProvider]);

    }

    public function actionEffectCreate($type_id){

        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $effectsModel = new Effects();
        $effectsModel->load(['Effects'=>["type_id"=>$type_id]]);
        $modelUploadImage = new UploadFiles();

        $effectsTypeList = ArrayHelper::map(EffectsType::find()->orderBy('name asc')->all(), 'id', 'name');
        $effectsInfluenceTypeList = ArrayHelper::map(EffectsInfluence::find()->orderBy('name asc')->all(), 'id', 'name');

        //Загружаем данные из формы
        if ($effectsModel->load(Yii::$app->request->post())) {
                      
            
                
                // Сохраняем данные в БД
                if($effectsModel->validate()){
                    $effectsModel->save();
                    $effect_id = $effectsModel->id;

                    Yii::$app->session->setFlash('success', "Данные сета сохранены.");
                    return $this->redirect(['effect-update', 'id'=>$effect_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

           

        }

        return $this->render('effect-create', ['adminMenu'=>$adminMenu, 'effectsModel'=>$effectsModel, 'effectsTypeList'=>$effectsTypeList, 'effectsInfluenceTypeList'=>$effectsInfluenceTypeList, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionEffectUpdate($id){
        
        

        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $effectModel = Effects::findOne($id);
        
        if(!$effectModel){
            return $this->redirect(['effects']);
        }

        $effectsTypeList = ArrayHelper::map(EffectsType::find()->orderBy('name asc')->all(), 'id', 'name');
        $effectsInfluenceTypeList = ArrayHelper::map(EffectsInfluence::find()->orderBy('name asc')->all(), 'id', 'name');
        
        $effects_type_id = $effectModel->type_id;

        if($effects_type_id == 3 || $effects_type_id == 2){
            
            $effectsList = ArrayHelper::map(Effects::find()->orderBy('name asc')->where('type_id=3')->all(), 'id', 'name');
                
        }elseif($effects_type_id == 1){
            
            $effectsList = ArrayHelper::map(Effects::find()->orderBy('name asc')->where(['in', 'type_id', [3,2]])->all(), 'id', 'name');

        }else{
            $effectsList = ArrayHelper::map(Effects::find()->orderBy('name asc')->all(), 'id', 'name');
        }

        

        $modelEffectToEffect = new EffectToEffect();

        $effectTGEList = EffectToEffect::find()
            ->select('knb_effects.name, knb_effects.image, knb_effects.type_id,  knb_effect_to_effect.id,  knb_effect_to_effect.children_effect_id')
            ->leftJoin('knb_effects', 'knb_effects.id = knb_effect_to_effect.children_effect_id')
            ->where('parent_effect_id=:effect_id', [':effect_id'=>$id])
            ->all();

        $effectToEffectList = EffectToEffect::find()
            ->select('knb_effects.name, knb_effects.type_id,  knb_effect_to_effect.id,  knb_effect_to_effect.children_effect_id')
            ->leftJoin('knb_effects', 'knb_effects.id = knb_effect_to_effect.children_effect_id')
            ->where('parent_effect_id=:effect_id', [':effect_id'=>$id])
            ->all();    


        if($effectModel->load(Yii::$app->request->post())){
            if($effectModel->validate()){
                $effectModel->save();

                Yii::$app->session->setFlash('success', "Данные эффекта сохранены.");
                return $this->redirect(['effects']);
            }else{

                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }
        }



        return $this->render('effect-update', ['adminMenu'=>$adminMenu, 'effectModel'=>$effectModel,'effectsTypeList'=>$effectsTypeList, 'effectsInfluenceTypeList'=>$effectsInfluenceTypeList, 'effectsList'=>$effectsList, 'modelEffectToEffect'=>$modelEffectToEffect, 'effectTGEList'=>$effectTGEList]);

    }

    public function actionEffectRemove($id){
        $effectModel = Effects::findOne($id);

        if($effectModel && $effectModel->delete())
        {
            return 1;

        }else{

            return 0;

        } 
    }

    public function actionEffectOfSkillAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelEffectOfSkill = new EffectOfSkill();
        
        if($request->isPost && $modelEffectOfSkill->load(['EffectOfSkill'=>$request->post()]) && $modelEffectOfSkill->validate()){
            
                $modelEffectOfSkill->save();

                $effectModel = Effects::find()->where('id=:id', [':id'=>$modelEffectOfSkill->effect_id])->one();

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelEffectOfSkill->id, 'image'=>$effectModel->image];
            

        }else{
            $error_text = "";
            foreach ($modelEffectOfSkill->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        } 
    }

    public function actionEffectOfSkillRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelEffectOfSkill = EffectOfSkill::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelEffectOfSkill){
                $modelEffectOfSkill->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelEffectOfSkill->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, эффект не найден'];
            }
        }
    }


    public function actionEffectToEffectAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelEffectToEffect = new EffectToEffect();
        if($request->isPost && $modelEffectToEffect->load(['EffectToEffect'=>$request->post()]) && $modelEffectToEffect->validate()){
            $modelEffectToEffect->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelEffectToEffect->id];
        }else{
            $error_text = "";
            foreach ($modelEffectToEffect->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }
    }

    public function actionEffectToEffectRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelEffectToEffect = EffectToEffect::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelEffectToEffect){
                $modelEffectToEffect->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelEffectToEffect->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, эффект не найден'];
            }
        }
    }

    public function actionGetEffectsList()
    {
        $request = \Yii::$app->getRequest();
        //$request->post()
        $effectsList = [];
        
        if($request->isPost && isset($request->post()["effect_type_id"]) && $request->post()["effect_type_id"])
        {
            
            $effectsList = ArrayHelper::map(Effects::find()->orderBy('name asc')->where('type_id=:type_id', [':type_id'=>$request->post()["effect_type_id"]])->all(), 'id', 'name');
            
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'effectsList'=>$effectsList];

        }else{
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'effectsList'=>$effectsList];
        }

        

    }

    public function actionFigures(){
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $figuresModel = new Figures();

        $figuresDataProvider = $figuresModel->search(Yii::$app->request->queryParams);

        
        return $this->render('figures', ['adminMenu'=>$adminMenu, 'figuresDataProvider'=>$figuresDataProvider, 'figuresModel'=>$figuresModel]);


    }
    public function actionFigureCreate(){
        

        $request = \Yii::$app->getRequest();
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $figureModel = new Figures();
        $modelUploadImage = new UploadFiles();
        $typeFigure = $figureModel->getTypeFigure();
        
        $figureOptionsValueModel = new FigureOptionsValue();
        $figureOptionsTitle = FigureOptionsTitle::find()->all();

        $figuresFormation = ArrayHelper::map(Formations::find()->all(), 'id', 'name');

        $monsterClassList = ArrayHelper::map(MonsterClass::find()->orderBy('name asc')->all(),'id','name');

        $figureModel->isMercenary = 0;

        if ($request->isPost && $figureModel->load(['Figures'=>Yii::$app->request->post()])) {
            

            // Сохраняем данные в БД
            if($figureModel->validate()){
                $figureModel->save();
                $figure_id = $figureModel->id;


                // перебираем значения переменных option
                foreach($request->post() as $key=>$option_value)
                {
                    
                    //плучаем id и значения
                    if(preg_match("|option_id_|", $key)){
                        
                        $option_id =  str_replace("option_id_", "", $key);
                        $figureOption = new FigureOptionsValue();
                        $figureOption->option_id = $option_id;
                        $figureOption->value = $option_value;
                        $figureOption->figure_id = $figure_id;
                        
                        if($option_value != ""  && $figureOption->validate())
                        {

                            $figureOption->save();
                        }

                    }
                }


                Yii::$app->session->setFlash('success', "Данные фигуры сохранены.");
                return $this->redirect(['figure-update', 'id'=>$figure_id]);

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => 'OK'];

            }else{

                
                $error_text = "";
                foreach ($figureModel->getErrors() as $key => $value) {
                    $error_text .= $value[0]."; ";
                }

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => $error_text];

            }

           

        }else{
                
            

        }

        return $this->render('figure-create', ['adminMenu'=>$adminMenu, 'figureModel'=>$figureModel, 'typeFigure'=>$typeFigure, 'figuresFormation'=>$figuresFormation, 'figureOptionsValueModel'=>$figureOptionsValueModel,'figureOptionsTitle'=>$figureOptionsTitle, 'modelUploadImage'=>$modelUploadImage, "monsterClassList"=>$monsterClassList]);

    }
    public function actionFigureUpdate($id){
        

        $request = \Yii::$app->getRequest();
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $figureModel = Figures::findOne($id);
        
        $typeFigure = $figureModel->getTypeFigure();

        $figuresRang = $figureModel->getRangFigure();
       
        $figuresFormation = ArrayHelper::map(Formations::find()->all(), 'id', 'name');

        $figureOptionsTitle = FigureOptionsTitle::find()->all();
        $figureOptionsValueModel = new FigureOptionsValue();
        $figureOptionsValue = ArrayHelper::map(FigureOptionsValue::find()->where('figure_id=:id', [':id'=>$id])->all(), 'option_id', 'value');
        
        $monsterClassList = ArrayHelper::map(MonsterClass::find()->orderBy('name asc')->all(),'id','name');

        $skillList = ArrayHelper::map(Skills::find()->orderBy('name asc')->all(), 'id', 'name');
        
        $skillOfFigureList = SkillsOfFigure::find()
            ->select('knb_skills.image, knb_skills_of_figure.id,  knb_skills_of_figure.skill_id')
            ->leftJoin('knb_skills', 'knb_skills.id = knb_skills_of_figure.skill_id')
            ->where('knb_skills_of_figure.figure_id = :figure_id', [':figure_id'=>$id])
            ->all();

        if($request->isAjax && $request->isPost && $figureModel->load(['Figures'=>Yii::$app->request->post()])){

            
            if($figureModel->validate()){
                
                $figureModel->save();   


                // перебираем значения переменных option
                foreach($request->post() as $key=>$option_value)
                {
                    $error_text = "";
                    // Проверяем есть ли в параметрах не цифровые значения
                    if(preg_match("|option_id_|", $key) && preg_match("|\D|", $option_value)){
                        
                        $error_text = "Ошибка! В поле параметр должны быть только цыфры";
                        \Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['error' => $error_text." ".$key." ".$option_value];

                    }       

                    if($option_value != ""){

                             
                        //плучаем id и значения
                        if(preg_match("|option_id_|", $key)){
                            
                            $option_id =  str_replace("option_id_", "", $key);


                            $figureOption = FigureOptionsValue::find()->where('figure_id = :figure_id and option_id = :option_id', [':option_id'=>$option_id, ':figure_id'=>$figureModel->id])->one();
                            
                            if(!$figureOption){
                                
                                $figureOption = new FigureOptionsValue();
                                $figureOption->figure_id = $figureModel->id;
                                $figureOption->option_id = $option_id;

                            }
                            
                            $figureOption->value = $option_value;
                            
                            
                            if($figureOption->validate())
                            {

                                $figureOption->save();
                                
                                

                            }else{
                                
                                $error_text = "";
                                foreach ($figureModel->getErrors() as $key => $value) {
                                    $error_text .= $value[0]."; ";
                                }

                                \Yii::$app->response->format = Response::FORMAT_JSON;
                                return ['error' => $error_text];
                                //die;
                            }

                        }
                    }
                }




                //Yii::$app->session->setFlash('success', "Данные фигуры сохранены.");

                //$this->redirect(['figure-update', 'id'=>$figure_id]);

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "Данные фигуры сохранены."];
                // $this->redirect(['figures']);

            }else{

                $error_text = "";
                foreach ($figureModel->getErrors() as $key => $value) {
                    $error_text .= $value[0]."; ";
                }

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => $error_text];  
            }
        }



        return $this->render('figure-update', ['adminMenu'=>$adminMenu, 'figureModel'=>$figureModel, 'typeFigure'=>$typeFigure, 'figuresFormation'=>$figuresFormation, 'figureOptionsTitle'=>$figureOptionsTitle, 'figureOptionsValue'=>$figureOptionsValue, 'figureOptionsValueModel'=>$figureOptionsValueModel, 'skillList'=>$skillList, 'skillOfFigureList'=>$skillOfFigureList, 'figuresRang'=>$figuresRang, "monsterClassList"=>$monsterClassList]);

    }
    public function actionFigureRemove($id){
        $figureModel = Figures::findOne($id);

        if($figureModel && $figureModel->delete())
        {
            return 1;

        }else{

            return 0;

        }     
    }

    public function actionRooms()
    {
        
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $roomsModel = new Rooms();

        $roomsDataProvider = $roomsModel->search(Yii::$app->request->queryParams);

        
        return $this->render('rooms', ['adminMenu'=>$adminMenu, 'roomsDataProvider'=>$roomsDataProvider, 'roomsModel'=>$roomsModel]);
    }


    public function actionRoomCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $roomsModel = new Rooms();
        $modelUploadImage = new UploadFiles();


        //Загружаем данные из формы
        if ($roomsModel->load(Yii::$app->request->post())) {
                      
            
                // Сохраняем данные в БД
                if($roomsModel->validate()){
                    $roomsModel->save();
                    $room_id = $roomsModel->id;

                    Yii::$app->session->setFlash('success', "Данные комнаты сохранены.");
                    return $this->redirect(['room-update', 'id'=>$room_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

            

        }

        return $this->render('room-create', ['adminMenu'=>$adminMenu, 'roomsModel'=>$roomsModel, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionRoomUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $roomModel = Rooms::findOne($id);
        $modelUploadImage = new UploadFiles();
        $structuresList = ArrayHelper::map(Structures::find()->orderBy('name asc')->all(), 'id', 'name');
        $roomsConnectionsModel = new RoomsConnections();

        $roomConnectionsToList = NULL;
        

        $roomConnectionsFromList = RoomsConnections::find()
            ->select('knb_rooms_connections.id, knb_rooms_connections.structura_id, knb_rooms_connections.room_id, knb_rooms_connections.parent,  knb_structures.image, knb_structures.name')
            ->leftJoin('knb_structures', 'knb_rooms_connections.structura_id=knb_structures.id')
            ->where('knb_rooms_connections.room_id=:room_id', ['room_id'=>$id])
             // ->asArray()
            ->all();

        $roomType = RoomsConnections::getTypeRoom();
            
        $roomToRoomConnectList = RoomToRoomConnections::find()
            ->select('knb_room_to_room_connections.id, knb_rooms.image, knb_rooms.name, knb_room_to_room_connections.children_room_id')
            ->leftJoin('knb_rooms', 'knb_room_to_room_connections.children_room_id = knb_rooms.id')
            ->where('parent_room_id = :room_id', [':room_id'=>$id])
            // ->asArray()
            ->all();
    
            
        $roomToRoomConnectionModel = new RoomToRoomConnections();

        $roomsList = ArrayHelper::map(Rooms::find()->orderBy('name asc')->all(), 'id', 'name');
        
        $monsterWFSModel = new MonsterWhereFindToRoom();

        $monsterList = ArrayHelper::map(Monster::find()->orderBy('name asc')->all(), 'id', 'name');              
        $whoLiveInStructure = MonsterWhereFindToRoom::find()
            ->select('knb_monster_where_find_room.id as id, knb_monster.name, knb_monster.image, knb_monster_where_find_room.monster_id, knb_monster_where_find_room.time_of_day')
            ->leftJoin('knb_monster', 'knb_monster.id=knb_monster_where_find_room.monster_id')
            ->where('structure_id=:id', [':id'=>$id])
            // ->asArray()
            ->all();
        
        //Загружаем данные из формы
        if ($roomModel->load(Yii::$app->request->post())) {
                      
            
                // Сохраняем данные в БД
                if($roomModel->validate()){
                    $roomModel->save();
                    $room_id = $roomModel->id;

                    Yii::$app->session->setFlash('success', "Данные комнаты сохранены.");
                    return $this->redirect(['rooms']);
                }else{

                    Yii::$app->session->setFlash('error', "Но общие данные не сохранены.");
                }

            

        }

        return $this->render('room-update', ['adminMenu'=>$adminMenu, 'roomModel'=>$roomModel, 'modelUploadImage'=>$modelUploadImage, 'structuresList'=>$structuresList, 'roomsConnectionsModel'=>$roomsConnectionsModel, 'roomToRoomConnectionModel'=>$roomToRoomConnectionModel, 'roomsList'=>$roomsList, 'roomConnectionsToList'=>$roomConnectionsToList, 'roomConnectionsFromList'=>$roomConnectionsFromList, 'roomToRoomConnectList'=>$roomToRoomConnectList, 'whoLiveInStructure'=>$whoLiveInStructure, 'monsterList'=>$monsterList, 'monsterWFSModel'=>$monsterWFSModel, 'roomType'=>$roomType]);

    }


    public function actionRoomRemove($id)
    {
        $roomModel = Rooms::findOne($id);

        if($roomModel && $roomModel->delete())
        {
            return 1;

        }else{

            return 0;

        }   
    }

    public function actionRoomsConnectionsAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelRoomConnectTo = new RoomsConnections();
        if($request->isPost && $modelRoomConnectTo->load(['RoomsConnections'=>$request->post()]) && $modelRoomConnectTo->validate()){
            $modelRoomConnectTo->save();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => "OK", 'id'=>$modelRoomConnectTo->id];
        }else{
            $error_text = "";
            foreach ($modelRoomConnectTo->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }    
    }

    public function actionRoomsConnectionsRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelRoomConnect = RoomsConnections::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelRoomConnect){
                $modelRoomConnect->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelRoomConnect->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, сектор не найден'];
            }
        }    
    }

    public function actionRoomToRoomConnectionsAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelRoomToRoomConnect = new RoomToRoomConnections();
        
        if($request->isPost && $modelRoomToRoomConnect->load(['RoomToRoomConnections'=>$request->post()]) && $modelRoomToRoomConnect->validate()){
            
                $modelRoomToRoomConnect->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelRoomToRoomConnect->id];
            

        }else{
            $error_text = "";
            foreach ($modelRoomToRoomConnect->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        }    
    }

    public function actionRoomToRoomConnectionsRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelRoomToRoomConnect = RoomToRoomConnections::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelRoomToRoomConnect){
                $modelRoomToRoomConnect->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelRoomToRoomConnect->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, сектор не найден'];
            }
        } 
    }


    public function actionSkills()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $skillsModel = new Skills();

        $params = Yii::$app->request->queryParams;
        
        $params["rang_id"] = 1;
        $skillsDataProviderPassive = $skillsModel->search($params);
        $params["rang_id"] = 2;
        $skillsDataProviderFirst = $skillsModel->search($params);
        $params["rang_id"] = 3;
        $skillsDataProviderSecond = $skillsModel->search($params);
        $params["rang_id"] = 4;
        $skillsDataProviderThird = $skillsModel->search($params);
        $params["rang_id"] = 5;
        $skillsDataProviderFourth = $skillsModel->search($params);
        $params["rang_id"] = 6;
        $skillsDataProviderFifth = $skillsModel->search($params);
        $params["rang_id"] = 7;
        $skillsDataProviderSpecial = $skillsModel->search($params);

        
        return $this->render('skills', ['adminMenu'=>$adminMenu, 'skillsDataProviderPassive'=>$skillsDataProviderPassive, 'skillsDataProviderFirst'=>$skillsDataProviderFirst, 'skillsDataProviderSecond'=>$skillsDataProviderSecond, 'skillsDataProviderThird'=>$skillsDataProviderThird, 'skillsDataProviderFourth'=>$skillsDataProviderFourth, 'skillsDataProviderFifth'=>$skillsDataProviderFifth, 'skillsDataProviderSpecial'=>$skillsDataProviderSpecial,
            'skillsModel'=>$skillsModel]);
    }

    public function actionSkillCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        
        if($get = Yii::$app->request->get()){
            $rang_id = $get['rang_id'];
        }else{
            $rang_id = 1;
        }
        
        $skillModel = new Skills();
        $skillModel->load(['Skills'=>["rang_id"=>$rang_id]]);

        $modelUploadImage = new UploadFiles();
        $skillRangsList = $skillModel::getRangs();


        //Загружаем данные из формы
        if ($skillModel->load(Yii::$app->request->post())) {
            
                // Сохраняем данные в БД
                if($skillModel->validate()){
                    $skillModel->save();
                    $skill_id = $skillModel->id;

                    Yii::$app->session->setFlash('success', "Данные умения сохранены.");
                    return $this->redirect(['skill-update', 'id'=>$skill_id]);

                }else{

                    Yii::$app->session->setFlash('error', "Данные не сохранены.");
                }


        }

        return $this->render('skill-create', ['adminMenu'=>$adminMenu, 'skillModel'=>$skillModel, 'modelUploadImage'=>$modelUploadImage, 'skillRangsList'=>$skillRangsList]);
    }

    public function actionSkillUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $skillModel = Skills::findOne($id);

        if(!$skillModel){
            return $this->redirect(['skills']);
        }

        $modelUploadImage = new UploadFiles();
        $effectOfSkillModel = new EffectOfSkill();

        $skillRangsList = $skillModel::getRangs();

        $effectList = ArrayHelper::map(Effects::find()->orderBy('name asc')->all(), 'id', 'name');
        $effectTypeList = ArrayHelper::map(EffectsType::find()->orderBy('name asc')->all(), 'id', 'name'); 

        $effectOfSkill = EffectOfSkill::find()
            ->select('knb_effects.name, knb_effects.image, knb_effects.type_id, knb_efficts_of_skills.id, knb_efficts_of_skills.effect_id, knb_efficts_of_skills.skill_id')
            ->leftJoin('knb_effects', 'knb_effects.id = knb_efficts_of_skills.effect_id')
            ->where('skill_id = :id', [':id'=>$id])
            ->orderBy('knb_effects.name asc')
            // ->asArray()
            ->all(); 

       

        if ($skillModel->load(Yii::$app->request->post())) {
            
            // Сохраняем данные в БД
            if($skillModel->validate()){
                
                $skillModel->save();
                
                Yii::$app->session->setFlash('success', "Данные умения сохранены.");
                return $this->redirect(['skills']);
            }else{
                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }   
        }

        return $this->render('skill-update', ['adminMenu'=>$adminMenu, 'skillModel'=>$skillModel, 'modelUploadImage'=>$modelUploadImage, 'skillRangsList'=>$skillRangsList, 'effectList'=>$effectList, 'effectOfSkill'=>$effectOfSkill, 'effectTypeList'=>$effectTypeList, 'effectOfSkillModel'=>$effectOfSkillModel]);

    }

    public function actionSkillRemove($id)
    {
        $skillModel = Skills::findOne($id);

        if($skillModel && $skillModel->delete())
        {
            return 1;

        }else{

            return 0;

        }  
    }

    public function actionSkillOfFigureAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelSkillsOfFigure = new SkillsOfFigure();
        
        if($request->isPost && $modelSkillsOfFigure->load(['SkillsOfFigure'=>$request->post()]) && $modelSkillsOfFigure->validate()){
            
                $modelSkillsOfFigure->save();

                $skillModel = Skills::find()->where('id=:id', [':id'=>$modelSkillsOfFigure->skill_id])->one();

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSkillsOfFigure->id, 'image'=>$skillModel->image];
            

        }else{
            $error_text = "";
            foreach ($modelSkillsOfFigure->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        } 
    }

    public function actionSkillOfFigureRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelSkillsOfFigure = SkillsOfFigure::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelSkillsOfFigure){
                $modelSkillsOfFigure->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSkillsOfFigure->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, умения не найден'];
            }
        } 
    }



    public function actionSkillOfMonsterAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelSkillsOfMonster = new SkillsOfMonster();
        
        if($request->isPost && $modelSkillsOfMonster->load(['SkillsOfMonster'=>$request->post()]) && $modelSkillsOfMonster->validate()){
            
                $modelSkillsOfMonster->save();

                $skillModel = Skills::find()->where('id=:id', [':id'=>$modelSkillsOfMonster->skill_id])->one();

                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSkillsOfMonster->id, 'image'=>$skillModel->image];
            

        }else{
            $error_text = "";
            foreach ($modelSkillsOfMonster->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        } 
    }

    public function actionSkillOfMonsterRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelSkillsOfMonster = SkillsOfMonster::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelSkillsOfMonster){
                $modelSkillsOfMonster->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelSkillsOfMonster->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, умения не найден'];
            }
        } 
    }



    public function actionTerms()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $termsModel = new Terms();
        $termsDataProvider = $termsModel->search(Yii::$app->request->queryParams);

        
        return $this->render('terms', ['adminMenu'=>$adminMenu, 'termsDataProvider'=>$termsDataProvider, 'termsModel'=>$termsModel]);
    }    

    public function actionTermCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $termsModel = new Terms();
        $modelUploadImage = new UploadFiles();


        //Загружаем данные из формы
        if ($termsModel->load(Yii::$app->request->post())) {
                      
            
                // Сохраняем данные в БД
                if($termsModel->validate()){
                    $termsModel->save();
                    $term_id = $termsModel->id;

                    Yii::$app->session->setFlash('success', "Данные термина сохранены.");
                    return $this->redirect(['term-update', 'id'=>$term_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Данные не сохранены.");
                }

            

        }

        return $this->render('term-create', ['adminMenu'=>$adminMenu, 'termsModel'=>$termsModel, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionTermUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $termModel = Terms::findOne($id);
        $modelUploadImage = new UploadFiles();
        $effectsType = Terms::getEffectType();

        
        if ($termModel->load(Yii::$app->request->post())) {
            
            // Сохраняем данные в БД
            if($termModel->validate()){
                
                $termModel->save();
                
                Yii::$app->session->setFlash('success', "Данные термина сохранены.");
                return $this->redirect(['terms']);
            }else{
                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }   
        }

        return $this->render('term-update', ['adminMenu'=>$adminMenu, 'termModel'=>$termModel, 'modelUploadImage'=>$modelUploadImage, 'effectsType'=>$effectsType]);

    }
    public function actionTermRemove($id)
    {
        $termModel = Terms::findOne($id);

        if($termModel && $termModel->delete())
        {
            return 1;

        }else{

            return 0;

        }   
    }

    public function actionFormations()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();
        
        $formationsModel = new Formations();
        $formationsDataProvider = $formationsModel->search(Yii::$app->request->queryParams);

        
        return $this->render('formations', ['adminMenu'=>$adminMenu, 'formationsDataProvider'=>$formationsDataProvider, 'formationsModel'=>$formationsModel]);
    }    

    public function actionFormationCreate()
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $formationsModel = new Formations();
        $modelUploadImage = new UploadFiles();


        //Загружаем данные из формы
        if ($formationsModel->load(Yii::$app->request->post())) {
                      
            
                // Сохраняем данные в БД
                if($formationsModel->validate()){
                    $formationsModel->save();
                    $formation_id = $formationsModel->id;

                    Yii::$app->session->setFlash('success', "Данные формирования сохранены.");
                    return $this->redirect(['formation-update', 'id'=>$formation_id]);
                }else{

                    Yii::$app->session->setFlash('error', "Данные не сохранены.");
                }

            

        }

        return $this->render('formation-create', ['adminMenu'=>$adminMenu, 'formationModel'=>$formationsModel, 'modelUploadImage'=>$modelUploadImage]);
    }

    public function actionFormationUpdate($id)
    {
        $adminMenu = AdminMenu::find()->where('active=1 and role=1')->orderBy('name asc')->all();

        $formationModel = Formations::findOne($id);
        $modelUploadImage = new UploadFiles();

        $formationEffectsList = FormationEffect::find()
            ->select('knb_effects.image, knb_effects.name, knb_effects.type_id,  knb_formation_effect.id,  knb_formation_effect.formation_id, knb_formation_effect.effect_id')
            ->leftJoin('knb_effects', 'knb_effects.id = knb_formation_effect.effect_id')
            ->where("formation_id=:formation_id", [':formation_id'=>$id])
            // ->asArray()
            ->all();
        
        // var_dump($formationEffectsList);
        // die;

        $effectsList = ArrayHelper::map(Effects::find()->orderBy('name asc')->all(), 'id', 'name');
        $effectsTypeList = ArrayHelper::map(EffectsType::find()->orderBy('name asc')->all(), 'id', 'name');
        if ($formationModel->load(Yii::$app->request->post())) {
            
            // Сохраняем данные в БД
            if($formationModel->validate()){
                
                $formationModel->save();
                
                Yii::$app->session->setFlash('success', "Данные термина сохранены.");
                return $this->redirect(['formations']);
            }else{
                Yii::$app->session->setFlash('error', "Данные не сохранены.");
            }   
        }

        return $this->render('formation-update', ['adminMenu'=>$adminMenu, 'formationModel'=>$formationModel, 'modelUploadImage'=>$modelUploadImage, 'formationEffectsList'=>$formationEffectsList, 'effectsList'=>$effectsList, 'effectsTypeList'=>$effectsTypeList]);
    }

    public function actionFormationRemove($id)
    {
        $formationModel = Formations::findOne($id);

        if($formationModel && $formationModel->delete())
        {
            return 1;

        }else{

            return 0;

        }
    }   

    public function actionFormationEffectAdd()
    {
        $request = \Yii::$app->getRequest();
        $modelFormationEffect = new FormationEffect();
        
        if($request->isPost && $modelFormationEffect->load(['FormationEffect'=>$request->post()]) && $modelFormationEffect->validate()){
            
                $modelFormationEffect->save();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelFormationEffect->id];
            

        }else{
            $error_text = "";
            foreach ($modelFormationEffect->getErrors() as $key => $value) {
                $error_text .= $value[0]."; ";
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $error_text];    
        } 
    }

    public function actionFormationEffectRemove()
    {
        $request = \Yii::$app->getRequest();
        if($request->isPost && isset($request->post()["id"])){
            $modelFormationEffect = FormationEffect::find()->where('id=:id', [':id'=>$request->post()["id"]])->one();
            if($modelFormationEffect){
                $modelFormationEffect->delete();
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => "OK", 'id'=>$modelFormationEffect->id];
            }else{
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => 'Ошибка, сектор не найден'];
            }
        }
    }     
}