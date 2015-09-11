<?php


	$sv= Sivexkuitti::model()->findbypk($id);
	if(!empty($sv->tietoja)) 
	  $tietoja = $sv->tietoja."\n"; 
	else 
	  $tietoja = "<perus>".$sv->kohde_kannasta."//".$sv->aloitan."//".$sv->loppui."</perus>";

	$sv->tietoja=$tietoja.Yii::app()->user->nimi." (".date("d.m.Y H:i")."):\n".$tilanne.", Vanha-".$sv->kohde_kannasta.", ".$sv->aloitan.", ".$sv->loppui.". Uusi-".$uusikohde.", ".$uusialoitus.", ".$uusilopetus;
	$sv->save();

//print_r($sv);
?>
