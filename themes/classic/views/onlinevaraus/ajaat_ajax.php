<?php

   $body = '';
/*
if(isset($_SESSION['ajaanReika']))
{
   $body .= '<label>Ajaan reikoja</label><br>';
   foreach($_SESSION['ajaanReika'] as $k=>$v)
   {
	$ex = explode("//",$k);
	$body .= $ex[0].' <span class="btn btn-success">'.$ex[1].' - '.$ex[2].'</span> - Työntekijä: '.$v.'<br>';
	
   }
}
*/

   $body .= $_POST['pvm'];
   $zapas = 1; // 1 tunti
   $sumTunti = (float)$_SESSION['onlinevaraus']['sumTunti']+$zapas;

   $start = "08:00";
   $stop = date("H:i",strtotime("+".strtotime($sumTunti)." hour"));;

for ($i = $sumTunti; $i <= 6; $i++) {

   if(!isset($sta) and !isset($sto))
   {
	$sta = $start;
	$sto = $stop;
   }

   $body .= '<div class="row">';
     $body .= '<div class="col-sm-4 col-sm-offset-2">';
     	$body .= '<button class="btn btn-success btn-block">'.$sta.' '.$sto.'</button>';
     $body .= '</div>';
   $body .= '</div>';

   $sta = date("H:i",strtotime("$start +$i hour"));
   $sto = date("H:i",strtotime("$stop +$i hour"));

}

   echo json_encode($body);
?>

