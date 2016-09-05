<?php

   $asetukset = Asetukset::model()->findbypk(1);
   $body = '

	      <h4>'.date("d.m.Y", strtotime($_POST['pvm'])).'</h4>

   ';
   $body .= '<input type="hidden" value="'.date("d.m.Y", strtotime($_POST['pvm'])).'" id="valinnuPvm">';

// kuva
function kuva($vapaaTid){
   $filename = "../../img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg";
   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg"))
   $kuva = '<img src="'.$filename.'" class="img-thumbnail">';
   else
   $kuva = '<img src="../../img/tekijat/noname.jpg" class="img-thumbnail">';
   return $kuva;
}
// kuva

function tr($vapaaTid, $pvm, $sta, $sto, $kuva)
{
   	$return = '
	<tr class="link ajaanClick" tid="'.$vapaaTid.'" pvm="'.date("d.m.Y",strtotime($_POST['pvm'])).'" alku="'.$sta.'" loppu="'.$sto.'">
	<td class="col-sm-2">'.$kuva.'</td>
	<td>'.$sta.' - '.$sto.'</td>
	</tr>';
	return $return;
}



   $tekijat = $this->pmvCal($_POST['pvm'])[1];
   foreach($tekijat as $key=>$value)
   {
	$sta = "08:00";
	$sto = "18:00";

   	$body .= '<table class="table table-hover">';
	$body .= tr($key, $value[0], $sta, $sto, kuva($key));
   	$body .= '</table>';
   }


   	$body = '<h1>Sivu ei toimi</h1>';

   echo json_encode($body);
?>

