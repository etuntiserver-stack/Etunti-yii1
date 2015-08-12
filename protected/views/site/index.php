<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

?>

<?php
	if(isset(Yii::app()->user->domain))
	echo Yii::app()->user->domain;

	//echo Yii::app()->session['domain'];
?>








<?php 
if(isset(Yii::app()->user->adminID))
{

for ($i = 1; $i <= 12; $i++) {


	$criteria = new CDbCriteria;
	$criteria->addCondition('YEAR(time) = '.date("Y"));
	$criteria->addCondition('MONTH(time) = '.$i);
	$result = Sivexkuitti::model()->findAll($criteria);
  

    $res = $result;
    $kk[$i] = count ( $res );
}

        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => array("Tammikuu","Helmikuu","Maaliskuu","Huhtikuu","Toukokuu","Kesäkuu","Heinäkuu","Elokuu","Syyskuu","Lokakuu","Marraskuu", "Joulukuu"),
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(100,100,220,1)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => array($kk[1],$kk[2],$kk[3],$kk[4],$kk[5],$kk[6],$kk[7],$kk[8],$kk[9],$kk[10],$kk[11],$kk[12])
                    )       
                ),
                'options' => array()
            )
        ); 
    
}
?>












<!--
<div class="row">
  <div class="col-sm-12">
    Tavallinen tekstti
    <h4>Isompi 4 tekstti</h4>
    <h3>Isompi 3 tekstti</h3>
    <h2>Isompi 2 tekstti</h2>
    <h1>Isompi 1 tekstti</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
    <p class="well">Bootstrap on sisällä</p>
  </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-4 alert alert-warning">Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä.</div>
    <div class="col-sm-4 col-sm-offset-1 alert alert-danger">Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä.</div>
    <div class="col-sm-4 alert alert-success">Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä.</div>
    <div class="col-sm-4 col-sm-offset-1 alert alert-info">Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä. Bootstrap on sisällä.</div>
</div>

<div class="row">
  <div class="col-sm-12">
    <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#suodattimet">Avaa minut <b class="caret"></b></button>
    <br><br>
      <div class="collapse" id="suodattimet">
        Tässä on piilottu sisältö. Tässä on piilottu sisältö. Tässä on piilottu sisältö. Tässä on piilottu sisältö
      </div>
  </div>
</div>

<h3>Linkit</h3>
<div class="row">
  <div class="col-sm-12">
    <?php echo CHtml::link('Linkki kirjautumiselle',array('user/login')); ?>
    <br>
    <a href="http://yle.fi" target="_blank">Linkki YLE.fi lle</a>
  </div>
</div>

<h3>Nappit</h3>
<div class="row">
  <div class="col-sm-12">
    <?php echo CHtml::button('Punainen nappi kirjautumiselle', array('class'=>'btn btn-danger', 'submit' => array('user/login'))); ?>
    <?php echo CHtml::button('Keltainen nappi kirjautumiselle', array('class'=>'btn btn-warning', 'submit' => array('user/login'))); ?>
    <?php echo CHtml::button('Vihreä nappi kirjautumiselle', array('class'=>'btn btn-success', 'submit' => array('user/login'))); ?>
    <?php echo CHtml::button('Sininen nappi kirjautumiselle', array('class'=>'btn btn-info', 'submit' => array('user/login'))); ?>
    <?php echo CHtml::button('Submit nappi kirjautumiselle', array('class'=>'btn btn-primary', 'submit' => array('user/login'))); ?>
  </div>
</div>
-->
