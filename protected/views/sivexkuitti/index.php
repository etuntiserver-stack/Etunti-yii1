<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Luetut kohteet',
);

$this->menu=array(
	//array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Luetut Hallinta', 'url'=>array('admin')),
);
?>

<h1>Luetut kohteet</h1>


<div class="row">
  <table class="table table-striped">
  <tbody>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',

)); ?>
  </tbody>
  </table>
</div>
