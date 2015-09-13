<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	Yii::t('main', 'Työntekijä')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);

$this->menu=array(
	array('label'=>'List Tyontekijat', 'url'=>array('index')),
	array('label'=>'Create Tyontekijat', 'url'=>array('create')),
	array('label'=>'View Tyontekijat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);


?>

<div class="pull-right">
 <div class="kuva form-inline">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
   <input type="hidden" name="uploaded" value="true" />
   <input type="file" name="file" id="i_file" data-icon="false" data-buttonText="Etsi kuvaa" class="form-group" />
   <input type="submit" value="Lataa kuva" class="btn btn-primary btn-group" id="kuvaUP" /></button>
  </form>
 </div>
</div>

<h1><?php echo Yii::t('main', 'Työntekijä ID:'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
