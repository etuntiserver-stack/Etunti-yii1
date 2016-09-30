<?php
/* @var $this LaskuController */
/* @var $data Lasku */


	$asiakas='';
	$a = Asiakkaat::model()->find( " asiakasnumero='".$data->as_nro."' ");
	if(isset($a->id) and $a->tyyppi == 'yritys')
	$asiakas = $a->yrityksen_nimi;
	if(isset($a->id) and $a->tyyppi == 'henkilo')
	$asiakas = $a->yhteyshenkilo;

?>
<tr>
	<td>
		<?php echo $data->laskunumero; ?>
	</td>
	<td>
		<?php echo $asiakas; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->viitenumero; ?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $this->tilanneCheck($data,null); ?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i",strtotime($data->tapahtumapvm)); ?>
	</td>
	<td>
		<?php 
			if($data->yhteensa_total > 0)
			echo number_format($data->yhteensa_total, 2, ",", " "); 
		?>

	</td>
	<td>
		<?php 
			if($this->avoinnaCheck($data,null) > 0)
			echo number_format($this->avoinnaCheck($data,null), 2, ",", " "); 
		?>
	</td>
	<td>
		<?php echo $data->laskun_nimetys; ?>
	</td>
	<td>
		<i class="link fa fa-history" for="<?php echo $data->id; ?>"></i>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>
