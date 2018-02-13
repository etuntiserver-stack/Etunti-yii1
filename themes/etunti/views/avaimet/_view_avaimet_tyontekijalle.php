<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

  $avain_tyontekijalla = '';
  $avain_sijainti = '';

  if(isset($data->avaimet)){
	foreach($data->avaimet as $avain){
	   if( isset($data->kohteet->id) and $avain->kohde == $data->kohteet->id ){
  		$avain_tyontekijalla = $this->etuSukunimi($avain->tid);
		$avain_sijainti = $avain->sijainti;
	     break;
	   }
	}
  }
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
		<?php
		if(isset($data->kohteet->id)){
			$as = Asiakkaat::model()->findByPk($data->kohteet->asiakas_id);
			if(isset($as->id) and $as->tyyppi == 'yritys')
				echo $as->yrityksen_nimi;
			if(isset($as->id) and $as->tyyppi == 'henkilo')
				echo $as->yhteyshenkilo;
		}
		?>
	</td>
	<td>
		<?php
		if(isset($data->kohteet->osoite)){
			echo $data->kohteet->osoite; 
		}
		?>
	</td>
	<td>
		<p><?php echo $data->pvm; ?></p>
		<p><?php echo $data->alku; ?> - <?php echo $data->loppu; ?></p>
		<p><?php echo $this->etuSukunimi($data->tt->id); ?></p>
	</td>
	<td>
		<?php
		if(isset($data->avaimet)){
			foreach($data->avaimet as $avain){
			   echo '<p>'.$avain->avainnumero.'</p>';
			}
		}
		?>
	</td>
	<td>
		<?=$avain_tyontekijalla?>
	</td>
	<td>
		<?=$avain_sijainti?>
	</td>
</tr>
