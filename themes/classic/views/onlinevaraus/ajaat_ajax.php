<?php

   $body = '<center><h3>'.date("d.m.Y", strtotime($_POST['pvm'])).'</h3></center>';
   $body .= '<input type="hidden" value="'.date("d.m.Y", strtotime($_POST['pvm'])).'" id="valinnuPvm">';


   // <-- Tarkista taysin vapaana
   $criteria=new CDbCriteria;
   $criteria->condition = "online_varauksen_valmina=1 ";
   $tyontekijat = Tyontekijat::model()->findAll($criteria);

   $taysinVapaana = 'ei';
   $vapaaTid = '';
   foreach($tyontekijat as $t)
   {
   	$criteria=new CDbCriteria;
   	$criteria->condition = " 
		pvm='".date("d.m.Y", strtotime($_POST['pvm']))."' 
		AND tid='".$t->id."'
	";
	$tyovuorot = Tyovuoroot::model()->find($criteria);
   	if(!isset($tyovuorot->id))
   	{
   		$taysinVapaana = 'on';
   		$vapaaTid = $t->id;
		break;
   	}
   }
   // Tarkista taysin vapaana -->





   $sumTunti = (float)$_SESSION['onlinevaraus']['sumTunti'];
   $sumTuntiMin = $sumTunti*60;

   $start = "08:00";
   $stop = date("H:i",strtotime($start." +".$sumTuntiMin." minutes"));
   $period = 10/$sumTunti;

if($taysinVapaana == 'on' and !empty($vapaaTid))
{
  for ($i = 1; $i <= $period; $i++) {

   $int = 0;
   if(!isset($sta) and !isset($sto))
   {
	$sta = $start;
	$sto = $stop;
   }

   $filename = "../../img/tekijat/".$_SESSION['domain']."/".$vapaaTid.".jpg";
   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg"))
   $kuva = '<img src="'.$filename.'" class="img-thumbnail">';
   else
   $kuva = '<img src="../../img/tekijat/noname.jpg" class="img-thumbnail">';


   $body .= '<div class="row">';
     $body .= '<div class="link col-sm-10 col-sm-offset-1 ajaanClick"  tid="'.$vapaaTid.'" pvm="'.date("d.m.Y",strtotime($_POST['pvm'])).'" alku="'.$sta.'" loppu="'.$sto.'">';
     	$body .= '
	    <div class="">
		<div class="col-sm-3">'.$kuva.'</div>
		<div class="col-sm-9">'.$sta.' - '.$sto.'</div>
	    </div>
		';
     $body .= '</div>';
   $body .= '</div><br>';

   $int += $sumTuntiMin;
   $sta = date("H:i",strtotime($sta." +$int minutes"));
   $sto = date("H:i",strtotime($sto." +$int minutes"));

  }
}



if($taysinVapaana == 'ei' and empty($vapaaTid))
{

   $filename = "../../img/tekijat/".$_SESSION['domain']."/".$vapaaTid.".jpg";
   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg"))
   $kuva = '<img src="'.$filename.'" class="img-thumbnail">';
   else
   $kuva = '<img src="../../img/tekijat/noname.jpg" class="img-thumbnail">';

   $pmvCal = $this->pmvCal($_POST['pvm'])[1];
   $body .= '<center><b>Vapaat vuorot</b></center>';
   foreach($pmvCal as $k=>$v)
   {
	$ex = explode("//",$k);
   $body .= '<div class="row">';
     $body .= '<div class="link col-sm-10 col-sm-offset-1 ajaanClick" tid="'.$ex[2].'" pvm="'.date("d.m.Y",strtotime($_POST['pvm'])).'" alku="'.$ex[0].'" loppu="'.$ex[1].'">';
	$body .= '
	    <div class="">
		<div class="col-sm-3">'.$kuva.'</div>
		<div class="col-sm-9">'.$ex[0].' - '.$ex[1].'</div>
	    </div>
	';
     $body .= '</div>';
   $body .= '</div><br>';
	
   }
  
}

   echo json_encode($body);
?>

