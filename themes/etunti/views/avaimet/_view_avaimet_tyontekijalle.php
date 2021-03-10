<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

  $avain_tyontekijalla = [];
  $avain_sijainti = [];

  if(isset($avaimet)){
	foreach($avaimet as $avain){
	   if( isset($kohteet->id) and $avain->kohde == $kohteet->id ){
  		$avain_tyontekijalla[] = $this->etuSukunimi($avain->tid);
		$lisays = '';
		if($avain->sijainti_omatekstti == 0 and $avain->sijainti == 2)
				$lisays .= '<br>'. $avain->palautetu_asiakkaalle_pvm;
		if(!empty($avain->ovikoodi))
				$lisays .= '<br><b>Ovikoodi:</b>'. $avain->ovikoodi;	
		$avain_sijainti[] = $this->sijaintiText($avain->id).$lisays;
	   }
	}
  }
?>


<tr <?=($data->peruutettu == 1)?'class="text-danger"':''?>>
	<td>
		
	</td>
	<td class="text-center">
		<?=($data->peruutettu == 1)?'<h2>'.Yii::t('main', 'Työvuoro on peruutettu').'</h2>':''?>
		<?php
		if(isset($kohteet->id)){
			$as = Asiakkaat::model()->findByPk($kohteet->asiakas_id);
			if(isset($as->id))
				echo $as->Fullname;
		}
		?>
	</td>
	<td>
		<?php
		if(isset($kohteet->osoite)){
			echo $kohteet->osoite; 
		}
		?>
	</td>
	<td>
		<p><?php echo $arr['this_pvm']; ?></p>
		<p><?php echo $data->alku; ?> - <?php echo $data->loppu; ?></p>
		<p><?php if(isset($tt->id)) { echo $this->etuSukunimi($tt->id); } ?></p>
	</td>
	<td>
		<?php
		if(isset($avaimet)){
			foreach($avaimet as $avain){
			   echo '<p><input type="checkbox" class="avainnumero" value="'.$avain->id.'"> '.$avain->avainnumero.'</p>';
			}
		}
		?>
	</td>
	<td>
		<?php foreach($avain_tyontekijalla as $item) : ?>
		  <p><?=$item?></p>
		<?php endforeach; ?>
	</td>
	<td>
		<?php foreach($avain_sijainti as $item) : ?>
		  <?php if(empty($item)) : ?>
		  <p>---</p>
		  <?php else: ?>
		  <p><?=$item?></p>
		  <?php endif; ?>
		<?php endforeach; ?>
	</td>
</tr>
