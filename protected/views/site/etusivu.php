<?php

function num($val){
    if($val > 0)
	return  number_format((float)$val/3600, 2, '.', '');
}

?>
<legend>
<h1><?php echo Yii::t('main','ETUSIVU'); ?></h1>
</legend>

<br>

<div class="row">
  <div class="col-sm-4">
  <fieldset>
	<legend><?php echo Yii::t('main','TYÖT TÄNÄÄN'); ?></legend>
<?php 
	$a1 = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=1",array('select'=>'id'));
	$a = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3",array('select'=>'id'));	
	$m = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=2",array('select'=>'id'));
	$l = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=10",array('select'=>'id'));


        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 370,
                'height' => 200,
                'htmlOptions' => array(),

                'labels' => array(Yii::t('main','Aloitettu työt'),Yii::t('main','Lopetut työt'),Yii::t('main','Matkat'),Yii::t('main','Lounaat')),
                'datasets' => array(
                    array(
                        "fillColor" => "#cecece",
                        "strokeColor" => "#8cc152",
                        "data" => array((int)count($a1),(int)count($a),(int)count($m),(int)count($l))
                    )       
                ),
                'options' => array()
            )
        ); 
?>
  </fieldset>
  </div>
</div>

<br>

<div class="row">
  <div class="col-sm-12">
  <fieldset>
	<legend><?php echo Yii::t('main','TYÖVUOROSUUNNITTELU KOHTEEN MÄÄRÄ'); ?></legend>
<?php 
	$criteria = new CDbCriteria;
	$criteria->group="tid";	
	$criteria->condition=" kohde!='' AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() ";
	$t = Tyovuoroot::model()->findAll($criteria);

	$nimi = array();
	$k = array();
	foreach($t as $tekija)
	{
		$tnimi = Tyontekijat::model()->findbypk($tekija->tid);
		$kohteet = Tyovuoroot::model()->findAll(" tid='".$tekija->tid."' and DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() ");
		$k[] = (int)count($kohteet);
		$nimi[] = $tnimi->tekijan_nimi;
	}

        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => $nimi,
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(100,100,220,1)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => $k
                    )       
                ),
                'options' => array()
            )
        ); 
?>
  </fieldset>
  </div>
</div>

<br>

<div class="row">
  <div class="col-sm-12">
  <fieldset>
	<legend><?php echo Yii::t('main','TYÖVUOROSUUNNITTELU TUNNIN MÄÄRÄ'); ?></legend>
<?php 
	$criteria = new CDbCriteria;
	$criteria->group="tid";	
	$criteria->condition=" 
		kohde!='' AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
	";
	$t = Tyovuoroot::model()->findAll($criteria);

	$nimi = array();
	$k = array();
	foreach($t as $tekija)
	{
		$tnimi = Tyontekijat::model()->findbypk($tekija->tid);

		$criteria = new CDbCriteria;
		$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%H:%i'), 
			DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%H:%i')))) as l_tunnit
		";
		$criteria->condition=" 
			tid='".$tekija->tid."' AND kohde!='' 
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
		";
		$tunnit = Tyovuoroot::model()->find($criteria);
		$k[] = (int)num($tunnit->l_tunnit);
		$nimi[] = $tnimi->tekijan_nimi;
	}

        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => $nimi,
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(100,100,220,1)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => $k
                    )       
                ),
                'options' => array()
            )
        ); 
?>
  </fieldset>
  </div>
</div>
