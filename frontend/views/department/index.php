<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'department_id',
             ['attribute'=>'company_fk_id','value' => 'company.company_name','label'=>'Company Name'],
            ['attribute'=>'branch_fk_id','value' => 'branch.branch_name','label'=>'Branch Name'],
            'department_name',
            'department_create',
            // 'department_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
