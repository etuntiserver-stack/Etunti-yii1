<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */

$this->breadcrumbs=array(
	'Firman Tiedots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
/*
$this->menu=array(
	array('label'=>'List FirmanTiedot', 'url'=>array('index')),
	array('label'=>'Create FirmanTiedot', 'url'=>array('create')),
	array('label'=>'View FirmanTiedot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
*/
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'YRITYS'); ?> </h2>

        <!-- loppu: .tray-center -->
        </div>
<br>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>





