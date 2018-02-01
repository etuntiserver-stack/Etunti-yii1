<?php

 $months=array(
	'01'=>'Tammikuu',
	'02'=>'Helmikuu',
	'03'=>'Maaliskuu',
	'04'=>'Huhtikuu',
	'05'=>'Toukokuu',
	'06'=>'Kesäkuu',
	'07'=>'Heinäkuu',
	'08'=>'Elokuu',
	'09'=>'Syyskuu',
	10=>'Lokakuu',
	11=>'Marraskuu',
	12=>'Joulukuu'
	);


 $asetukset = Asetukset::model()->findByPk(1);
 $body = '';

 if(isset($_SESSION['onlinevaraus']['sumTunti']) and $_SESSION['onlinevaraus']['sumTunti'] > 0){

     ($asetukset->onlinevaraus_viikonlopput == 0)? $numOfWeek = 7 : $numOfWeek = 5; 
     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');
     $body .= "<div id='ensimmainen_kk' class='text-center'>";
     $body .= "<h3><i class='fa fa-angle-double-right pull-right link next_kk'></i> ".$months[$month]." $year</h3>";
     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);
     $body .= "</div>";

     $month = date('m',strtotime("last day of +1 month"));
     $year = date('Y',strtotime("last day of +1 month"));
     $body .= "<div id='toinen_kk' class='text-center hidden'>";
     $body .= "<h3><i class='fa fa-angle-double-left pull-left link prev_kk'></i> ".$months[$month]." $year</h3>";
     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);
     $body .= "</div>";

 }

     echo json_encode($body);
?>
