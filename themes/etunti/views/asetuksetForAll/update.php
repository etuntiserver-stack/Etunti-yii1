<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */

$this->breadcrumbs=array(
	'Asetukset For Alls'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
/*
$this->menu=array(
	array('label'=>'List AsetuksetForAll', 'url'=>array('index')),
	array('label'=>'Create AsetuksetForAll', 'url'=>array('create')),
	array('label'=>'View AsetuksetForAll', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AsetuksetForAll', 'url'=>array('admin')),
);
*/
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Pääasetukset'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
