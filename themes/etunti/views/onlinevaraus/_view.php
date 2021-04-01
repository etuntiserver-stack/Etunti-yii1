<?php
/* @var $this OnlinevarausController */
/* @var $data Onlinevaraus */

$tilauksen_kuvaus = json_decode($data->tilauksen_kuvaus, true);
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
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
		<?php echo date("d.m.Y  H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php 
		$tv = Tyovuoroot::model()->findbypk($data->tv_id);
		if(isset($tv->id))
		{
			$tekija = Tyontekijat::model()->findbypk($tv->tid);
			if(isset($tekija->id))
			{
			   echo '
				<b>'.$this->etuSukunimi($tekija->id).'</b><br>
				'.$tv->pvm.'<br>
				'.$tv->alku.'-'.$tv->loppu.'
			   ';
			}
		}
		?>
	</td>
	<td>
		<?php echo $data->kesto.'h'; ?>
	</td>
	<td>
		<?php echo $data->hinta.'&euro;'; ?>
	</td>
	<td>
		<?php 
		if($data->tila == 1) 
			echo 'Maksettu';
		else 
			echo '<span class="text-danger">Ei maksettu</span>';
		?>
	</td>
	<td>
		<?php

if(isset($tilauksen_kuvaus['paa']) and isset($tilauksen_kuvaus['lisa']))
{
  foreach($tilauksen_kuvaus['paa'] as $k=>$v)
	echo $k.' '.$v.' m²<br>';
  foreach($tilauksen_kuvaus['lisa'] as $k=>$v)
	echo  $k.' '.$v.' h<br>';
}
		?>
	</td>

	<td>
		<?php echo $data->yhteyshenkilo; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $data->lisatietoja; ?>
	</td>
	<td>
	<?php
		$criteria = new CDbCriteria();
		$criteria->condition = " kohde_id='".$data->kohde_id."'  ";
		$kuvk = KuviaKohteesta::model()->findAll($criteria);

		foreach($kuvk as $d)
		{
			//echo '<a href="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$d->tiedosto.'">'.$d->tiedosto.'</a><br>';
			// <-- file_safe_opener
			$filepath = dirname(Yii::app()->getBasePath()).'/img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$d->tiedosto;

			if( file_exists($filepath) ){
			$imageData = base64_encode(file_get_contents($filepath));
			$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;

			echo CHtml::link('<img src="'.$src.'" class="img-responsive thumbnail" style="height:200px">',
				array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
				array('target'=>'_blank','class'=>'text-danger'
			));
			}
			//     file_safe_opener -->
		}
	?>
	</td>
</tr>

