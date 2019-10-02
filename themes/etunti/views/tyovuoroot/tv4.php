<?php
ini_set('memory_limit', '512M');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>

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

<!-- TV laatiko -->
<div id="showres" class="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
<!-- TV laatiko -->

<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th></th>
 <?php $f = date("d.m.Y", strtotime($from)); ?>
 <?php while (strtotime($f) <= strtotime($to)): ?>
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
 <?php foreach($tt as $tid=>$item): ?>
 <tr>
    <td class="laatiko_td text-center" style="z-index: 999; width: 50px">
	<div class="nimi"><?=$item['etusukunimi']?></div>
    </td>
    <?php $f = date("d.m.Y", strtotime($from)); ?>
    <?php while (strtotime($f) <= strtotime($to)): ?>
      <?php $did = date("Ymd", strtotime($f)); ?>
      <td>
	<div id="<?=$did.'_'.$tid?>" class="latikkoAsetukset">
		<?php //echo json_decode($this->tv4head($tid, $f)); ?>
		<?php if( isset($tv_arr[$tid][$f]) ): ?>
			<?php array_map('superfast', $tv_arr[$tid][$f]); ?>
			<?php //echo json_decode($this->tv4_loop($tv_arr[$tid][$f], $tid, $f)); ?>
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


<?php 
function superfast($arvo){
	echo '<span class="tv_edit" id="'.$arvo['id'].'">'.((!empty($arvo['alku']))?$arvo['alku'].'-'.$arvo['loppu'].' ':'').$arvo['osoite'].'</span><br>';
}
/*
<script type="text/javascript">
$(document).ready(function(){

$('#fixTable tr td').each(function(){
	var this_id = $(this).attr('id');
	console.log(this_id);
});


  $.each(<?=json_encode($tv_arr)?>, function( i, elem ) {
    $.each(elem, function( index, value ) {
      var html = '';
      $.each(value, function( r, p ) {
	console.log(index + "_" + p['tid'] + " " + p['osoite']);
	html = p['alku'] + "-" + p['loppu'] + " " + p['osoite'] + "<br>";
	$("#" + index + "_" + p['tid'] ).append(html);
      });
    });
  });


});
</script>
*/ ?>
