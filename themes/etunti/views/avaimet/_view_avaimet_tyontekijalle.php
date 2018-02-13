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
		<input type="checkbox">
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
		<input type="text" class="form-control" value="<?=$avain_tyontekijalla?>">
	</td>
	<td>
		<input type="text" class="form-control" value="<?=$avain_sijainti?>">
	</td>
</tr>
