<?php
ini_set('memory_limit', '512M');
//print_r($_SESSION['muistin']);
//exit;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>
<?php echo CHtml::button(Yii::t('main', 'Lähetä'),array('class'=>'btn btn-lg btn-success','id'=>'lahetaTyovuoroja', 'style' => 'display: none; position:fixed; bottom: 0; left: 0; margin: 10px')); ?>
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
<?php $odotus_ikooni = '<img src="../../lib/img/etusivu/odotus.gif" height="20px">'; ?>

<!-- TV laatiko -->
<div id="showres" class="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
<!-- TV laatiko -->
<div id="yht_tv"></div>
<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th class="bg-default text-center" width="1"><?php echo CHtml::button(Yii::t('main', 'Valitse kaikki'),array('target'=>'_blank','class'=>'btn btn-default valitseKaikkiLahetettavaksi')); ?></th>
 <?php
 $f 		= date("d.m.Y", strtotime($from));
 $did_sunday 	= date("Ymd", strtotime($year.'W'.$week.'7'));
 echo '<span class="sunday" sunday="'.date("d.m.Y", strtotime($year.'W'.$week.'7')).'" tids="'.json_encode(array_values($haku_tids)).'"></span>';
 while (strtotime($f) <= strtotime($to)){
	if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
		$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
	$columnDate 	= date("N/d.m",strtotime($f));
	$explColDate 	= explode("/",$columnDate);
	$tr_pyhat 	= '';
	$ispyha 	= '';
	if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) ){
		$tr_pyhat = 'tr_pyhat';
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	}
	if(isset($pyhapaivat[$f]['el'])){
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Erikoislauantai').'"></i>';
	}
	echo '<th class="bg-default text-center '.$tr_pyhat.'" style="z-index: 999;">
		<div class="laatiko_td"><b>'.$arrDate[$explColDate[0]].'</b>, '.$explColDate[1].$ispyha.'</b></div>
	</th>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
 } 
 ?>
 </tr>
 </thead>
 <tbody>
 <!-- VARAUKSET -->
 <tr>
    <td class="bg-default text-center" style="z-index: 999; width: 50px">
	<span class="nimi">VARAUS</span>
    </td>
    <?php
	$f = date("d.m.Y", strtotime($from));
	while (strtotime($f) <= strtotime($to)){
		if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
			$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
		$did 		= date("Ymd", strtotime($f));
		$columnDate 	= date("N/d.m",strtotime($f));
		$explColDate 	= explode("/",$columnDate);
		$tr_pyhat 	= '';
		if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) )
			$tr_pyhat = 'tr_pyhat';

		echo '<td class="'.$tr_pyhat.'">';
		echo '<div id="'.$did.'_0" class="latikkoAsetukset" pvm="'.$f.'" tid="0"><span class="odotus">'.$odotus_ikooni.'</span></div>';
		echo '</td>';
    		$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
    } 
  ?>
 </tr>
 <!-- VARAUKSET -->
 <?php foreach($tt as $tid=>$item): ?>
 <tr>
	<td class="bg-default" style="z-index: 999; max-width: 150px; white-space: normal;">
	<div class="m15 text-center">
		<h5 class="nimi"><?=$item['etusukunimi']?></h5>
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
			echo '<div class="text-center"><b>'.$vktyoaika[$tid].'</b> / ';
		echo '<b id="vko_'.$did_sunday.'_'.$tid.'""><span class="odotusweeklaskennan">'.$odotus_ikooni.'</span></b>';
		?>
		<p><input type="checkbox" class="lahetettava_checkbox" for="<?=$tid?>" data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('main', 'Määrittele lähetettäväksi')?>"></p>
	</div>
	</td>
	<?php
	$f = date("d.m.Y", strtotime($from));
	while (strtotime($f) <= strtotime($to)){
		if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
			$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
		$did = date("Ymd", strtotime($f));
		$columnDate 	= date("N/d.m",strtotime($f));
		$explColDate 	= explode("/",$columnDate);
		$tr_pyhat 	= '';
		if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) )
			$tr_pyhat = 'tr_pyhat';
		echo '<td class="'.$tr_pyhat.'"><div id="'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$f.'" tid="'.$tid.'"><span class="odotus">'.$odotus_ikooni.'</span></div></td>';
		$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
	}
	?>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
</div>
<?php


?>
<!-- Täytetään tauluu -->
<?php echo $this->tv_arrJava($from, $to, $haku_criteria, $haku_tids); ?>
