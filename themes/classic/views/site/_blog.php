<?php
/* @var $this BlogController */
/* @var $data Blog */
?>

<div class="view small">
 <p>
	<h1 class="title-subtitle text-left">
	<span><?php echo CHtml::encode($data->otsikko); ?>, <?php echo CHtml::encode(date("d.m.Y", strtotime($data->time))); ?></span>
	</h1>

	<?php echo str_replace("\n", "<br>", $data->teksti); ?>
	<br />

	<b><?php echo CHtml::encode($data->luoja); ?></b>
 </p>
</div>
<hr>
