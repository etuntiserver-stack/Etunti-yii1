<?php
/* @var $this BlogController */
/* @var $data Blog */
//'style'=>'width: 200px'
?>

<div class="row small">
 <div class="col-md-12">
 <p>



     <div class="">


	<div class="row">
	 <div class="col-sm-2">
	  <?php echo CHtml::encode(date("d.m.Y", strtotime($data->time))); ?>
	 </div>

	 <div class="col-sm-8 col-sm-offset-1">
	  <span><?php echo str_replace("\n", "<br>", $data->teksti); ?></span>
	  <p><b><?php echo CHtml::encode($data->luoja); ?></b></p>
	 </div>
	</div>


    </div>



 </p>
 </div>
</div>

