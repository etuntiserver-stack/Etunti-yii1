<?php
ini_set('memory_limit', '512M');
//print_r($_SESSION['muistin']);
//exit;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>
<?php echo CHtml::button(Yii::t('main', 'Lähetä'),array('class'=>'btn btn-lg btn-success','id'=>'lahetaTyovuoroja', 'style' => 'display: none; position:fixed; bottom: 0; right: 0')); ?>
<!-- Fixed Table -->
<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
<script>
	$(document).ready(function() {
		window.onload = function(event) { resizeDiv(); }
		//window.onresize = function(event) { resizeDiv(); }
			function resizeDiv() {
		    	    vpw = $(window).width()-100; 
		    	    vph = $(window).height()-100;
			    $('#parent').css({'height': vph + 'px', 'overflow-y' : 'hidden'});
			    $("#fixTable").tableHeadFixer({
				"left" : 1,
				"foot" : 1,
				'z-index': 0
		    	    }); 
			}
		});
</script>
<!-- Fixed Table -->

<input type="hidden" id="week" value="<?=$week?>">
<input type="hidden" id="year" value="<?=$year?>">

<!-- TV laatiko -->
<div id="showres" class="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
<!-- TV laatiko -->
<div id="yht_tv"></div>
<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th class="text-center" width="1"><?php echo CHtml::button(Yii::t('main', 'Valitse kaikki'),array('target'=>'_blank','class'=>'btn btn-default valitseKaikkiLahetettavaksi')); ?></th>
 <?php $f = date("d.m.Y", strtotime($from)); ?>
 <?php while (strtotime($f) <= strtotime($to)): ?>
 <?php
 if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6){
	$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
 }
 ?>
 <?php
  $columnDate = date("N/d.m",strtotime($f));
  $explColDate = explode("/",$columnDate);
 ?>
 <th class="text-center" style="z-index: 999;">
	<div class="laatiko_td"><b><?=$arrDate[$explColDate[0]]?></b>, <?=$explColDate[1]?></b></div>
 </th>
 <?php $f = date ("d.m.Y", strtotime("+1 day", strtotime($f))); ?>
 <?php endwhile; ?>
 </tr>
 </thead>
 <tbody>
 <!-- VARAUKSET -->
 <tr>
    <td class="laatiko_td text-center" style="z-index: 999; width: 50px">
	<span class="nimi">VARAUS</span>
    </td>
    <?php $f = date("d.m.Y", strtotime($from)); ?>
    <?php while (strtotime($f) <= strtotime($to)): ?>
    <?php
    if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6){
	$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
    }
    ?>
    <?php $did = date("Ymd", strtotime($f)); ?>
      <td>
	<div id="<?=$did.'_0'?>" class="latikkoAsetukset" pvm="<?=$f?>" tid="0">
		<?php if( isset($tv_arr[0][$f]) ): ?>
		<?php ksort($tv_arr[0][$f]); ?>
		<?php foreach($tv_arr[0][$f] as $k => $v): ?>
			<?php foreach($v as $v2): ?>
				<p><?=$v2?></p>
			<?php endforeach; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
      </td>
      <?php $f = date ("d.m.Y", strtotime("+1 day", strtotime($f))); ?>
    <?php endwhile; ?>
 </tr>
 <!-- VARAUKSET -->
 <?php $week_yhteensa = []; ?>
 <?php foreach($tt as $tid=>$item): ?>
 <tr>
    <td class="laatiko_td" style="z-index: 999; min-width: 150px">
	<div class="m15 text-center">
		<h5 class="nimi text-left">
			<input type="checkbox" class="lahetettava_checkbox" for="<?=$tid?>" data-toggle="tooltip" data-placement="left" title="<?=Yii::t('main', 'Määrittele lähetettäväksi')?>">&nbsp;
			<?=$item['etusukunimi']?>
		</h5>
		<?php
		$file = $week.'_'.$year.'_'.$tid.'.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
		if (file_exists($path.'/'.$file)){
			// <-- file_safe_opener
			$filepath = 'emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file;
			echo '<p>'.CHtml::link(Yii::t('main', ' Lähetetty'),
				array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
				array('target'=>'_blank','class'=>'text-danger'
			)).'</p>';
			//     file_safe_opener -->
		}
		if(isset($vktyoaika[$tid])){
			echo '<div class="text-center"><b>'.$vktyoaika[$tid].'</b> / <b id="vkoyht_'.$tid.'">00:00</b></div>';
		}
		?>
	</div>
    </td>
    <?php $week_yhteensa[$tid] = 0; ?>
    <?php $f = date("d.m.Y", strtotime($from)); ?>
    <?php while (strtotime($f) <= strtotime($to)): ?>
    <?php
    if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6){
	$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
    }
    ?>
    <?php $did = date("Ymd", strtotime($f)); ?>
      <td>
	<div id="<?=$did.'_'.$tid?>" class="latikkoAsetukset" pvm="<?=$f?>" tid="<?=$tid?>">
		<?php $pvm_yhteensa = 0; ?>
		<?php if( isset($tv_arr[$tid][$f]) ): ?>
		<?php ksort($tv_arr[$tid][$f]); ?>
		<?php foreach($tv_arr[$tid][$f] as $k => $v): ?>
			<?php foreach($v as $v2): ?>
				<?php if( isset($last_loppu) and ($this->num(strtotime($v2['alku'])-strtotime($last_loppu)) > 0) ): ?>
					<p class="reika reika-warning text-center">Aika: <?=$this->sprint(strtotime($v2['alku'])-strtotime($last_loppu))?></p>
				<?php endif; ?>
				<p><?=$v2['tv_edit']?></p>
				<?php $pvm_yhteensa += $v2['tv_kesto']; ?>
				<?php $last_loppu = $v2['loppu']; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
		<?php endif; ?>
		<?=($pvm_yhteensa > 0)? '<div class="pull-right pvm_yht">'.$this->sprint($pvm_yhteensa).'</div>':''?>
		<?php $week_yhteensa[$tid] += $pvm_yhteensa; ?>
	</div>
      </td>
      <?php $f = date ("d.m.Y", strtotime("+1 day", strtotime($f))); ?>
    <?php endwhile; ?>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$.each(JSON.parse('<?=json_encode($week_yhteensa)?>'), function( tid, value ) {
		$('#vkoyht_' + tid).html($.sprint(value));
	});
});
</script>
