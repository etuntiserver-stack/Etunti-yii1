<?php

Yii::app()->clientScript->registerScript('avoimet', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lasku-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$asetukset = Asetukset::model()->findbypk(1);
$palvelu = '';
if($asetukset->palvelu_tyyppi == 1)
$palvelu = 'POSTITA';
if($asetukset->palvelu_tyyppi == 2)
$palvelu = 'TRUST';

?>

<legend>
<h1> <?php echo Yii::t('main', 'Avoimet laskut ').$palvelu; ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>



<?php 

  foreach($model as $data)
  {
	echo 'Tapahtuma pvm:'.$data->time.' - Lasku id:'.$data->lid.', statuscode: '.$data->trust_statuscode.'<br>';
  }
?>
