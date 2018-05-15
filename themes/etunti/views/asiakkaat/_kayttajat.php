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
		$str = 'Lähetä eDico tunnukset asiakkaalle';
		if(empty($data->salasana) and empty($data->token))
			$str = Yii::t('main', 'Lähetä eDico<br> tunnukset asiakkaalle');
		if(empty($data->salasana) and !empty($data->token)){
			$str = Yii::t('main', 'Tunnukset on lähetetty.<br> Lähetä uudelleen');
		}
		if(!empty($data->salasana) and empty($data->token)){
			$str = Yii::t('main', 'Tunnus on aktiivinen.<br> Lähetä uudelleen');
		}
		?>

		<span class="btn btn-primary myBgColors btn-block"><input type="checkbox" class="valitse_asiakas pull-right"> <?=$str?></span>
	<?php else: ?>
		<span class="btn btn-primary myBgColors btn-block"><?=Yii::t('main', 'Sähköposti puutuu.')?></span>
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
