<?php

namespace backend\controllers;

use Yii;
use app\models\Permission;
use app\models\PermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;


/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class PermissionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->layout='dashboard';
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$this->p($model);
            $auth = Yii::$app->authManager;
            $name = $model->name;
            $type = $model->type;
            $description = $model->description;
        // add "createPost" permission
            $createPost = $auth->createPermission($name);
            $createPost->description = $description;
            $createPost->type = $type;
            $auth->add($createPost);
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;
        $currentdata = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $name = $model->name;
            $type = $model->type;
            
            $description = $model->description;
            $updatePost = $auth->createPermission($name);
            $updatePost->description = $description;
            $updatePost->type = $type;
            $check =  $this->checkupdate($updatePost,$currentdata);
            //$auth->add($updatePost);
            if($check == '1'){
                $auth->add($updatePost,$model);
            }
            
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function Checkupdate($updatePost,$model){
        //$this->p($updatePost,0);
        //$this->p($model);
        $modelname = $model->name;
        $modeltype = $model->type;
        $modeldescription = $model->description;

        $updatename = $updatePost->name;
        $updatetype = $updatePost->type;
        $updatedescription = $updatePost->description;
        if( ($modelname == $updatename) && ($modeltype == $updatetype) && ($modeldescription == $updatedescription))
        {   $return = '0';
            
        }else{
             $query = new Query;
             $query ->createCommand()
            ->delete('auth_item', ['name' => $modelname])
            ->execute();
           
            $return = '1';
        }
        return $return;
    }
}
