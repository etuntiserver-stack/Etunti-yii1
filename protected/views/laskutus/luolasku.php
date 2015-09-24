<?php
//uusilasku.php


if(empty($laskunumero)){

$l = Laskutus::model()->find(array("order"=>"id DESC"));
$laskunumero = $l->id+1;
$method = 'new';
$tilanne = 'avoin';
//$allShow = 'style="display: none"';
$yid = '';
} else {

$l = Laskutus::model()->find(" id = '".$laskunumero."' ");
$method = 'update';
$tilanne = $l->tilanne;
//$allShow = '';
$yid = $l->yid;
}

/*
if(empty($l->as_nro])){
$sql = mysql_query("SELECT id FROM asiakkaat ORDER by id DESC LIMIT 1");
$row = mysql_fetch_array($sql);
$l->as_nro = $l->id+1;
}
*/

$l->as_nro = 000;

function Viitenumero($string) {
  $string = strval($string);
  $paino = array(7, 3, 1);
  $summa = 0;
  for($i=strlen($string)-1, $j=0; $i>=0; $i--,$j++){
    $summa += (int) $string[$i] * (int) $paino[$j%3];
  }
  $tarkiste = (10-($summa%10))%10;
  return $string.$tarkiste;
}

	$viitenumero = Viitenumero($l->as_nro."0".$laskunumero);


	if($method == 'new') echo '<H1>Luo lasku</H1>';
	if($method == 'update') echo '<H1>Muokka lasku</H1>';

?>
<div class="row col-sm-12">
<?php 
/*
 <div class="col-sm-3">
	<h2>Saaja: 
	<select id="saaja" class="form-control">
	<?php

	if($yid){
	$sql1 = mysql_query("SELECT id,iban,yritys FROM yritys WHERE id = '".$yid."' ");
	$saaja1 = mysql_fetch_array($sql1);
	echo '<option value="'.$saaja1[id].'">'.$saaja1[yritys].': '.$saaja1[iban].'</option>';
	}

	$sql = mysql_query("SELECT id,iban,yritys FROM yritys");
	if(!$yid)
	echo '<option value="">---Valitse---</option>';

	while($saaja = mysql_fetch_array($sql)){
	echo '<option value="'.$saaja[id].'">'.$saaja[yritys].': '.$saaja[iban].'</option>';
	}
	?>
	</select>
	</h2>
 </div>
	<BR>
*/
?>

<div id="allShow" class="col-sm-12">

	<form action="index.php?lasku_new=true&selaa=true&laskunumero=<?php echo $laskunumero; ?>" id="laskuForm" method="POST">
	<input type="hidden" name="method" value="<?php echo $method; ?>">
	<input type="hidden" name="count" id="count">
	<input type="hidden" name="tilanne" value="<?php echo $tilanne; ?>">
	<input type="hidden" name="id" value="<?php echo $l->id; ?>">
	<input type="hidden" name="yid" id="yid">
	<input type="hidden" name="saaja_iban" id="saaja_iban">
	<input type="hidden" name="saaja_virtualkoodi" id="saaja_virtualkoodi">

	<BR>
<div id="luolasku">
<p><H2>Asiakas</H2></p>

  <div class="row">
    <div class="col-sm-3">
	<label>Kanta-asiakas:</label>
	<BR>
	<div id="Asiakkaankanta"></div>
    </div>
  </div>

<TABLE>
 <TR>
  <TD valign="top" width="50%">
   <TABLE>
     <TR>
	<TD><label>Tyyppi:</label></TD>
<?php 
	if($l->tyyppi == 'yritys') $Y_checked = 'checked';
	if($l->tyyppi == 'henkilo') $H_checked = 'checked';
	if($l->tyyppi == '') $Y_checked = 'checked';
?>

	<TD>
	<input type="radio" name="tyyppi" value="yritys" <?php echo $Y_checked; ?>> <label>Yritys</label> 
	<input type="radio" name="tyyppi" value="henkilo" <?php echo $H_checked; ?>> <label>Yksityishenkilö</label>
	</TD>
     </TR>
     <TR class="for_yritys" style="display: none">
	<TD><label>Yritys:</label></TD>
	<TD><input class="form-control" type="text" name="yritys" id="yritys" value="<?php echo $l->yritys; ?>"></TD>
     </TR>
     <TR class="for_yritys" style="display: none">
	<TD><label>Y-Tunnus:</label></TD>
	<TD><input class="form-control" type="text" name="y_tunnus" id="y_tunnus" value="<?php echo $l->y_tunnus; ?>"></TD>
     </TR>
     <TR class="for_henkilo" style="display: none">
	<TD><label>Nimi:</label></TD>
	<TD><input class="form-control" type="text" name="nimi" id="nimi" value="<?php echo $l->nimi; ?>"></TD>
     </TR>
     <TR>
	<TD><label>As. numero:</label></TD>
	<TD><input class="form-control" type="text" name="as_nro" id="as_nro" value="<?php echo $l->as_nro; ?>" readonly></TD>
     </TR>
     <TR>
	<TD><label>Osoite:</label></TD>
	<TD><input class="form-control" type="text" name="osoite" id="osoite" value="<?php echo $l->osoite; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Postinumero:</label></TD>
	<TD><input class="form-control" type="text" name="postinumero" id="postinumero" value="<?php echo $l->postinumero; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Toimipaikka:</label></TD>
	<TD><input class="form-control" type="text" name="toimipaikka" id="toimipaikka" value="<?php echo $l->toimipaikka; ?>"></TD>
     </TR>
   </TABLE>
  </TD>
  <TD valign="top" width="50%">
   <TABLE>
     <TR>
	<TD><label>Laskutus:</label></TD>

<?php 
	if($l->laskutus == 'posti') $P_checked = 'checked';
	if($l->laskutus == 'verkkolasku') $V_checked = 'checked';
	if($l->laskutus == 'sahkoposti') $S_checked = 'checked';
	if($l->laskutus == '') $P_checked = 'checked';
?>

	<TD>
	<input type="radio" name="laskutus" value="posti" <?php echo $P_checked; ?>> <label>Posti</label> 
	<input type="radio" name="laskutus" value="verkkolasku" <?php echo $V_checked; ?>> <label>Verkkolasku</label>
	<input type="radio" name="laskutus" value="sahkoposti" <?php echo $S_checked; ?>> <label>Sähköposti</label>
	</TD>
     </TR>
     <TR class="for_sahkoposti" style="display: none">
	<TD><label>Sähköposti:</label></TD>
	<TD><input class="form-control" type="text" name="sahkoposti" id="sahkoposti" value="<?php echo $l->sahkoposti; ?>"></TD>
     </TR>
     <TR class="for_verkkolasku" style="display: none">
	<TD><label>Verkkolaskuosoite:</label></TD>
	<TD><input class="form-control" type="text" name="verkkolaskuosoite" id="verkkolaskuosoite" value="<?php echo $l->verkkolaskuosoite; ?>"></TD>
     </TR>
     <TR class="for_verkkolasku" style="display: none">
	<TD><label>Välittäjän tunnus:</label></TD>
	<TD><input class="form-control" type="text" name="v_tunnus" id="v_tunnus" value="<?php echo $l->v_tunnus; ?>"></TD>
     </TR>
     <TR class="for_yritys" style="display: none">
	<TD><label>Yhteyshenkilö:</label></TD>
	<TD><input class="form-control" type="text" name="yhteyshenkilo" id="yhteyshenkilo" value="<?php echo $l->yhteyshenkilo; ?>"></TD>
     </TR>
     <TR class="for_henkilo" style="display: none">
	<TD><label>Nimitarkenne:</label></TD>
	<TD><input class="form-control" type="text" name="nimitarkenne" id="nimitarkenne" value="<?php echo $l->nimitarkenne; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Puhelin:</label></TD>
	<TD><input class="form-control" type="text" name="puhelin" id="puhelin" value="<?php echo $l->puhelin; ?>"></TD>
     </TR>
   </TABLE>
  </TD>
 </TR>

 <TR id="ToimitusosoiteToinen" style="display: none">
  <TD valign="top">
   <H2 style="text-align: left">Toimitusosoite</H2>
   <TABLE>
     <TR class="for_yritys" style="display: none">
	<TD><label>Yritys:</label></TD>
	<TD><input class="form-control" type="text" name="t_yritys" id="t_yritys" value="<?php echo $l->t_yritys; ?>"></TD>
     </TR>
     <TR class="for_yritys" style="display: none">
	<TD><label>Y-Tunnus:</label></TD>
	<TD><input class="form-control" type="text" name="t_y_tunnus" id="t_y_tunnus" value="<?php echo $l->t_y_tunnus; ?>"></TD>
     </TR>
     <TR class="for_henkilo" style="display: none">
	<TD><label>Nimi:</label></TD>
	<TD><input class="form-control" type="text" name="t_nimi" id="t_nimi" value="<?php echo $l->t_nimi; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Osoite:</label></TD>
	<TD><input class="form-control" type="text" name="t_osoite" id="t_osoite" value="<?php echo $l->t_osoite; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Postinumero:</label></TD>
	<TD><input class="form-control" type="text" name="t_postinumero" id="t_postinumero" value="<?php echo $l->t_postinumero; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Toimipaikka:</label></TD>
	<TD><input class="form-control" type="text" name="t_toimipaikka" id="t_toimipaikka" value="<?php echo $l->t_toimipaikka; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Puhelin:</label></TD>
	<TD><input class="form-control" type="text" name="t_puhelin" id="puhelin" value="<?php echo $l->puhelin; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Sähköposti:</label></TD>
	<TD><input class="form-control" type="text" name="t_sahkoposti" id="t_sahkoposti" value="<?php echo $l->t_sahkoposti; ?>"></TD>
     </TR>
   </TABLE>
  </TD>
  <TD valign="top">

  </TD>
 </TR>
</TABLE>



	<label>Toimitusosoite on eri kuin laskutusosoite:</label>
	<input type="radio" name="toimitusosoite" value="0" id="toimitusosoite_0" checked> <label>Ei</label> 
	<input type="radio" name="toimitusosoite" value="1" id="toimitusosoite_1" > <label>Kyllä</label>

</div>







<BR>

<div id="luolasku">
<p><H2>Laskun tiedot</H2></p>
<TABLE>
 <TR>
  <TD valign="top" width="50%">
   <TABLE>
     <TR>
	<TD><label>Laskunumero:</label></TD>
	<TD><input class="form-control" type="text" name="laskunumero" id="laskunumero" value="<?php echo $laskunumero; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Päiväys:</label></TD>
	<TD><input class="form-control" type="date" name="paivays" id="paivays"  value="<?php echo date("Y-m-d"); ?>"></TD>
     </TR>
     <TR>
	<TD><label>Eräpäivä:</label></TD>
	<TD><input class="form-control" type="date" name="erapaiva" id="erapaiva" value="<?php echo $l->erapaiva; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Toimituspäivä:</label></TD>
	<TD><input class="form-control" type="date" name="toimituspaiva" id="toimituspaiva" value="<?php echo $l->toimituspaiva; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Maksuehto:</label></TD>
	<TD>
		<select type="text" name="maksuehto" id="maksuehto" class="select form-control">
	<?php
	if($l->maksuehto){
		echo '<option value="'.$l->maksuehto.'">'.$l->maksuehto.' pv</option>';
	}
	?>

			<option value="14">14 pv</option>
		<?php
		for ($i = 1; $i <= 100; $i++) {
		    echo '<option value='.$i.'>'.$i.' pv</option>';
		}
		?>
		</select>
	</TD>
     </TR>
   </TABLE>
  </TD>
  <TD valign="top" width="50%">
   <TABLE>
     <TR>
	<TD><label>Viitenumero:</label></TD>
	<TD><input class="form-control" type="text" name="viitenumero" id="viitenumero" value="<?php echo $viitenumero; ?>"></TD>
     </TR>
     <TR>
	<TD><label>Viivästyskorko:</label></TD>
	<TD>
		<select type="text" name="viivastyskorko" id="viivastyskorko" class="select form-control">

	<?php
	if($l->viivastyskorko){
		echo '<option value="'.$l->viivastyskorko.'">'.$l->viivastyskorko.' %</option>';
	}
	?>

			<option value="8">8 %</option>
		<?php
		for ($i = 0.5; $i <= 15; $i++) {
		$i2 = $i-0.5;
		    echo '<option value='.$i2.'>'.$i2.' %</option>';
		    echo '<option value='.$i.'>'.$i.' %</option>';
		}
		?>
		</select>
	</TD>
     </TR>
   </TABLE>
  </TD>
 </TR>
</TABLE>
</div>

<BR>

<div class="row">
<div class="col-sm-12">

<p><H2>Laskurivit</H2></p>

   <TABLE id="rivit">
     <TR>
	<TH></TH>
	<TH>Tuote</TH>
	<TH>Nimike</TH>
	<TH>Kpl</TH>
	<TH>Yksikkö</TH>
	<TH>Hinta</TH>
	<TH>ALV-kanta %</TH>
	<TH>ALV</TH>
	<TH>Ale %</TH>
	<TH>Veroton</TH>
	<TH>Yhteensä</TH>
     </TR>
    
     <tbody>
<?php
if(isset($_GET[laskunumero]) and !empty($_GET[laskunumero])){


	$sql = mysql_query("SELECT * FROM laskun_rivit WHERE lid = '".$laskunumero."' ");

	while($rivit = mysql_fetch_array($sql)){
	$num = $rivit[rivi];

	echo '
     <TR>
	<TD class="poista">x</TD>
	<TD><input type="text" size="1" name="rivi&&tkoodi__'.$num.'" id="tkoodi_'.$num.'" class="for_tkoodi form-control" value="'.$rivit[tkoodi].'"></TD>
	<TD><input type="text" name="rivi&&nimike__'.$num.'" id="nimike_'.$num.'" value="'.$rivit[nimike].'" class="form-control"></TD>
	<TD><input type="text" size="5" name="rivi&&kpl__'.$num.'" id="kpl_'.$num.'" value="1" class="onlyDigits form-control" value="'.$rivit[kpl].'"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="rivi&&yksikko__'.$num.'" id="yksikko_'.$num.'" class="form-control">
		<option value="'.$rivit[yksikko].'">'.$rivit[yksikko].'</option>
		'.yksikkot($rivit[yksikko]).'
		</select>
	</TD>
	<TD><input type="text" size="10"  name="rivi&&hinta__'.$num.'" id="hinta_'.$num.'" class="onlyDigits form-control" value="'.$rivit[hinta].'"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="rivi&&alv__'.$num.'" id="alv_'.$num.'" class="form-control">
		<option value="'.$rivit[alv].'">'.$rivit[alv].' %</option>
		'.alv($rivit[alv]).'
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" type="text" size="10" name="rivi&&hinta_alv__'.$num.'" id="hinta_alv_'.$num.'" value="'.$rivit[hinta_alv].'" readonly></TD>
	<TD><input type="text" name="rivi&&ale__'.$num.'" id="ale_'.$num.'" size="10" class="onlyDigits form-control" value="'.$rivit[ale].'"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" size="10" type="text" name="rivi&&veroton__'.$num.'" id="veroton_'.$num.'" value="'.$rivit[veroton].'" readonly></TD>
	<TD><input class="yhteensa_total form-control" size="10" type="text" name="rivi&&yhteensa_alv__'.$num.'" id="yhteensa_alv_'.$num.'" value="'.$rivit[yhteensa_alv].'" readonly></TD>
     </TR>
	';

	} 

} else {
	include "laskun_rivi.php";
}
?>

     <tbody>
     <tfoot>
     <TR>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="yhteensa_total_verot" id="yhteensa_total_verot" readonly></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="yhteensa_total_veroton" id="yhteensa_total_veroton" readonly></TD>
	<TD><input type="text" class="form-control" size="10" name="yhteensa_total" id="yhteensa_total" readonly></TD>
     </TR>
     </tfoot>
   </TABLE>
	
   <p><div id="uusiRivi" style="text-align: left"><span><b>Lisää tyhjä rivi</b></span></div></p>


</div>
</div>
	</form>
	<BR>
	<center><input type="button" id="tallenna" value="Tallenna" class="btn btn-lg btn-warning"></center>


</div><!--all-->

</div><!--row-->

	<div id="result"></div>




<script type="text/javascript">
$(document).ready(function(){

  $("#laskuForm").on('submit',function(e) {
    var paivays = $("#paivays").val();
    var osoite = $("#osoite").val();
    var postinumero = $("#postinumero").val();
    var toimipaikka = $("#postinumero").val();
    var viitenumero = $("#viitenumero").val();
    var erapaiva = $("#erapaiva").val();
    var saaja = $("#saaja").val();
 
    if(saaja === ''){
      $("#saaja").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(osoite === ''){
      $("#osoite").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(postinumero === ''){
      $("#postinumero").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(toimipaikka === ''){
      $("#toimipaikka").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(erapaiva === ''){
      $("#erapaiva").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(paivays === ''){
      $("#paivays").css({"border":"2px red solid"}).focus();
      return false;
    }
    if(viitenumero === ''){
      $("#viitenumero").css({"border":"2px red solid"}).focus();
      return false;
    }

  });


$("#tallenna").click(function() {
    var rowCount = $('table#rivit tr').length;
    $("#count").val(rowCount-2);

    $("#laskuForm").submit();
});

$("input:radio[name=tyyppi]:checked").each(function() {
    var value = $(this).val();
	$(".for_"+value).fadeIn('slow');

	kantaTyyppi(value);
});

    var toimitusosoite = "<?php echo $l->toimitusosoite; ?>";
    if(toimitusosoite){
    if(toimitusosoite == '1'){
	$("#ToimitusosoiteToinen").fadeIn('slow');
    }
    if(toimitusosoite == '0'){
	$("#ToimitusosoiteToinen").fadeOut('slow');
    }
    }

$("input:radio[name=toimitusosoite]").change(function() {
    var value = $(this).val();

    if(value == '0'){
	$("#ToimitusosoiteToinen").fadeOut('slow');
    }
    if(value == '1'){
	$("#ToimitusosoiteToinen").fadeIn('slow');
    }

	forYritysHenkilo();
});


	forYritysHenkilo();

function forYritysHenkilo(){

$("input:radio[name=tyyppi]:checked").each(function() {
    var tyyppi = $(this).val();
    if(tyyppi == 'yritys'){
	$(".for_yritys").fadeIn('slow');
	$(".for_henkilo").fadeOut('slow');
    }
    if(tyyppi == 'henkilo'){
	$(".for_henkilo").fadeIn('slow');
	$(".for_yritys").fadeOut('slow');
    }

});

}


$("input:radio[name=tyyppi]").change(function() {
    var value = $(this).val();

    if(value == 'yritys'){
	$(".for_yritys").fadeIn('slow');
	$(".for_henkilo").fadeOut('slow');
    }
    if(value == 'henkilo'){
	$(".for_henkilo").fadeIn('slow');
	$(".for_yritys").fadeOut('slow');
    }

	kantaTyyppi(value);
});





$("input:radio[name=laskutus]:checked").each(function() {
    var value = $(this).val();
	$(".for_"+value).fadeIn('slow');
});

$("input:radio[name=laskutus]").change(function() {
    var value = $(this).val();

    if(value == 'sahkoposti'){
	$(".for_sahkoposti").fadeIn('slow');
	$(".for_verkkolasku").fadeOut('slow');
    }
    if(value == 'posti'){
	$(".for_sahkoposti").fadeOut('slow');
	$(".for_verkkolasku").fadeOut('slow');
    }
    if(value == 'verkkolasku'){
	$(".for_sahkoposti").fadeOut('slow');
	$(".for_verkkolasku").fadeIn('slow');
    }

});

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    result.setMonth(result.getMonth() + 1);

		var date = ((result.getDate()) < 10 ? '0' : '') + (result.getDate());
		var month = ((result.getMonth()) < 10 ? '0' : '') + (result.getMonth());
		var year = result.getFullYear();

    return year+"-"+month+"-"+date;
}
/*
$("#saaja").change(function() {
    var thisVal = $(this).val();


        $.ajax({
           url: 'saaja_ajax.php',
           type: "POST",
           data: {"id" : thisVal},
           success: function(html){
               	var yritys = html.split("///");
		var paivays = new Date($("#paivays").val());	
		var add = addDays(paivays, parseInt(yritys[3]));
		$("#erapaiva").val(add);

		$("#yid").val(yritys[0]);
		$("#saaja_iban").val(yritys[1]);
		$("#saaja_virtualkoodi").val(yritys[2]);

    		var maksuehto = $('<option></option>').attr("value", yritys[3]).text(yritys[3]+" pv");
    		$("#maksuehto").html(maksuehto);

    		var viivastyskorko = $('<option></option>').attr("value", yritys[4]).text(yritys[4]+" %");
    		$("#viivastyskorko").html(viivastyskorko);

		$("#allShow").show('slow');
           }
        });
});
*/
$("#uusiRivi").click(function() {
    var rivi = $("#samaRivi").html();
    var rowCount = $('table#rivit tbody tr').length;
        $.ajax({
           url: 'laskutus/laskun_rivi.php',
           type: "POST",
           data: {"test" : "true", "num" : rowCount},
           success: function(html){
               $("table#rivit tbody tr").last().after(html);
           }
        });
});


function kantaTyyppi(val){
    var tyyppi = val;
        $.ajax({
           url: 'laskutus/asiakkaat_ajax.php',
           type: "POST",
           data: {"tyyppi" : tyyppi},
           success: function(html){
               $("#Asiakkaankanta").html(html);
           }
        });
}

    yhteensaTotal();


$("#rivit input").keyup(function() {
    var inputKenta = $(this).attr("name").split("__");



    var hinta_alv_0 = $("#hinta_"+inputKenta[1]).val();
    var alv = $("#alv_"+inputKenta[1]).val();
    var kpl = $("#kpl_"+inputKenta[1]).val();
    var ale = $("#ale_"+inputKenta[1]).val();


    var laske = parseFloat(((hinta_alv_0*kpl)/100*alv), 10);
    var laskeAleY = parseFloat((($("#yhteensa_alv_"+inputKenta[1]).val())/100*ale), 10);
    var laskeAleV = parseFloat((($("#veroton_"+inputKenta[1]).val())/100*ale), 10);

    var veroton = parseFloat(hinta_alv_0, 10)*kpl;
    yhteensa = laske+veroton;

    $("#hinta_alv_"+inputKenta[1]).val((laske).toFixed(2));
    $("#veroton_"+inputKenta[1]).val((veroton-laskeAleV).toFixed(2));
    $("#yhteensa_alv_"+inputKenta[1]).val((yhteensa-laskeAleY).toFixed(2));

    yhteensaTotal();

});


function yhteensaTotal(){
var sum = 0;
$('.yhteensa_total_verot').each(function(){
    sum += parseFloat(this.value);
    $('#yhteensa_total_verot').val(sum.toFixed(2));
});
var sum1 = 0;
$('.yhteensa_total_veroton').each(function(){
    sum1 += parseFloat(this.value);
    $('#yhteensa_total_veroton').val(sum1.toFixed(2));
});
var sum2 = 0;
$('.yhteensa_total').each(function(){
    sum2 += parseFloat(this.value);
    $('#yhteensa_total').val(sum2.toFixed(2));
});
}



});
</script>


<?php /*include "autolasketaan.php"; */?>


