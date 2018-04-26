<?php
  $r = array();
if(is_array($rekisteriseloste))
{
  foreach($rekisteriseloste as $val)
  {
	$ex = explode("___", $val);
	if(isset($ex[0]) and isset($ex[1]))
	$r[$ex[0]] = $ex[1];
  }
}

?>
<div id="rekisteriteloste">

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-gear"></i> <?php echo Yii::t('main', 'REKISTERISELOSTE'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Henkilötietolaki (523/1999) 10 §');?></h2>
                <div class="panel-body bg-light">
                 <div class="row">



<div class="pull-right small">
Laatimispäivä<br>
<input type="text" id="laatimispaiva" class="form-control datepicker" value="<?php if(isset($r['laatimispaiva'])) echo $r['laatimispaiva']; ?>">
</div>

<p class="small">Lue <a href="<?php echo Yii::app()->request->baseUrl.'/lib/pdf/tayttoohjeet.pdf'; ?>" target="_blank">täyttöohjeet</a> ennen rekisteriselosteen<br>
täyttämistä. Käytä tarvittaessa liitettä.</p>

<br>

<style>
table{ background: white; }
</style>


<table class="table table-bordered">
<tr>
	<td class="col-sm-3">1a<br> Rekisterin-pitäjä</td>
	<td class="col-sm-9">
		<p class="small">Nimi</p>
		<textarea id="rekisterinpitaja_nimi" class="form-control" rows="1"><?php if(isset($r['rekisterinpitaja_nimi'])) echo $r['rekisterinpitaja_nimi']; ?></textarea>
		<p class="small">Osoite</p>
		<textarea id="rekisterinpitaja_osoite" class="form-control" rows="1"><?php if(isset($r['rekisterinpitaja_osoite'])) echo $r['rekisterinpitaja_osoite']; ?></textarea>
		<p class="small">Muut yhteystiedot (esim. puhelin virka-aikana, sähköpostiosoite)</p>
		<textarea id="muut_rekisterinpitaja" class="form-control" rows="2"><?php if(isset($r['muut_rekisterinpitaja'])) echo $r['muut_rekisterinpitaja']; ?></textarea>
	</td>
</tr>
<tr>
	<td class="col-sm-3">2<br>Yhteyshenkilö rekisteriä koskevissa asioissa</td>
	<td class="col-sm-9">
		<p class="small">Nimi</p>
		<textarea id="yhteys_nimi" class="form-control" rows="1"><?php if(isset($r['yhteys_nimi'])) echo $r['yhteys_nimi']; ?></textarea>
		<p class="small">Osoite</p>
		<textarea id="yhteys_osoite" class="form-control" rows="1"><?php if(isset($r['yhteys_osoite'])) echo $r['yhteys_osoite']; ?></textarea>
		<p class="small">Muut yhteystiedot (esim. puhelin virka-aikana, sähköpostiosoite)</p>
		<textarea id="muut_yhteystiedot" class="form-control" rows="2"><?php if(isset($r['muut_yhteystiedot'])) echo $r['muut_yhteystiedot']; ?></textarea>
	</td>
</tr>
<tr>
	<td class="col-sm-3">3<br>Rekisterin nimi</td>
	<td class="col-sm-9"><textarea id="rekisterin_nimi" class="form-control" rows="2"><?php if(isset($r['rekisterin_nimi'])) echo $r['rekisterin_nimi']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">4<br>Henkilötietojen käsittelyn tarkoitus</td>
	<td class="col-sm-9"><textarea id="henkilotietojen_kasittelyn" class="form-control" rows="2"><?php if(isset($r['henkilotietojen_kasittelyn'])) echo $r['henkilotietojen_kasittelyn']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">5<br>Rekisterin tietosisältö</td>
	<td class="col-sm-9"><textarea id="rekisterin_tietosisalto" class="form-control" rows="2"><?php if(isset($r['rekisterin_tietosisalto'])) echo $r['rekisterin_tietosisalto']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">6<br>Säännönmukaiset tieto-lähteet</td>
	<td class="col-sm-9"><textarea id="saannonmukaiset_tieto_lahteet" class="form-control" rows="2"><?php if(isset($r['saannonmukaiset_tieto_lahteet'])) echo $r['saannonmukaiset_tieto_lahteet']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">7<br>Tietojen säännönmukaiset luovutukset</td>
	<td class="col-sm-9"><textarea id="saannonmukaiset_luovutukset" class="form-control" rows="2"><?php if(isset($r['saannonmukaiset_luovutukset'])) echo $r['saannonmukaiset_luovutukset']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">8<br>Tietojen siirto EU:n tai ETA:n ulkopuolelle</td>
	<td class="col-sm-9"><textarea id="tietojen_siirto" class="form-control" rows="2"><?php if(isset($r['tietojen_siirto'])) echo $r['tietojen_siirto']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">9<br>Rekisterin suojauksen periaatteet</td>
	<td class="col-sm-9">
		<p class="small">A Manuaalinen aineisto</p>
		<textarea id="manuaalinen_aineisto" class="form-control" rows="1"><?php if(isset($r['manuaalinen_aineisto'])) echo $r['manuaalinen_aineisto']; ?></textarea>
		<p class="small">B ATK:lla käsiteltävät tiedot</p>
		<textarea id="suojauksen_periaatteet" class="form-control" rows="1"><?php if(isset($r['suojauksen_periaatteet'])) echo $r['suojauksen_periaatteet']; ?></textarea>
	</td>
</tr>
<tr>
	<td class="col-sm-3">10<br>Tarkastusoikeus</td>
	<td class="col-sm-9"><textarea id="tarkastusoikeus" class="form-control" rows="2"><?php if(isset($r['tarkastusoikeus'])) echo $r['tarkastusoikeus']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">11<br>Oikeus vaatia tiedon korjaamista</td>
	<td class="col-sm-9"><textarea id="tiedon_korjaamista" class="form-control" rows="2"><?php if(isset($r['tiedon_korjaamista'])) echo $r['tiedon_korjaamista']; ?></textarea></td>
</tr>
<tr>
	<td class="col-sm-3">12<br>Muut henkilötietojen käsittelyyn liittyvät oikeudet</td>
	<td class="col-sm-9"><textarea id="muut_oikeudet" class="form-control" rows="2"><?php if(isset($r['muut_oikeudet'])) echo $r['muut_oikeudet']; ?></textarea></td>
</tr>

</table>


<br>
<span class="btn btn-primary myBgColors tallennaTaulun">Tallenna taulun</span>


                 </div>
                </div>
              </div>
            </div>


 
        <!-- loppu: .tray-center -->
        </div>


</div>



<script type="text/javascript">
$(document).ready(function(){


$(".tallennaTaulun").click(function(event){

    event.preventDefault();
    var searchIDs = $("#rekisteriteloste input, #rekisteriteloste textarea").map(function(){
      return $(this).attr('id')+"___"+$(this).val();
    }).get(); 

    console.log(searchIDs);


        $.ajax({
           url: 'rekisteriseloste',
	   type:'POST',
	   data: { "rekisteriseloste" : searchIDs },
           success: function(data){
		//console.log(data);
		window.location.reload();

           }
        });


});



});
</script>



