<?php
/* @var $this AsiakkaatSivuController */
/* @var $model AsiakkaatSivu */

$this->breadcrumbs=array(
	'Asiakkaat Sivus'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AsiakkaatSivu', 'url'=>array('index')),
	array('label'=>'Create AsiakkaatSivu', 'url'=>array('create')),
	array('label'=>'Update AsiakkaatSivu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AsiakkaatSivu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AsiakkaatSivu', 'url'=>array('admin')),
);
?>

<h1>View AsiakkaatSivu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'asiakkaan_nimi',
		'html_content',
	),
)); ?>
