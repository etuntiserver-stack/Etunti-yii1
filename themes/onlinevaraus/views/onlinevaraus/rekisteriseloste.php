<?php
  $r = array();
  foreach($rekisteriseloste as $val)
  {
	$ex = explode("___", $val);
	if(isset($ex[0]) and isset($ex[1]))
	$r[$ex[0]] = $ex[1];
  }


?>
<style>
body{ background: white; }
</style>

<div id="rekisteriteloste">

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <?php echo Yii::t('main', 'REKISTERISELOSTE'); ?></span><br> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Henkilötietolaki (523/1999) 10 §'); ?></h2>
                <div class="panel-body bg-light">
                 <div class="row">



<div class="pull-right small">
Laatimispäivä<br>
<span class="small"><?php if(isset($r['laatimispaiva'])) echo $r['laatimispaiva']; ?></span><br>
</div>

<?php if($this->isEtuntiAdmin()): ?>
<p class="small">Lue <a href="<?php echo Yii::app()->request->baseUrl.'/tiedostot/tayttoohjeet.pdf'; ?>" target="_blank">täyttöohjeet</a> ennen rekisteriselosteen<br>
täyttämistä. Käytä tarvittaessa liitettä.</p><br>
<br>
<?php endif; ?>

<table class="table table-bordered">
<tr>
	<td class="col-sm-3">1a<br> Rekisterin-pitäjä</td>
	<td class="col-sm-9">
		<label class="small">Nimi</label><br>
		<span class="small"><?php if(isset($r['rekisterinpitaja_nimi'])) echo $r['rekisterinpitaja_nimi']; ?></span><br>
		<label class="small">Osoite</label><br>
		<span class="small"><?php if(isset($r['rekisterinpitaja_osoite'])) echo $r['rekisterinpitaja_osoite']; ?></span><br>
		<label class="small">Muut yhteystiedot (esim. puhelin virka-aikana, sähköpostiosoite)</label><br>
		<span class="small"><?php if(isset($r['muut_rekisterinpitaja'])) echo $r['muut_rekisterinpitaja']; ?></span><br>
	</td>
</tr>
<tr>
	<td class="col-sm-3">2<br>Yhteyshenkilö rekisteriä koskevissa asioissa</td>
	<td class="col-sm-9">
		<label class="small">Nimi</label><br>
		<span class="small"><?php if(isset($r['yhteys_nimi'])) echo $r['yhteys_nimi']; ?></span><br>
		<label class="small">Osoite</label><br>
		<span class="small"><?php if(isset($r['yhteys_osoite'])) echo $r['yhteys_osoite']; ?></span><br>
		<label class="small">Muut yhteystiedot (esim. puhelin virka-aikana, sähköpostiosoite)</label><br>
		<span class="small"><?php if(isset($r['muut_yhteystiedot'])) echo $r['muut_yhteystiedot']; ?></span><br>
	</td>
</tr>
<tr>
	<td class="col-sm-3">3<br>Rekisterin nimi</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['rekisterin_nimi'])) echo $r['rekisterin_nimi']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">4<br>Henkilötietojen käsittelyn tarkoitus</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['henkilotietojen_kasittelyn'])) echo $r['henkilotietojen_kasittelyn']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">5<br>Rekisterin tietosisältö</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['rekisterin_tietosisalto'])) echo $r['rekisterin_tietosisalto']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">6<br>Säännönmukaiset tieto-lähteet</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['saannonmukaiset_tieto_lahteet'])) echo $r['saannonmukaiset_tieto_lahteet']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">7<br>Tietojen säännönmukaiset luovutukset</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['saannonmukaiset_luovutukset'])) echo $r['saannonmukaiset_luovutukset']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">8<br>Tietojen siirto EU:n tai ETA:n ulkopuolelle</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['tietojen_siirto'])) echo $r['tietojen_siirto']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">9<br>Rekisterin suojauksen periaatteet</td>
	<td class="col-sm-9">
		<label class="small">A Manuaalinen aineisto</label><br>
		<span class="small"><?php if(isset($r['manuaalinen_aineisto'])) echo $r['manuaalinen_aineisto']; ?></span><br>
		<label class="small">B ATK:lla käsiteltävät tiedot</label><br>
		<span class="small"><?php if(isset($r['suojauksen_periaatteet'])) echo $r['suojauksen_periaatteet']; ?></span><br>
	</td>
</tr>
<tr>
	<td class="col-sm-3">10<br>Tarkastusoikeus</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['tarkastusoikeus'])) echo $r['tarkastusoikeus']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">11<br>Oikeus vaatia tiedon korjaamista</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['tiedon_korjaamista'])) echo $r['tiedon_korjaamista']; ?></span><br></td>
</tr>
<tr>
	<td class="col-sm-3">12<br>Muut henkilötietojen käsittelyyn liittyvät oikeudet</td>
	<td class="col-sm-9"><span class="small"><?php if(isset($r['muut_oikeudet'])) echo $r['muut_oikeudet']; ?></span><br></td>
</tr>

</table>



                 </div>
                </div>
              </div>
            </div>


 
        <!-- loppu: .tray-center -->
        </div>


</div>



