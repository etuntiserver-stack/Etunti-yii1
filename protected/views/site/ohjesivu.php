<?php

?>

<legend>
<h1><?php echo Yii::t('main','OHJESIVU'); ?></h1>
</legend>

<br>

<?php
	$o = AsetuksetForAll::model()->findbypk(1);
?>

<div class="row">
  <div class="col-sm-12">
	<?php echo $o->ohjesivu; ?>
  </div>
</div>


