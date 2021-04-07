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

	if(isset($_SESSION['onlinevaraus']['sumTunti']) and $_SESSION['onlinevaraus']['sumTunti'] > 0)
	{

		($asetukset->onlinevaraus_viikonlopput == 0)? $numOfWeek = 7 : $numOfWeek = 5; 
		$dateArray = array();
		$dateComponents = getdate();

		$month = date('m');
		$year = date('Y');

		if(!isset($_SESSION['onlinevaraus']['kalenteri_year_month']))
			$_SESSION['onlinevaraus']['kalenteri_year_month'] = $year."_".$month;

		$month_next = date('m',strtotime("last day of +1 month"));
		$year_next = date('Y',strtotime("last day of +1 month"));

		$month_kolmas = date('m',strtotime("last day of +2 month"));
		$year_kolmas = date('Y',strtotime("last day of +2 month"));


		if(isset($_SESSION['onlinevaraus']['kalenteri_year_month']) and $_SESSION['onlinevaraus']['kalenteri_year_month'] == $year."_".$month)
		{
			$body .= "<div id='ensimmainen_kk' class='text-center'>";
			$body .= "<h3><i class='fa fa-angle-double-right pull-right link month_muutos' kalenteri_year_month='".$year_next."_".$month_next."'></i> ".$months[$month]." $year</h3>";
			$body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);
			$body .= "</div>";
		}
		if(isset($_SESSION['onlinevaraus']['kalenteri_year_month']) and $_SESSION['onlinevaraus']['kalenteri_year_month'] == $year_next."_".$month_next)
		{
			$body .= "<div id='toinen_kk' class='text-center'>";
			$body .= "<h3><i class='fa fa-angle-double-right pull-right link month_muutos' kalenteri_year_month='".$year_kolmas."_".$month_kolmas."'></i><i class='fa fa-angle-double-left pull-left link month_muutos' kalenteri_year_month='".$year."_".$month."'></i> ".$months[$month_next]." $year_next</h3>";
			$body .= $this->build_calendar($month_next, $year_next, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);
			$body .= "</div>";
		}
		if(isset($_SESSION['onlinevaraus']['kalenteri_year_month']) and $_SESSION['onlinevaraus']['kalenteri_year_month'] == $year_kolmas."_".$month_kolmas)
		{
			$body .= "<div id='kolmas_kk' class='text-center'>";
			$body .= "<h3><i class='fa fa-angle-double-left pull-left link month_muutos' kalenteri_year_month='".$year_next."_".$month_next."'></i> ".$months[$month_kolmas]." $year_kolmas</h3>";
			$body .= $this->build_calendar($month_kolmas, $year_kolmas, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);
			$body .= "</div>";
		}

	}

	echo json_encode($body);
?>
