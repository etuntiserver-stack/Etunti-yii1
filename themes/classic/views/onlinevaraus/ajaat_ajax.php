<?php


   $body = '
	<div class="boxes-info">
	      <h4>'.date("d.m.Y", strtotime($_POST['pvm'])).'</h4>
	   <div class = "panel-body">

   ';
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


function tr($vapaaTid, $pvm, $sta, $sto, $kuva)
{
   	$return = '
	<tr class="link ajaanClick" tid="'.$vapaaTid.'" pvm="'.date("d.m.Y",strtotime($_POST['pvm'])).'" alku="'.$sta.'" loppu="'.$sto.'">
	<td class="col-sm-3">'.$kuva.'</td>
	<td>'.$sta.' - '.$sto.'</td>
	</tr>';
	return $return;
}


   $body .= '<table class="table table-hover">';

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

   $body .= tr($vapaaTid, $_POST['pvm'], $sta, $sto, $kuva);

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
   foreach($pmvCal as $k=>$v)
   {
	$ex = explode("//",$k);
	$body .= tr($ex[2], $_POST['pvm'], $ex[0], $ex[1], $kuva);
	
   }
  
}

   $body .= '</table>';

   $body .= '</div></div>';

   echo json_encode($body);
?>

