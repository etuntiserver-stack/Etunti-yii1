<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakkaat')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

/*
$this->menu=array(
	array('label'=>Yii::t('main', 'Lista asiakas'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo asiakas'), 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#asiakkaat-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

/*
if(isset($_POST['up']) and Yii::app()->user->username == 'roman')
{

  Asiakkaat::model()->deleteAll();
  $k = Kohteet::model()->findAll();
 foreach($k as $v){

  $a = new Asiakkaat;
  $a->yhteyshenkilo=$v->etu_suku_nimet;
  $a->osoite=$v->osoite;
  $a->kaupunki=$v->kaupunki;
  $a->postinumero=$v->pnumero;
  $a->puhelin=$v->puh_nro;
  $a->sahkoposti=$v->email;
  $a->ryhma=0;
  $a->aktiivinen=1;

  	if($a->save())
  	{
		Kohteet::model()->updateByPk($v->id,array('asiakas_id'=>$a->id));
		//echo $v->id.'<br>';
  	} else {
		echo $v->id.' ei tallennettu<br>';
 	}
  }
}

if(Yii::app()->user->username == 'roman') {
echo '
<form action="#" method="POST">
<input type="submit" name="up" value="update kantaat">
</form>
';
} 
*/
?>




<legend>
   <div class="pull-right">
     <?php echo CHtml::link(' Lisää uusi asiakas','/index.php/asiakkaat/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-user')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'ASIAKKAAT'); ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asiakkaat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped table-hover',

	'columns'=>array(
		'id',
		/*
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		*/
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		//'kaupunki',
  
               array(
                    'name'=>'ryhma',
                    'filter'=>CHtml::dropDownList('Asiakkaat[ryhma]','',
		    CHtml::listData(Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type")), 'id', 'value'),array('empty'=>'valitse','class'=>'form-control')),
                    
		),
		/*
		'postinumero',
		'puhelin',
		'sahkoposti',

		'aktiivinen',

		array(
			'class'=>'CButtonColumn',
		),
		*/
array(

        'value' => '
	   CHtml::link("Katso Muokkaa", Yii::app()->createUrl("asiakkaat/update",array("id"=>$data->id)))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),
	),
)); ?>
