<?php

?>

<!-- Style -->
<?php if(isset($_POST['luoPrintSivu'])) : ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css">
<script>
window.onload = function () {
    window.print();
}
</script>
<?php else : ?>
<link rel="stylesheet" type="text/css" href="css/raportit_table.css">
<?php endif; ?>
<!-- Style -->


<div style="100%">

<table id="ylataulu" class="table">
 <tr><td valign="right" style="width:20%">
  <?php echo Yii::t('main', 'Lomat ja poissaolot'); ?>
  <?php if(isset($_POST['from']) and isset($_POST['to'])) : ?>
    <?php echo date("d.m.Y",strtotime($_POST['from'])).'-'.date("d.m.Y",strtotime($_POST['to'])); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>



<div class="tb">
<table class="table table-bordered table-striped">
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Lomaan nimike'); ?></th>
    <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>
  <tbody>

  <?php
  $kplYht	= 0;
  ?>
  <?php foreach($dataAll as $pvm => $tids) : ?>
  <?php $date = date("Y-m-d", strtotime($pvm)) ?>
  	<?php foreach($tids as $nimi => $tids) : ?>
	  	<?php foreach($tids as $tid => $arr) : ?>
		  	<?php foreach($arr as $tyoajanlaatu => $kpl) : ?>
			<?php
				$expl = explode("/",$tyoajanlaatu);
			  	$kplYht	+= $kpl;
			?>
			<tr>
			<td style="text-align:left"><?=$nimi?></td>
			<td style="text-align:left; color:<?=$expl[1]?>"><?php echo $expl[0]; ?></td>
			<td><?=$kpl?></td>
			</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
  <tr>
    <td></td>
    <td><?php echo Yii::t('main', 'Yhteensä'); ?></td>
    <td><?php echo $kplYht; ?></td>
  </tr>
  </tfoot>
</table>
</div>


</div>
