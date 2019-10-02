<?php

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
 <?php $tid_arr = array(); ?>
 <?php foreach($tt as $tid=>$item): ?>
 <?php $tid_arr[$tid] = $tid; ?>
 <th class="text-center" style="z-index: 999;"><?=$item['etusukunimi']?></th>
 <?php endforeach; ?>
 </tr>
 </thead>
 <?php $f = date("d.m.Y", strtotime($from)); ?>
 <?php while (strtotime($f) <= strtotime($to)): ?>
 <?php
  $did = date("Ymd", strtotime($f));
  $columnDate = date("N/d.m",strtotime($f));
  $explColDate = explode("/",$columnDate);
 ?>
 <tbody>
 <tr>
 <td class="laatiko_td text-center" style="z-index: 999;">
  <div class="nimi">
   <b><?=$arrDate[$explColDate[0]]?></b>
   <br>
   <?=$explColDate[1]?></b>
  </div>
 </td>
 <?php foreach($tid_arr as $tid): ?>
 <td class="laatiko_td">
  <div class="latikkoAsetukset" for="<?=$did.'_'.$tid?>">
  <?php
  if( isset($tv_arr[$did.'_'.$tid]) ){
     for ($i = 0; $i <= count($tv_arr[$did.'_'.$tid]); $i++) {
	if(isset($tv_arr[$did.'_'.$tid][$i])){
		$color = '#888';
		$bgcol = 'color:#333';
		$arvo = $tv_arr[$did.'_'.$tid][$i];
		$osoite = $arvo['osoite'];
		$kellot = '<b class="kellot">'.$arvo['alku'].'-'.$arvo['loppu'].'&nbsp; </b>';
	        $muokkaus =  '<i class="link tvikooni fa fa-pencil-square-o muistin" for="'.$arvo['id'].'_'.$did.'_'.$tid.'"></i>';
		if(!empty($tv_arr[$i]['tyoajanmerkinta'])){
			$expl = explode("/",$tv_arr[$i]['tyoajanmerkinta']);
			if(isset($expl[1]) and !empty($expl[1])){
				$color = $expl[1];
				$bgcol = 'color:'.$color;
			}
		}
		if(!empty($tv_arr[$i]['tyoajanlaatu']) and empty($osoite)){
			$expl1 = explode("/",$tv_arr[$i]['tyoajanlaatu']);
			if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
			$osoite = (isset($expl1[0])) ? '<div class="text-center"><b style="color:'.$color.'">'.$expl1[0].'</b></div>' : '';
			$kellot = '';
		}
	   	echo '<div class="fullRivi" style="'.$bgcol.'">';
		echo '<div class="pull-left ikoonintila" style="display:none;margin-right: 5px">'.$muokkaus.' </div>';
		echo '<span class="tv_edit" id="'.$arvo['id'].'">'.$kellot.$osoite.'</span>';
	   	echo '</div>';
	}
     }
  }
  ?>
  </div>
 </td>
 <?php endforeach; ?>
 </tr>
 </tbody>
 <?php $f = date ("d.m.Y", strtotime("+1 day", strtotime($f))); ?>
 <?php endwhile; ?>
 </table>
</div>


<?php 

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
