<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	'Kohteets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Kohteet', 'url'=>array('index')),
	array('label'=>'Manage Kohteet', 'url'=>array('admin')),
);
?>

<h1>Create Kohteet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>