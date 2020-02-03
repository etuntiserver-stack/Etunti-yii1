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
 <?php foreach($tt as $tid=>$item): ?>
 <tr>
    <td class="laatiko_td text-center" style="z-index: 999; min-width: 150px">
	<h5 class="nimi"><?=$item['etusukunimi']?></h5>
	<input type="checkbox" class="lahetettava_checkbox" for="<?=$tid?>" data-toggle="tooltip" data-placement="right" title="<?=Yii::t('main', 'Määrittele lähetettäväksi')?>">
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

	if(isset($vktyoaika[$tid]))
		echo '<p>'.$vktyoaika[$tid].'</p>';
	?>
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
	<div id="<?=$did.'_'.$tid?>" class="latikkoAsetukset" pvm="<?=$f?>" tid="<?=$tid?>">
		<?php if( isset($tv_arr[$tid][$f]) ): ?>
		<?php ksort($tv_arr[$tid][$f]); ?>
		<?php foreach($tv_arr[$tid][$f] as $k => $v): ?>
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
 <?php endforeach; ?>
 </tbody>
 </table>
</div>

<script type="text/javascript">
$(document).ready(function(){
	//var numItems = $('.tv_edit').length;
	//$('#yht_tv').html(numItems);
});
</script>
