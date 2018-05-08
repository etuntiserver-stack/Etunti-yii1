<?php

?>

<?php 
   $sum_mobiili = 0;
   $sum_avaimet = 0;
   $sum_vlomat = 0;
   $sum_tv	= 0;
   $sum_laskut 	= 0;
   $sum_lh	= 0;
   $sum_toistuvatv = 0;
   $sum_tk	= 0;
   $sum_tk_rivi = 0;



	$mob = Mobile::model()->findAll(" tid='".$tt->id."' ");
	$tot = Toteutuneet::model()->findAll(" tid='".$tt->id."' ");
	$sum_mobiili = count($mob)+count($tot);

	$avaimet = Avaimet::model()->findAll(" tid='".$tt->id."' ");
	$sum_avaimet = count($avaimet);
	$tv = Tyovuoroot::model()->findAll(" tid='".$tt->id."' ");
	$sum_tv = count($tv);

	$vuosilomat = Vuosilomat::model()->findAll(" tid='".$tt->id."' ");
	$sum_vlomat = count($vuosilomat);

        if( isset($_POST['action']) and $_POST['action'] == 'delete'){
		Mobile::model()->deleteAll(" tid='".$tt->id."' ");
		Toteutuneet::model()->deleteAll(" tid='".$tt->id."' ");
		Avaimet::model()->deleteAll(" tid='".$tt->id."' ");
		Tyovuoroot::model()->deleteAll(" tid='".$tt->id."' ");
		Vuosilomat::model()->deleteAll(" tid='".$tt->id."' ");
	}


   if( isset($_POST['action']) and $_POST['action'] == 'delete'){
   	Tyontekijat::model()->findByPk($tt->id)->delete();
   }

?>
<table class="table table-bordered">
<tr><td>Työvuorot</td><td class="text-danger"><?=$sum_tv?> riveja.</td></tr>
<tr><td>Toistuva työvuorot</td><td class="text-danger"><?=$sum_toistuvatv?> riveja.</td></tr>
<tr><td>Mobiili</td><td class="text-danger"><?=$sum_mobiili?> riveja.</td></tr>
<tr><td>Avaimet</td><td class="text-danger"><?=$sum_avaimet?> riveja.</td></tr>
<tr><td>Vuosilomat</td><td class="text-danger"><?=$sum_vlomat?> riveja.</td></tr>
</table>
<!--/Kohteista-->
