<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */

	$tb = json_decode($model->muut_kulut, true);
	$muut = '';
	if(is_array(json_decode($model->muut_kulut, true)))
	{
		$muut .= '
		<div class="row">
		 <div class="col-sm-4">
		  <table class="table">';
		foreach($tb['otsikko'] as $key=>$items)
		{
		$muut .= '
		    <tr class="rivi" num="'.$key.'">
		        <td>
		            '.$items.'
		        </td>
		        <td>
		            '.$tb['hinta'][$key].'
		        </td>
		    </tr>';

		}
		$muut .= '</table>
		 </div>
		</div>';
	}

$model->muut_kulut = $muut;
?>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		'id',
		'time',
		'yhteystiedot_id',
		'asiakas_id',
		'tuote_palvelu_id',
		'hinta_tyyppi',
		'neliot',
		'kayntikerrat',
		'tuntien_maara',
		'yhteensa',
		'tavoite_myyntikate',
		'palkkakustannus',
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
