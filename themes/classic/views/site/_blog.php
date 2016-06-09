<?php
/* @var $this BlogController */
/* @var $data Blog */
//'style'=>'width: 200px'
?>

<div class="row small">
 <div class="col-md-12">
 <p>



     <div class="boxes-info">

	<div class="pull-right"><?php echo CHtml::encode(date("d.m.Y", strtotime($data->time))); ?></div>
	<h1 class="title-subtitle text-left">
	<span><?php echo CHtml::encode($data->otsikko); ?></span>
	</h1>


	<div class="row">
	 <div class="col-sm-3">
	  <?php echo CHtml::image(Yii::app()->request->baseUrl.'/tiedostot/etusivu/'.$data->kuva,"kuva",array('class'=>'img-thumbnail')); ?>
	 </div>

	 <div class="col-sm-7 col-sm-offset-1">
	  <span><?php echo str_replace("\n", "<br>", $data->teksti); ?></span>
	  <p><b><?php echo CHtml::encode($data->luoja); ?></b></p>
	 </div>
	</div>


    </div>



 </p>
 </div>
</div>

