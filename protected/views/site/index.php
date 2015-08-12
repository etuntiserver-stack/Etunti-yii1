<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

?>

<?php
	if(isset(Yii::app()->user->domain))
	echo Yii::app()->user->domain;
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
