<?php
ini_set('max_execution_time', 900);
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
  <?php echo Yii::t('main', 'Toteutuneen ja suunnitellun työn erot'); ?>
  <?php if(isset(Yii::app()->session['from']) and isset(Yii::app()->session['to'])) : ?>
    <?php echo date("d.m.Y",strtotime($from)).'-'.date("d.m.Y",strtotime($to)); ?>
  <?php endif; ?>
 </td>
 </tr>
</table>

<br>



<div class="tb">
<table class="table table-bordered">
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Pvm.'); ?></th>
    <th><?php echo Yii::t('main', 'Osoite'); ?></th>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Suunnittelut'); ?></th>
    <th><?php echo Yii::t('main', 'Hyväksytyt'); ?></th>
    <th><?php echo Yii::t('main', 'Ero'); ?></th>
  </tr>
  </thead>
  <tbody>

  <?php
  $suunnittellutYht	= 0;
  $hyvaksytytYht	= 0;
  $eroYht		= 0;
  $suunnittellut 	= 0;
  $hyvaksytyt 		= 0;
  $ero			= 0;
  $osoite 		= '';
  $tyontekija 		= '';
  $eroLaskin		= 0;
  $eroLaskinYht		= 0;
  ?>
  <?php foreach($dataAll as $pvm => $tids) : ?>
  <?php $date = date("Y-m-d", strtotime($pvm)) ?>
  	<?php foreach($tids as $nimi => $tids) : ?>
	  	<?php foreach($tids as $tid => $arr) : ?>
			<?php
				$hyvaksytyt = (isset($hyv_tyotunnit_all[$date][$tid]))?$hyv_tyotunnit_all[$date][$tid]:0;
			?>
		  	<?php foreach($arr as $osoite => $kesto) : ?>
			<?php
			if($kesto > $hyvaksytyt) {
				$eroLaskin = $kesto-$hyvaksytyt;
				$ero = '<b style="color:red">-'.$this->sprint($eroLaskin).'</b>';
				$eroLaskinYht	-= $eroLaskin;
			} elseif($kesto < $hyvaksytyt) {
				$eroLaskin = $hyvaksytyt-$kesto;
				$ero = '<b style="color:green">+'.$this->sprint($eroLaskin).'</b>';
				$eroLaskinYht	+= $eroLaskin;
			} elseif($kesto == $hyvaksytyt and $_POST['is_kaikki'] == 'erot') {
				$ero = '<b>00:00</b>';
				continue;
			} elseif($kesto == $hyvaksytyt and $_POST['is_kaikki'] != 'erot') {
				$ero = '<b>00:00</b>';
			}
			$suunnittellutYht += $kesto;
			$hyvaksytytYht += $hyvaksytyt;
			?>
			<tr>
				<td><?php echo date("d.m.Y", strtotime($pvm)); ?></td>
				<td style="width:27%"><?=$osoite?></td>
				<td style="width:27%"><?=$nimi?></td>
				<td align="center"><?=$this->num($kesto)?></td>
				<td align="center"><?=$this->num($hyvaksytyt)?></td>
				<td align="center"><?php echo $ero; ?></td>
			</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
  <?php endforeach; ?>
  <?php 
		$yhtEro = $this->sprint($eroLaskinYht);
	if($eroLaskinYht < 0)
		$yhtEro = '-'.$this->sprint(str_replace("-", "", $eroLaskinYht));
  ?>

  </tbody>
  <tfoot>
  <tr>
    <td></td>
    <td></td>
    <td><?php echo Yii::t('main', 'Yhteensä'); ?></td>
    <td align="center"><?php echo $this->sprint($suunnittellutYht); ?></td>
    <td align="center"><?php echo $this->sprint($hyvaksytytYht); ?></td>
    <td align="center"><?php echo $yhtEro; ?></td>
  </tr>
  </tfoot>
</table>
</div>


</div>
