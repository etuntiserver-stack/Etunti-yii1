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



if(isset($_POST['uploaded'])){

  if (!file_exists(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'.jpg');
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     echo "";
  } 
}
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
