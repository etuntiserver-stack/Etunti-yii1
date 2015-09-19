<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakkaat')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Lista asiakas'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo asiakas'), 'url'=>array('create')),
);

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

if(isset($_POST['up']) and Yii::app()->user->username == 'roman')
{
  Asiakkaat::model()->deleteAll();
  $k = Kohteet::model()->findAll();
 foreach($k as $v){

  $etunimi = '';
  $expl = explode(" ",$v->etu_suku_nimet);
  if(isset($expl[0]))
  $etunimi .= $expl[0];

  $sukunimi = '';
  $expl = explode(" ",$v->etu_suku_nimet);
  if(isset($expl[1]))
  $sukunimi .= $expl[1];
  if(isset($expl[2]))
  $sukunimi .= ' '.$expl[2];
  if(isset($expl[3]))
  $sukunimi .= ' '.$expl[3];

  $a = new Asiakkaat;
  $a->etunimi=$etunimi;
  $a->sukunimi=$sukunimi;
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
?>

<h1><?php echo Yii::t('main', 'Asiakas hallinta'); ?></h1>

<form action="#" method="POST">
<input type="submit" name="up" value="update kantaat">
</form>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asiakkaat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'etunimi',
		'sukunimi',
		'osoite',
		'kaupunki',
		'ryhma',
		/*
		'postinumero',
		'puhelin',
		'sahkoposti',

		'aktiivinen',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
