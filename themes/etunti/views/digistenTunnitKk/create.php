<?php
/* @var $this DigistenTunnitKkController */
/* @var $model DigistenTunnitKk */

$this->breadcrumbs=array(
	'Digisten Tunnit Kks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DigistenTunnitKk', 'url'=>array('index')),
	array('label'=>'Manage DigistenTunnitKk', 'url'=>array('admin')),
);
?>

<h1>Create DigistenTunnitKk</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>