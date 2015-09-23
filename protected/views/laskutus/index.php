<?php
/* @var $this LaskutusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Laskutuses',
);

$this->menu=array(
	array('label'=>'Create Laskutus', 'url'=>array('create')),
	array('label'=>'Manage Laskutus', 'url'=>array('admin')),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'LASKUTUS'); ?> <i class="glyphicon glyphicon-time"></i></h1>
</legend>

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.2.min.js"></script>
<?php 

   $avoinColor = '#ffa500';
   $lahetettyColor = '#357942';
   $maksettuColor = '#0086cd';

   $page =  ''; 
   if(isset($_GET['selaa']))
   $page =  $_GET['selaa']; 

?>


<script type="text/javascript">
$(document).ready(function(){



$('#iframe1').ready(function () {
    //$('#loadingMessage').html("<img src=laskutus/img/wait.gif>");
});
$('#iframe1').load(function () {
    $('#loadingMessage').css('display', 'none');
});

 
$('#iframe2').ready(function () {
    //$('#loadingMessage2').html("<img src=laskutus/img/wait.gif>");
});
$('#iframe2').load(function () {
    $('#loadingMessage2').css('display', 'none');
});


$("#malli").click(function(){
	window.location.href="index.php?lasku_new=true&laskunID=1";
});


$(".asetukset").click(function(){
	var forThis = $(this).attr("id");
	var to = forThis.split("_");
	var page = "<?php echo $page; ?>";

	$("#nextA_"+to[1]).html("<BR><div class='changeTilanne' for='hyvityslasku'>Luo hyvityslasku</div><div class='changeTilanne' for='muistutus'>Luo muistutuslasku</div>").css({"white-space": "nowrap", "z-index": "2"}).toggle();

  $(".changeTilanne").click(function(){
        var r=confirm("Oletko varmaa?")
        if (r){
	var forThis = $(this).attr("for");
        $.ajax({
           url: "laskutus/for_ajax.php",
	   type:'POST',
	   data: { "forThis" : forThis, "id" : to[1] },
           success: function(html){
		if(!html)
		window.location.href="index.php?lasku_new=true&selaa=true";
		else
		alert(html)
           }
        });
	}
  });

});


  $(".tilanne").click(function(){
	var forThis = $(this).attr("id");
	var to = forThis.split("_");
	var page = "<?php echo $page; ?>";

	$("#next_"+to[1]).html("<div class='changeTilanne'>avoin</div><div class='changeTilanne'>lähetetty</div><div class='changeTilanne'>maksettu</div>").css({"position":"absolute","margin-left":"40px","margin-top":"-20px","padding":"10px","background":"white","border":"1px #ddd solid"}).toggle();

  $(".changeTilanne").click(function(){
	var thisText = $(this).text();

        $.ajax({
           url: "laskutus/change_tilanne.php",
	   type:'POST',
	   data: { "thisText" : thisText, "id" : to[1] },
           success: function(html){
	    	$('#to_'+to[1]).html(html).css({"text-align" : "center"});
		$("#next_"+to[1]).hide();
           }
        });

	if(thisText == 'avoin')
	$($(this).closest("td")).css({"color" : "<?php echo $avoinColor; ?>"});

	if(thisText == 'lähetetty')
	$($(this).closest("td")).css({"color" : "<?php echo $lahetettyColor; ?>"});

	if(thisText == 'maksettu')
	$($(this).closest("td")).css({"color" : "<?php echo $maksettuColor; ?>"});

	if(page != 'true')
	$($(this).closest("tr")).hide("slow");
  });

  });


function iframeLoad(){

$('#iframe2').ready(function () {
    //$('#loadingMessage2').html("<img src=img/wait.gif>").show();
});
$('#iframe2').load(function () {
    $('#loadingMessage2').css('display', 'none');
});

}

  $(".kaikkiRaportit td form").on('submit',function(){
	iframeLoad();
  });


  $("#raportti").change(function(){
	$("#nimikellaForm").submit();
  });

  $("#alv").change(function(){
	$("#alvForm").submit();
  });

  $("#yritys").change(function(){
	$("#yritysForm").submit();
  });


$("#paivaysForm input[type=date]").on('change', function() {

	var thisName = $(this).attr("name");
	var thisVal = $(this).val();

        $.ajax({
           url: "laskutus/from_to.php",
	   type:'POST',
	   data: { "thisName" : thisName, "thisVal" : thisVal },
           success: function(html){
		$(".reload").fadeIn('slow');
           }
        });
});



});

$(document).ready(function () {
  $(".onlyDigits").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 46 || e.which > 57 )) {
        $(this).next("span").html("Vain numerot tai pisten merkki").show().fadeOut("slow").css({"position":"absolute","color":"red","padding":"3px 7px","background":"#fff"});
               return false;
    }
   });

});
</script>


<style>
.reload { display: none; position: absolute; margin-left: 355px;margin-top: -40px; }

#result{
	width:650px;left:50%;height: 500px; overflow: auto;
	margin-left:-325px;
	position:fixed;top:2%;
	color: #333;
	text-shadow: 1px 1px 0px #fff;
	background-color: #fff;
	display: none;
	padding: 20px;
	border: 2px #999 solid;
	z-index: 99999;
	box-shadow: 0 0 2px 2px #999;
	border-radius: 5px;
}

#iframe1, #iframe2 {
  margin: 0;
  padding: 0;
  border: 0;
  width: 510px;
  height: 720px;
}
table #rivit td{
    	border-collapse: collapse;
    	border-spacing: 0;
	padding: 1px 2px;
	margin: 0;
}

.tilanne, .changeTilanne{
	cursor: pointer;
	//font-weight: bold;
}
.changeTilanne:hover{
	background: #ccc;
}
TD { padding: 2px; }

#uusiRivi span:hover, .Taulukko span:hover, .paina td:hover, .for_tkoodi:hover{
	text-decoration: underline;
	cursor: pointer;
}
</style>


		<ul>
                    <li><a href="#" onClick="window.location.href='index.php?lasku_new=true'">Pää laskun sivu</a></li>
                    <li><a href="#" onClick="window.location.href='index.php?lasku_new=true&uusilasku=true'" class="dropdown-toggle" data-toggle="dropdown">Luo lasku</a></li>
                    <li><a href="#" onClick="window.location.href='index.php?lasku_new=true&selaa=true'">Selaa laskuja</a></li>
                    <li><a href="#" onClick="window.location.href='index.php?lasku_new=true&tuote_hal=true'">Tuotteet / Palvelut hallinta</a></li>
                    <li><a href="#" onClick="window.location.href='index.php?lasku_new=true&uusituote=true'">Uusi tuote / palvelu</a></li>
		</ul>


    <!--<script src="js/modernizr.js"></script>-->



<div class="col-sm-6">
<?php 
	echo $this->renderPartial("//laskutus/taulukko_erapaiva",array("from"=>"2015-01-01","to"=>"2015-01-01"));
	echo $this->renderPartial("//laskutus/taulukko_sahkopostille",array("from"=>"2015-01-01","to"=>"2015-01-01"));
	echo $this->renderPartial("//laskutus/taulukko_postille",array("from"=>"2015-01-01","to"=>"2015-01-01"));
/*
    <BR>
    <p><?php include "laskutus/sivut.php"; ?></p>




	<?php include "laskutus/taulukko_erapaiva.php"; ?>
  <BR>
	<?php include "laskutus/taulukko_sahkopostille.php"; ?>
  <BR>
	<?php include "laskutus/taulukko_postille.php"; ?>

*/
?>
</div>
<div class="col-sm-6 pull-right">

   <h2>Raportit</h2>

    <?php //echo formAika_laskutus(); ?>
    <a href="index.php?lasku_new=true" class="reload"><img src="laskutus/img/reload.png" height="40"></a>

  <TABLE class="kaikkiRaportit">
  <TR><TD>
   <form action="laskutus/raport_pdf.php" id="nimikellaForm" target="my_iframe" method="POST">
   <input type="hidden" name="method" value="nimikella">
     <label>Nimike</label><BR>
     <select name="tuote" id="raportti" class="select form-control">
     <option value="">---Valitse---</option>
     <?php
	  $lr = LaskunRivit::model()->findAll(array("select"=>"nimike","group"=>"nimike","order"=>"nimike"));
	  foreach($lr as $l){
		echo '<option value="'.$l->nimike.'">'.$l->nimike.'</option>';
	  }
     ?>
     </select>
   </form>

   </TD><TD>
   <form action="laskutus/raport_pdf.php" id="alvForm" target="my_iframe" method="POST">
   <input type="hidden" name="method" value="alv">
     <label>ALV</label><BR>
     <select name="alv" id="alv" class="select form-control">
     <option value="">---Valitse---</option>
     <?php
	  $lr = LaskunRivit::model()->findAll(array("select"=>"alv","group"=>"alv","order"=>"alv"));
	  foreach($lr as $l){
		echo '<option value="'.$l->alv.'">'.$l->alv.' %</option>';
	  }
     ?>
     </select>
   </form>

   </TD><TD>
   <form action="laskutus/raport_pdf.php" id="yritysForm" target="my_iframe" method="POST">
   <input type="hidden" name="method" value="yritys">
     <label>Yritys ( saaja )</label><BR>
     <select name="yritys" id="yritys" class="select form-control">
     <option value="">---Valitse---</option>
     <?php
	/*
	  $sql = mysql_query("SELECT yritys.id,yritys.yritys FROM yritys 
	  LEFT JOIN laskut ON laskut.yid = yritys.id
	  GROUP BY yritys ORDER BY yritys ") or die (mysql_error());
	  while($r = mysql_fetch_array($sql)){
		echo '<option value="'.$r[id].'">'.$r[yritys].'</option>';
	  }
	*/
     ?>
     </select>
   </form>

   </TD></TR>
   </TABLE>
   <div id="loadingMessage2" style="display: none"></div> 
   <BR>
   <iframe class="well" name="my_iframe"  id="iframe2" frameBorder="0" ></iframe>



</div>


</div>
