<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
	$is_local = in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']);
	$db_host = ($is_local)?'localhost':'10.215.25.9';
	$tyontekijat_maara = 0;
	Yii::app()->db1->setActive(false);
	Yii::app()->db1->connectionString = 'mysql:host='.$db_host.';dbname='.$data->domain;
	Yii::app()->db1->setActive(true);
	$tt = Tyontekijat::model()->findAll(" aktiivinen=1 ");
	if(isset($tt[0]))
	$tyontekijat_maara = count($tt);
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update_etunnin_asiakas', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<?php echo $data->domain; ?>
	</td>
	<td>
		<?php echo $data->yritys; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $tyontekijat_maara; ?>
	</td>
	<td>
		<?php echo $this->moduliMuutos($data->paketti); ?>
	</td>
	<td>
		<?php echo CHtml::link('Näytä', array('etunnin_asiakas_kk', 'id'=>$data->id), array('class'=>'btn btn-sm btn-primary myBgColors', 'style'=>'color:white')); ?>
	</td>
	<td>
		<?php 
			echo $data->getAttributeLabel('palveluhinta_persiivoja').': '.$data->palveluhinta_persiivoja.' &euro;<br>';
			echo $data->getAttributeLabel('tyovuorohinta_persiivoja').': '.$data->tyovuorohinta_persiivoja.' &euro;<br>';
			echo $data->getAttributeLabel('muut_tyokaluhinta').': '.$data->muut_tyokaluhinta.' &euro;';

		?>
	</td>

	<?php if( isset($_GET['maksullinen']) and $_GET['maksullinen'] == 0 ): ?>
	<td>
		<?=$data->ilmainen_versio_kayttotunnit?>h
	</td>
	<?php endif; ?>

	<td>
		<?php echo CHtml::link('Näytä', array('etunnin_asiakas_kk_laskuri', 'id'=>$data->id), array('class'=>'btn btn-sm btn-primary myBgColors', 'style'=>'color:white')); ?>
	</td>

</tr>
