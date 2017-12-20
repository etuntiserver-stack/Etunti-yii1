<?php
	if(isset($_SESSION['domain'])){ unset($_SESSION['domain']); }
?>
<br><br>

<div class="container">


<div class="row">
 <div class="col-sm-offset-3 col-sm-6">
  <div class="panel panel-default">
    <div class="panel-heading"><?=Yii::t('main', 'Aloita Etunnin käyttäminen')?></div>
    <div class="panel-body">

     <div class="row">

      <?php if(isset($_GET['aloita'])) : ?>
      <script src='https://www.google.com/recaptcha/api.js'></script>

      <div class="col-sm-12">
	<legend><h3 class="text-info"><?php echo Yii::t('main', 'Ota käyttöön'); ?></h3></legend>
      	<form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/aloita" id="aloita-lomake" method="POST" autocomplete="off">
        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Y-tunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Puhelinnumero')?></label>
        <input type="text" id="puhelinnumero" name="puhelinnumero" class="form-control input-lg">
        <label><?php echo Yii::t('main', 'Sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti" name="sahkoposti" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Vahvista sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti2" name="sahkoposti2" class="form-control input-lg" required>
	<br>
	<p>
		<a href="#" data-toggle="modal" data-target="#kehdot">Käyttöehdot</a> | 
		<?= CHtml::link('Rekisteriseloste',Yii::app()->request->baseUrl."/lib/pdf/Rekisteriseloste_08122017.pdf", array('target' => '_blank')) ?>
	</p>

	<p><div class="g-recaptcha" data-sitekey="6LcY2joUAAAAAFhvZM36PBNwej6vJiaHBZNsXeL4"></div></p>

        <p><button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Ota käyttöön'); ?></button></p>
    	</form>
	<div id="success"></div>

      </div>
      <?php endif; ?>

      <?php if(!isset($_GET['aloita'])) : ?>
      <div class="col-sm-12">
	<legend><h3 class="text-info"><?php echo Yii::t('main', 'Kirjaudu'); ?></h3></legend>
		<form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/user/login" method="POST">
		<label><?php echo Yii::t('main', 'Kirjautumistunnus')?></label>
		<input type="text" class="form-control input-lg" name="UserLogin[domain]" required>
		<label><?php echo Yii::t('main', 'Käyttäjätunnus')?></label>
		<input type="text" class="form-control input-lg" name="UserLogin[username]" required>
		<label><?php echo Yii::t('main', 'Salasana')?></label>
		<input type="password" class="form-control input-lg" name="UserLogin[password]" required>
		<br>
		<input type="submit" class="btn btn-lg btn-primary btn-group" value="Kirjaudu">
		</form>
		<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/salasanan_palauttaminen" class="link"><?php echo Yii::t('main', 'Unohditko salasanasi?'); ?></a>

      </div>
      <?php endif; ?>

     </div>

    </div>
  </div>
 </div>
</div>


<!-- Modal -->
<div id="kehdot" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Käyttöehdot</h4>
      </div>
      <div class="modal-body">
        <div class="well" style="background: #FFFFFF">
<p>1.	Soveltamisala</p>
<p>Näitä sopimusehtoja sovelletaan Digisten Oy:n (myöhemmin Digisten tai Palveluntoimittaja) verkon välityksellä tarjoamaan Etunti-verkkopalveluun (myöhemmin Etunti tai Etunti- verkkopalvelu) sekä Tilaajan asiointiin verkkopalvelussa.</p>
<p>Tilaajalla tarkoitetaan tahoa, joka tekee sopimuksen Palveluntoimittajan kanssa Etunti-verkkopalvelun käytöstä. Sopimuksen voi tehdä vain yritystunnuksen omaava oikeushenkilö. Henkilö, joka tekee sopimuksen oikeushenkilön puolesta, vakuuttaa että hänellä on oikeus tehdä sitova sopimus edustamansa oikeushenkilön puolesta.
Käyttäjällä tarkoitetaan henkilöä, joka käyttää Palveluntoimittajan verkkopalvelua Tilaajan lukuun.</p>
<p>2.	Sopimusasiakirjat</p>
<p>Sopimusasiakirjat määräämisjärjestyksessä ovat nämä Sopimusehdot sekä Palvelusopimus. Sopimusosapuolet ja palvelun sisältö määritellään Palvelusopimuksessa. Tilaajan käyttöön tulevat Etunti palvelut ja asiantuntijapalvelut. Mahdolliset muutokset ja lisätilaukset liitetään sopimukseen erillisellä Palvelusopimuksen päivityksellä.</p>
<p>3.	Sopimuksen syntyminen</p>
<p>Sopimus katsotaan syntyneeksi, kun Tilaaja hyväksyy nämä ehdot tai kun Toimittaja on muutoin todistettavasti hyväksynyt Tilaajan tilauksen.</p>
<p>4.	Palvelun käyttö</p>
<p>Etunti-verkkopalvelun käyttäminen edellyttää käyttäjän tunnistamista tunnistetietojen eli henkilökohtaisen käyttäjätunnuksen ja salasanan avulla. Henkilökohtaiset tunnukset luodaan järjestelmän valvojan tunnuksilla, jotka Tilaaja saa käyttönsä verkkopalvelun käyttöönoton yhteydessä.</p>
<p>Etunti-verkkopalvelua käytetään Internet selaimella. Tilaaja voi käyttää palvelua sekä
Suomessa että ulkomailla. Tietoturvateknisistä syitä Palveluntarjoaja voi rajoittaa palvelun käytettävyyttä yksittäisissä maissa. Palveluiden käyttöön ja Palveluntoimittajan velvoitteisiin sovelletaan kuitenkin kaikissa tilanteissa näiden sopimusehtojen lisäksi vain Suomen lakia.</p>
<p>Tilapäiset häiriöt puhelin- ja dataliikenteessä ovat mahdollisia ja ne voivat hetkellisesti estää tai hidastaa verkkopalvelun käytön.</p>
<p>Palveluntarjoaja ei vastaa kolmansien osapuolien palveluiden mahdollisista häiriöistä.</p>
<p>
5.	Tilaajan velvoitteet</p>
<p>Tilaaja hankkii Etunti-verkkopalvelun käyttöön soveltuvat puhelimet ohjelmistoineen sekä tarvittavat
tietoliikenneyhteydet ja vastaa niiden toimivuudesta, tietoturvasta sekä käyttö- ja ylläpitokustannuksista.</p>
<p>Tilaajakohtaiset käyttäjätunnukset ja salasanat luodaan Tilaajalle verkkopalvelun käyttöönoton yhteydessä. Tilaaja vastaa käyttäjätunnusten ja salasanojen asianmukaisesta käyttämisestä ja salassapidosta.</p>
<p>Tilaaja vastaa hänen tunnistetiedoillaan tehdyistä verkkopalvelun käytöstä.
Mikäli Palveluntoimittajalla on syytä epäillä tunnistetietojen väärinkäyttöä tai niiden joutumista vääriin käsiin, on tällä oikeus estää Etunnin verkkopalvelunkäyttö.</p>
<p>Mahdollisesti vääriin käsiin joutuneista salasanoista tai käyttäjätunnuksista on ilmoitettava Palveluntoimittajalle välittömästi. Tilaaja vastaa asiasta mahdollisesti aiheutuvasta vahingosta, kunnes hän on raportoinut asiasta Palveluntoimittajaa ja tälle on jäänyt kohtuullinen aika estää mahdolliset väärinkäytökset.</p>
<p>Tilaaja vastaa Tilaajan asiakastietojen viennistä Etunti-verkkopalvelun tietokantaan. Tilaaja on vastuussa asiakastietojen oikeellisuudesta ja mahdollisten virheiden korjaamisesta. Tilaaja sitoutuu varmistamaan, että tietokantaan vietyjen asiakastietojen perustana on vain olemassa oleva asiakkuus.</p>
<p>6.	Palveluntoimittajanvelvoitteet</p>
<p>Tilaajan yrityskohtaisten tietosisältöjen osalta (esim. asiakastiedot) Palveluntoimittajaa ja tämän henkilökuntaa sitoo salassapitovelvollisuus.</p>
<p>Palveluntoimittajalla ei ole oikeutta luovuttaa asiakastietoja ulkopuolisille tahoille ilman Tilaajan erillistä suostumusta pois lukien tilanteet jossa Suomen laki tätä edellyttää.</p>
<p>Palveluntoimittaja vastaa tietojärjestelmiensä toimivuudesta ja tietoturvan asianmukaisesta järjestämisestä.</p>
<p>7.	Palveluntoimittajan oikeudet</p>
<p>Palveluntoimittajalla on oikeus rajoittaa verkkopalvelujen käyttöaikaa tai keskeyttää tilapäisesti palveluiden toimittaminen tarpeellisten huolto- ja ylläpitotoimenpiteiden suorittamista varten tai muista näihin verrattavista syistä ilmoittamalla siitä asiakkailleen
verkkopalvelussa. Ilmoitus pyritään tekemään hyvissä ajoin etukäteen.</p>
<p>Palveluntoimittajalla on oikeus keskeyttää palveluiden toimittaminen sekä jättää sille annettu toimeksianto täyttämättä, mikäli Tilaaja ei noudata palveluja koskevia sopimusehtoja, suorita sopimuksen mukaisia maksuja tai milloin Palveluntoimittajalla on perusteltua syytä epäillä, että palvelua käytetään lainvastaiseen toimintaan tai tavalla, joka saattaa aiheuttaa vahinkoa tai vahingonvaaran Palveluntoimittajalle, Tilaajalle tai sivulliselle.</p>
<p>Palveluntoimittaja päättää Käyttäjälle tarjottavan tuen saatavuudesta ja sen palvelutasosta sekä veloitusperusteesta. 
Digisten Oy:llä on oikeus tallettaa arkistoida asiakaspalvelupuhelut ja -viestit.</p>
<p>Palveluntoimittajalla on oikeus käyttää Tilaajan nimeä ja Tilaajan kotisivuilla esitettyä graafista yritystunnusta (logoa) Digisten Oy:n markkinointimateriaalissa, esimerkkinä ohjelmistoa käyttävästä yrityksestä.</p>
<p>Palveluntoimittajalla on tähän sopimukseen perustuen oikeus kohdistaa Käyttäjän organisaatiolle lähetettävää Digistenin palveluita koskevaa viestintää.</p>
<p>
8.	Hinta ja hinnoitteluperiaatteet</p>
<p>Etunti-verkkopalvelun hinta määräytyy Tilaajan kanssa sovitun palvelukokonaisuuden mukaisesti. Palvelun hinta määräytyy palveluun kirjattujen työtuntien kokonaismäärän mukaisesti. Ohjelman eri osien yksikköhinnat on esitetty erillisessä Toimittajan voimassa olevassa hinnastossa. Maksuperusteen jakso on kalenterikuukausi.</p>
<p>Hinnat ja toimituksen sisältö yksilöidään Tilausvahvistuksessa.
Mahdolliset lisätilaukset hinnoitellaan Toimittajan kulloinkin voimassa olevan hinnaston mukaisesti.</p>
<p>Palvelun toimittamisen aloituspäivä määritellään Tilausvahvistuksessa. Palvelu laskutetaan kalenteri-kuukausittain, aikaisintaan laskutuskuukautta seuraavan kuukauden ensimmäisenä pankkipäivänä. Laskutus aloitetaan Etunti-verkkopalvelun käyttöönotosta tai viimeistään 3kk:n kuluttua sopimuksen allekirjoittamisesta. Hinnanmuutoksista ilmoitetaan neljä (4) kuukautta ennen uuden hinnan voimaantuloa.</p>
<p>
9.	Palvelun toimittaminen</p>
<p>Palvelu on toimitettu, kun Toimittaja ilmoittaa palvelun olevan käytettävissä. Palvelu katsotaan myös toimitetuksi, kun asiakas on saanut järjestelmän käyttöön oikeuttavat tunnukset.</p>
<p>10.	Sopimuksen muuttaminen</p>
<p>Sopimuksen muuttaminen tai siirtäminen on mahdollista vain kummankin sopijaosapuolen hyväksymällä muutossopimuksella.</p>
<p></p>
<p>11.	Palveluntoimittajan vahingonkorvaus-velvollisuus</p>
<p>Palveluntoimittajan vahingonkorvausvastuu palvelussa mahdollisesti ilmenevistä häiriöistä on rajoitettu
välittömiin vahinkoihin, jotka ilmenevät ylimääräisinä tai hukkaan menneinä etäyhteyskustannuksina tai niihin verrattavina Asiakkaalle aiheutuneina lisäkustannuksina. Palveluntoimittaja ei siten vastaa Asiakkaalle palvelussa esiintyvien häiriöiden takia mahdollisesti aiheutuvasta taloudellisesta vahingosta kuten esimerkiksi tulon, voiton tai tuoton menetyksestä, muissa sopimussuhteissa aiheutuvista virheistä tai viivästyksistä, kolmannen osapuolen vaatimuksista taikka muusta
Palveluntoimittajan kannalta vaikeasti ennakoitavasta vahingosta.</p>
<p>Palveluntoimittaja ei 
myöskään vastaa vahingosta, joka on aiheutunut Asiakkaan antamien tietojen puutteellisuudesta tai virheellisyydestä.</p>
<p>Palveluntoimittaja ei vastaa vahingosta, joka aiheutuu ylivoimaisesta esteestä, kuten sodasta tai lakosta;
Palveluntoimittajasta riippumattomasta häiriöstä tiedon siirrossa, atk-järjestelmissä, sähköisessä viestinnässä, tai sähkön saannissa; tai
vastaavasta syystä johtuvasta Palveluntoimittajan toiminnan kohtuuttomasta vaikeutumisesta.</p>
<p>Ylivoimaisen esteen johdosta Palveluntoimittaja on oikeutettu keskeyttämään palvelun toimittaminen toistaiseksi ja
jatkamaan palvelua vasta kun este poistuu. Tällöin Palveluntoimittaja on velvollinen ilmoittamaan esteestä Asiakkaalle mahdollisimman pian.</p>
<p>Palveluntoimittaja ei vastaa yhteistyökumppaniensa, kolmansien osapuolten taikka myyjän alustalla palvelujaan tarjoavien osapuolten toiminnasta, palveluista tai tuotteista, eikä niiden asiakkaalle aiheuttamista välittömistä tai välillisistä vahingoista.</p>
<p>12.	Huomautukset, vaatimukset</p>
<p>Mahdolliset palvelua, sopimusta tai toimeksiantoja koskevat huomautukset tai vaatimukset Toimittajalle tulee tehdä kirjallisesti viipymättä, kuitenkin viimeistään 14 kalenteripäivän kuluessa tapahtumapäivästä. Mikäli huomautusta ei tehdä tämän ajan kuluessa, katsotaan Asiakkaan hyväksyneen suoritetut toimenpiteet.</p>
<p>13.	Palveluihin liittyvät immateriaalioikeudet</p>
<p>Omistusoikeus ja kaikki Immateriaalioikeudet Verkkopalveluun, mukaan lukien palveluun lisensioidut sovellukset (”Mobiili”) ovat Toimittajan omaisuutta.</p>
<p>Sopimuksen mukaisten hintojen ja käyttömaksujen suorittamista vastaan Tilaaja saa ei-siirrettävissä olevan oikeuden käyttää palvelua omassa sisäisessä käytössään palvelun suunnitellussa käyttötarkoituksessa. Kohdeympäristöksi on rajattu Tilaajan oma henkilökunta.</p>
<p>14.	Salassapito</p>
<p>Sopijapuolet pitävät toisiltaan saamansa luottamukselliseksi tai liikesalaisuudeksi katsottavan aineiston salassa eivätkä käytä tietoja muihin kuin sopimuksen mukaisiin tarkoituksiin.
Salassapitovelvollisuus ei koske tietoa, joka on julkista tai yleisesti saatavilla tai jonka sopijapuoli on saanut laillisesti haltuunsa muuten kuin toiselta sopijaosapuolelta.</p>
<p>Tilaaja ei saa antaa sopimuksen sisällöstä tai hinnoista tietoa ulkopuolisille ilman Toimittajan lupaa.</p>
<p>Mikäli sopijaosapuoli on tahallisesti tai törkeällä huolimattomuudella luovuttanut salassa pidettäviä tietoja kolmannelle osapuolelle ja tästä sopimusrikkomuksesta aiheutuneen vahingon voidaan katsoa olevan olennainen, on sopimuksen rikkoja velvollinen suorittamaan kertakaikkisena sopimussakkona toiselle osapuolelle 10.000 euroa.
Sopimussakon määrä ei rajoita loukatun osapuolen oikeutta vaatia vahingonkorvausta, mikäli vahinko ylittää sopimussakon määrän.</p>
<p>15.	Erimielisyydet ja niiden ratkaiseminen</p>
<p>Sopimuksesta mahdollisesti syntyvät erimielisyydet ratkaistaan ensisijaisesti osapuolten välisin neuvotteluin. Mikäli neuvotteluissa ei päästä osapuolia tyydyttävään lopputulokseen, erimielisyydet jätetään ratkaistavaksi vastaajan kotipaikan yleiseen tuomioistuimeen. Mikäli sopijaosapuolet sopivat niin sopivat, asia voidaan jättää välimiesmenettelyn ratkaistavaksi.</p>
<p>16.	Sovellettava laki</p>
<p>Tähän sopimukseen sovelletaan yksinomaan Suomen lakia.</p>
<p>17.	Sopimuksen voimassaolo</p>
<p>Sopimus on voimassa toistaiseksi. Sopimuksen irtisanomisaika on kolme (3) kuukautta.
	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary hyvaksyn" data-dismiss="modal">Hyväksyn</button>
      </div>
    </div>

  </div>
</div>



</div>

<?php if(isset($_GET['aloita']) and isset($_GET['kirjautumistunnus']) and isset($_GET['email']) and $_GET['aloita'] == "ok") : ?>
<script>
 window.dataLayer = window.dataLayer || [];
 window.dataLayer.push({
 'event' : 'aloita',
 'tilanne' : 'OK',
 'kirjautumistunnus' : '<?=$_GET["kirjautumistunnus"]?>',
 'email' : '<?=$_GET["email"]?>',
 });
</script>
<?php endif; ?>


<?php if(isset($_GET['aloita']) and isset($_GET['kirjautumistunnus']) and isset($_GET['email']) and $_GET['aloita'] == "error") : ?>
<script>
 window.dataLayer = window.dataLayer || [];
 window.dataLayer.push({
 'event' : 'aloita',
 'tilanne' : 'ERROR',
 'kirjautumistunnus' : '<?=$_GET["kirjautumistunnus"]?>',
 'email' : '<?=$_GET["email"]?>',
 });
</script>
<?php endif; ?>


<script type="text/javascript">
$(document).ready(function(){

  localStorage.clear();

  $(".hyvaksyn").click(function(){
  	localStorage.setItem('kayttoehdot_luettu', true);
  });

  $("#kehdot").click(function(e){
	e.preventDefault();
  });

  $(".submit").click(function(e){


	e.preventDefault();

	if (grecaptcha.getResponse() == "" && location.hostname !== "etunti.local"){
		alert("Varmistaa, ettet ole robotti");
		return false;
	}

	if( $('#sahkoposti').val() !== $('#sahkoposti2').val() ){
		alert('Tarkasta sähköpostiosoite.');
		return false;
	}

	if(!localStorage.getItem('kayttoehdot_luettu')){
		alert('Lue ensin käyttöehdot.');
		return false;
	}

	$('#aloita-lomake').submit();
  });


/*
  $("#yrityksen_nimi").keyup(function(e){
	$.ajax({
	  url: '#',
	  data: { keyup_kirjautumistunnus : $(this).val() },
	  type:'POST',
	  success:function(data){
  		$('#kirjautumistunnus').val(data);
	  },
	  error:function(data){
  		console.log(data); 
	  }
  	});
  });
*/

});
</script>

