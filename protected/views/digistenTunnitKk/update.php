<?php
/* @var $this DigistenTunnitKkController */
/* @var $model DigistenTunnitKk */

$this->breadcrumbs=array(
	'Digisten Tunnit Kks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DigistenTunnitKk', 'url'=>array('index')),
	array('label'=>'Create DigistenTunnitKk', 'url'=>array('create')),
	array('label'=>'View DigistenTunnitKk', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DigistenTunnitKk', 'url'=>array('admin')),
);
?>

<h1>Update DigistenTunnitKk <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>