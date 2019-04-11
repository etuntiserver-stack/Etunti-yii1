<?php

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p20"> <i class="fa fa-gear"></i> <?php echo Yii::t('main', 'Käyttöoikeudet'); ?> </h2>



            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">




<?php
 $array = array
 (
	'asetukset'=>array(0=>null,1=>null,2=>'Asetukset ja käyttöoikeudet',3=>null),
	'tyoryhmat'=>array(4=>'Työryhmät järjestelmänvalvoja'),
	'pudotusvalikot'=>array(4=>'Pudotusvalikot'),
	'yrityksentiedot'=>array(0=>null,1=>null,2=>'Muokaa yrityksentiedot',3=>null),
	'henkilotunnukset'=>array(0=>null,1=>null,2=>'Henkilötunnukset',3=>null),
	'ryhmat'=>array(0=>'Käyttöoikeusryhmät taulu',1=>'Luo käyttöoikeusryhmä',2=>'Muokaa käyttöoikeusryhmiä',3=>null),
	'asiakkaat'=>array(0=>'Asiakkaat taulu',1=>'Luo asiakas',2=>'Muokaa asiakas',3=>'Poista asiakas',4=>'Massamuokkaus asiakas'),
	'kohteet'=>array(0=>'Kohteet taulu',1=>'Luo kohde',2=>'Muokaa kohde',3=>'Poista kohde',4=>'Massamuokkaus kohde'),
	'tyontekijat'=>array(0=>'Työntekijät taulu',1=>'Luo työntekijä',2=>'Muokaa työntekijä',3=>'Poista työntekijä',4=>'Työntekijät laaja'),
	'viestinta'=>array(0=>'Viestinta taulu',1=>'Luo viesti',2=>'Muokaa viesti',3=>'Poista viesti'),
	'administrators'=>array(0=>'Järjestelmänvalvoja taulu',1=>'Luo järjestelmänvalvoja',2=>'Muokaa järjestelmänvalvoja',3=>'Poista järjestelmänvalvoja'),
	'mallitiedostot'=>array(0=>null,1=>null,2=>'Mallitiedostot',3=>null),
	'lasku'=>array(0=>'Laskutus taulu',1=>'Luo lasku',2=>'Muokaa lasku',3=>'Poista lasku'),
 );

	$tas = '';
	if(isset(Yii::app()->user->adminPaketti))
	$tas = explode(",",Yii::app()->user->adminPaketti);


	if(isset(Yii::app()->user->adminID) and in_array('2',$tas))
	{
		$tyovuorot = array(0=>null,1=>'Luo työvuoro',2=>'Muokaa työvuoro',3=>'Poista työvuoro');
		$array['tyovuorot'] = $tyovuorot;
	}
	if(isset(Yii::app()->user->adminID) and in_array('3',$tas))
	{
		$tuotteet = array(0=>'Tuotteet ja palvelut',1=>'Luo tuote',2=>'Muoka tuote',3=>'Poista tuote');
		$array['onlineTuotteet'] = $tuotteet;
	}
	if(isset(Yii::app()->user->adminID) and in_array('5',$tas))
	{
		$yhteystiedot = array(0=>'Yhteystiedot taulu',1=>'Luo yhteystieto',2=>'Muokaa yhteystieto',3=>'Poista yhteystieto');
		$array['yhteystiedot'] = $yhteystiedot;
	}

?>

<?php echo CHtml::link('Muokkaa ryhmät', array('oikeusRyhmat/index'), array('class' => 'btn btn-primary myBgColors')); ?>

<table class="table table-bordered table-striped oikeudet">

 <tr>
  <th>Toiminto</th>

<?php
$otsiko = $this->oikeudenOtsikot();
foreach($otsiko as $v)
{
  echo '<th>'.$v.'</th>';
}
?>
 </tr>

<?php
$r = $this->oikeudenOtsikot();
foreach($array as $k=>$v)
{
   foreach($v as $k1=>$v1)
   {
	if($v1 != null)
	{
		echo '<tr><td>'.$v1.'</td>';
   		foreach($r as $ryhma=>$value)
	  	echo '<td><input type="checkbox" class="check" id="'.$k.'_'.$k1.'_'.$ryhma.'"></td>';
		echo '</tr>';
	}
   }

	echo '
	 <tr>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	  <td></td>
	 </tr>
	';
}
?>
</table>
<br>
<span class="btn btn-primary myBgColors tallennaOikeudet">Tallenna oikeudet</span>



                 </div>
                </div>
              </div>
            </div>

        </div>

<?php
$asetukset = Asetukset::model()->findbypk(1);
if(isset($asetukset->oikeudet)){
	$encode = $asetukset->oikeudet;
	echo '<textarea id="checkedMuisti" class="form-control" rows="6" style="display:none">'.$encode.'</textarea>'; //style="display:none"
}
?>

<script type="text/javascript">
$(document).ready(function(){

if($('#checkedMuisti').val())
{
var checkedMuisti = JSON.parse($('#checkedMuisti').val());
$.each(checkedMuisti, function( index, value ) {
  $('#'+value).attr('checked',true);
});
}

$(".tallennaOikeudet").click(function(){

    var searchIDs = $(".oikeudet input:checkbox:checked").map(function(){
      return $(this).attr('id');
    }).get(); 

    console.log(searchIDs);

        $.ajax({
           url: 'oikeudet',
	   type:'POST',
	   data: { "oikeudet" : searchIDs },
           success: function(data){
		console.log(data);
		window.location.reload();

           }
        });

});



});
</script>










