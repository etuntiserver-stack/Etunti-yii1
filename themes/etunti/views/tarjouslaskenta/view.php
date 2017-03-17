<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */

	$tb = json_decode($model->muut_kulut, true);
	$muut = '';
	if(is_array(json_decode($model->muut_kulut, true)))
	{
		$muut .= '
		<div class="row">
		 <div class="col-sm-4">';
		foreach($tb['otsikko'] as $key=>$items)
		{
		$muut .= $items.': '.$tb['hinta'][$key].'<br>';

		}
		$muut .= '
		 </div>
		</div>';
	}

$model->muut_kulut = $muut;

$tp = LaskutusTuotteet::model()->findByPk($model->tuote_palvelu_id);
if(isset($tp->id))
$model->tuote_palvelu_id = $tp->tuotenimi;
$model->time = date("d.m.Y", strtotime($model->time));

$asiakas = '';
$a = Asiakkaat::model()->findByPk($model->asiakas_id);
$y = Yhteystiedot::model()->findByPk($model->yhteystiedot_id);

if($model->asiakas_id != 0 and isset($a->id))
{
	if(!empty($a->yrityksen_nimi) and empty($a->yhteyshenkilo))
	$asiakas = $a->yrityksen_nimi;
	elseif(empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
	$asiakas = $a->yhteyshenkilo;
}
if($model->yhteystiedot_id != 0 and isset($y->id))
{
	if(!empty($y->yrityksen_nimi) and empty($y->yhteyshenkilo))
	$asiakas = $y->yrityksen_nimi;
	elseif(empty($y->yrityksen_nimi) and !empty($y->yhteyshenkilo))
	$asiakas = $y->yhteyshenkilo;
}

$model->asiakas_id = $asiakas;
?>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		//'id',
		'time',
		//'yhteystiedot_id',
		'asiakas_id',
		'tuote_palvelu_id',
		'hinta_tyyppi',
		'neliot',
		'kayntikerrat',
		'tuntien_maara',
		'yhteensa',
		//'tavoite_myyntikate',
		//'palkkakustannus',
		'matkat',
		'iltalisa',
		'yolisa',
                        array
                        (
                                'name'=>'muut_kulut',
                                'type'=>'raw',
                        ),
	),
)); ?>


<script>
jQuery(function(){

    $("table.detail-view").addClass('table table-bordered');
});
</script>


