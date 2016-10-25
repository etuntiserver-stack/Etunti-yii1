<?php
/* @var $this BlogController */
/* @var $data Blog */
//'style'=>'width: 200px'
?>

<div class="row small">
 <div class="col-md-12">

	<div class="section">
	 <div class="col-sm-2">
	  <?php echo CHtml::encode(date("d.m.Y", strtotime($data->time))); ?>
	 </div>

	 <div class="col-sm-8 col-sm-offset-1">
	  <span class="link text-primary" data-toggle="collapse" data-target="#open_<?php echo $data->id; ?>">
		<span class="caret"></span> <?php echo $data->otsikko; ?>
	  </span>

	  <br>
	  <div class="collapse" id="open_<?php echo $data->id; ?>">
	  <br>
	  <p>
	  	<span><?php echo str_replace("\n", "<br>", $data->teksti); ?></span>
	  	<p><b><?php echo CHtml::encode($data->luoja); ?></b></p>
	  </p>

	  </div>

	 </div>
	</div>

 </div>
</div>

