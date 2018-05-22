<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */

  $osoite = $data->osoite;
  if(!empty($data->postinumero))
  $osoite .= ', '.$data->postinumero;
  if(!empty($data->kaupunki))
  $osoite .= ', '.$data->kaupunki;

  $tyyppi = '';
  $tauste = 'bg-default';
  $nimi   = '';
  if($data->tyyppi == 'yritys'){
  	$tyyppi = Yii::t('main', 'Yritys');
	$tauste = 'bg-primary';
        $nimi	= $data->yrityksen_nimi;
  } else if($data->tyyppi == 'henkilo') {
  	$tyyppi = Yii::t('main', 'Henkilö');
	$tauste = 'bg-warning';
        $nimi	= $data->yhteyshenkilo;
  }
?>

<tr>
	<td>
	<?php if(!empty($data->sahkoposti)): ?>
		<?php 
		$str = 'Lähetä eDico<br> tunnukset asiakkaalle';
		$style = '';
		if(empty($data->salasana) and empty($data->token) and $data->app_kayttoehdot != 1)
			$str = Yii::t('main', 'Lähetä eDico<br> tunnukset asiakkaalle');
		if(empty($data->salasana) and !empty($data->token) and $data->app_kayttoehdot != 1){
			$str = Yii::t('main', 'Tunnukset on lähetetty.<br> Lähetä uudelleen');
			$style = 'style="border-right: 5px #fec121 solid"';
		}
		if(!empty($data->salasana) and $data->app_kayttoehdot == 1){
			$str = Yii::t('main', 'Tunnus on aktiivinen.<br> Lähetä uudelleen');
			$style = 'style="border-right: 5px #2cda5e solid"';
		}
		if(!empty($data->salasana) and $data->app_kayttoehdot != 1){
			$str = Yii::t('main', 'Käyttöehtoja<br> ei vielä hyväksytty');
			$style = 'style="border-right: 5px #fec121 solid"';
		}
		?>

		<span class="btn btn-primary myBgColors btn-block" <?=$style?>><input type="checkbox" class="valitse_asiakas pull-right" asiakas_id="<?=$data->id?>"> <?=$str?></span>
	<?php else: ?>
		<span class="btn btn-primary myBgColors btn-block" style="border-right: 5px #ff5f4e solid"><?=Yii::t('main', 'Sähköposti puutuu.')?></span>
	<?php endif; ?>
	</td>
	<td>
		<?php echo $nimi; ?>
	</td>
	<td>
		<?php echo $osoite; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $this->TyoryhmaName($data->tyoryhma); ?>
	</td>
	<td>
		<?php echo $this->ryhmaMuutos($data->ryhma); ?>
	</td>
	<td class="<?php echo $tauste; ?>" align="center">
		<?php echo $tyyppi; ?>
	</td>


</tr>
