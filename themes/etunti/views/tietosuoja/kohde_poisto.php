<?php

?>

<?php 
   $sum_mobiili = 0;
   $sum_avaimet = 0;
   $sum_tv	= 0;
   $sum_onlinevaraus = 0;
   $sum_laskut 	= 0;
   $sum_lh	= 0;
   $sum_toistuvatv = 0;
   $sum_tk	= 0;
   $sum_tk_rivi = 0;



	$mob = Mobile::model()->findAll(" kohdenID='".$kohde->id."' ");
	$tot = Toteutuneet::model()->findAll(" kohdenID='".$kohde->id."' ");
	$sum_mobiili += count($mob)+count($tot);

	$avaimet = Avaimet::model()->findAll(" kohde='".$kohde->id."' ");
	$sum_avaimet += count($avaimet);
	$ov = Onlinevaraus::model()->findAll(" kohde_id='".$kohde->id."' ");
	$sum_onlinevaraus += count($ov);
	$tv = Tyovuoroot::model()->findAll(" kohde='".$kohde->id."' ");
	$sum_tv += count($tv);
	$toistuvatv = ToistuvatTyovuorot::model()->findAll(" kohde='".$kohde->id."' ");
	$sum_toistuvatv += count($toistuvatv);
	$tk = Tyonkuvaus::model()->findAll(" kohde_id='".$kohde->id."' ");

        if( isset($_POST['action']) and $_POST['action'] == 'delete'){
		Mobile::model()->deleteAll(" kohdenID='".$kohde->id."' ");
		Toteutuneet::model()->deleteAll(" kohdenID='".$kohde->id."' ");
		Avaimet::model()->deleteAll(" kohde='".$kohde->id."' ");
		Onlinevaraus::model()->deleteAll(" kohde_id='".$kohde->id."' ");
		Tyovuoroot::model()->deleteAll(" kohde='".$kohde->id."' ");
		ToistuvatTyovuorot::model()->deleteAll(" kohde='".$kohde->id."' ");
		Tyonkuvaus::model()->deleteAll(" kohde_id='".$kohde->id."' ");
	}

	foreach($tk as $rivi){
	  $tkrivi = TyonkuvausRivit::model()->findAll(" tyonkuvaus_id='".$rivi->id."' ");
          if( isset($_POST['action']) and $_POST['action'] == 'delete'){
		TyonkuvausRivit::model()->deleteAll(" tyonkuvaus_id='".$rivi->id."' ");
	  }
	  $sum_tk_rivi += count($tkrivi);
	}
	$sum_tk += count($tk);


   if( isset($_POST['action']) and $_POST['action'] == 'delete'){
   	Kohteet::model()->findByPk($kohde->id)->delete();
   }

?>
<table class="table table-bordered">
<tr><td>Työvuorot</td><td class="text-danger"><?=$sum_tv?> riveja.</td></tr>
<tr><td>Toistuva työvuorot</td><td class="text-danger"><?=$sum_toistuvatv?> riveja.</td></tr>
<tr><td>Mobiili</td><td class="text-danger"><?=$sum_mobiili?> riveja.</td></tr>
<tr><td>Avaimet</td><td class="text-danger"><?=$sum_avaimet?> riveja.</td></tr>
<tr><td>Onlinevaraus</td><td class="text-danger"><?=$sum_onlinevaraus?> riveja.</td></tr>
<tr><td>Työnkuvaukset</td><td class="text-danger"><?=$sum_tk?> riveja.</td></tr>
</table>
<!--/Kohteista-->
