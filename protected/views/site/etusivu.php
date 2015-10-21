<?php

?>
<legend>
<h1><?php echo Yii::t('main','ETUSIVU'); ?></h1>
</legend>

<br>

<div class="row">
  <div class="col-sm-3">
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
                'width' => 350,
                'height' => 210,
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
