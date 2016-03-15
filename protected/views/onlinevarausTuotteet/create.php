<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */

$this->breadcrumbs=array(
	'Onlinevaraus Tuotteets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OnlinevarausTuotteet', 'url'=>array('index')),
	array('label'=>'Manage OnlinevarausTuotteet', 'url'=>array('admin')),
);
?>

<h1>Create OnlinevarausTuotteet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>