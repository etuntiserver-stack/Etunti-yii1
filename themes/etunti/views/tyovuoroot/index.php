<?php

?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot.css">

<?php
  $paivat=array(
	1=>Yii::t('main', 'Ma'),
	2=>Yii::t('main', 'Ti'),

	3=>Yii::t('main', 'Ke'),
	4=>Yii::t('main', 'To'),
	5=>Yii::t('main', 'Pe'),
	6=>Yii::t('main', 'La'),
	7=>Yii::t('main', 'Su'),
	);



   $dTVfrom = date("Y-m-d",strtotime($year ."W". $week. '1'));
   echo '<input type="hidden" id="fromTV" value="'.$dTVfrom.'">';
   $dTVto = date("Y-m-d",strtotime($year ."W". $week. '7'));
   echo '<input type="hidden" id="toTV" value="'.$dTVto.'">';

?>



<?php if(!isset($_GET['fullscreen'])) : ?>
	<input type="hidden" id="taulunKorko" value="160">
<?php else: ?>
	<input type="hidden" id="taulunKorko" value="140">
<?php endif; ?>

<?php if( count($tyontekijat_model) == 0 ) : ?>
	<div class="alert alert-danger"><?php echo Yii::t('main', 'Ei tuloksia, tarkasta haku.'); ?></div>
<?php endif; ?>


<?php if( !empty($year) and !empty($week) and count($tyontekijat_model) > 0 ) : ?>
<div class="row">
            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">
                 <div class="row">



<div class="table-responsive">
  <table class="table table-striped table-condensed table-bordered" style="background: white">
     <thead>
     <tr>
	<th><?php echo Yii::t('main', 'Nimi'); ?></th>
        <?php
	for($day= 1; $day <= $numDays; $day++)
	{
  	  $d = strtotime($year ."W". $week . $day);
	  $date = date('d.m.Y',$d);
	  echo '<th>'.$paivat[date('N',$d)].', '.$date.'</th>';
	}
        ?>
     </tr>
     </thead>
     <tbody>
        <?php


	// VARAUS
	  echo '<tr>';
	  echo '<td width=1 id="first_0">';

		echo '
		<div class="row">
		  <div class="col-sm-12">
		    	<b class="text-warning">'.Yii::t('main', 'VARAUS').'</b>
		  </div>
		</div>';


	  echo '</td>';

	  $asetukset = Asetukset::model()->findByPk(1);
	  for($day= 1; $day <= $numDays; $day++)
	  {
  	     $d = strtotime($year ."W". $week . $day);
	     $date = date('d.m.Y',$d);
	     $did = date('Ymd',$d);

	     $clPyhat = '';
	     $pyhat = $this->pyhat($date);
	     if($pyhat == true)
	     $clPyhat = 'style="background:#ddd"';

	     echo '<td '.$clPyhat.' id="'.$did.'_0" valign="top">';
 	     $did = $this->renderPartial('//tyovuoroot/did',array(
					'pvm'=>$date,
					'tid'=>0,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>$kohteet_siivous, 
					'asetukset'=>$asetukset,
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
	     ), true);
	     echo json_decode($did, true);
	     echo '</td>';
	  }
	  echo '</tr>';
	// VARAUS





	foreach($tyontekijat_model as $t)
	{
	  echo '<tr>';
	  echo '<td width=1 id="first_'.$t->id.'">';

	     $vktyoaika = '';
	     $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
	     if(isset($ts->id) and !empty($ts['vktyoaika']))
	     $vktyoaika = $ts['vktyoaika'];
	     $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$t->id,'viikko'=>$week,'year'=>$year),true);
 	  
		$cl = '';
		if((int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		)
		$cl = 'class="btn btn-xs btn-danger"';


		echo '
		<div class="row">
		  <div class="col-sm-12">
		    	<b>'.$t->tekijan_nimi.'</b>
			<br>
			<span '.$cl.'><b id="vk_'.$week.'_'.$t->id.'">'.$kokoViikko. '</b> ('.$vktyoaika.')</span>
		  </div>
		</div>';


	  echo '</td>';

	  for($day= 1; $day <= $numDays; $day++)
	  {
  	     $d = strtotime($year ."W". $week . $day);
	     $date = date('d.m.Y',$d);
	     $did = date('Ymd',$d);

	     $clPyhat = '';
	     $pyhat = $this->pyhat($date);
	     if($pyhat == true)
	     $clPyhat = 'style="background:#ddd"';

	     echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'" valign="top">';
 	     $did = $this->renderPartial('//tyovuoroot/did',array(
					'pvm'=>$date,
					'tid'=>$t->id,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>$kohteet_siivous, 
					'asetukset'=>$asetukset,
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
	     ), true);
	     echo json_decode($did, true);
	     echo '</td>';
	  }
	  echo '</tr>';
	}
        ?>
     </tbody>
  </table>
</div>


                 </div>
                </div>
              </div>
            </div>
</div>
<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
	<?php Yii::app()->clientScript->registerPackage('fixedTable'); ?>


<script type="text/javascript">
$(document).ready(function(){

$(function () {

    var tableHeight = function () {
        var $tableHeader = $('.dataTables_scrollHeadInner thead tr');
        return $(window).height() - 4 - ($tableHeader.length ? $tableHeader.height() : 0);
    };

    var dataTable = $('table').dataTable({
        sDom: 'frtiS',
        sScrollY: tableHeight(),
        sScrollX: '100%',
        bAutoWidth: true,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true
    });

    var onResize = function () {
        var oSettings = dataTable.fnSettings();
        oSettings.oScroll.sY = tableHeight()-$('#taulunKorko').val(); 
        dataTable.fnDraw();
    };

    var firstDraw = false;
    new FixedColumns(dataTable, {
        iLeftWidth: 100,
        fnDrawCallback: function () {
            if (firstDraw) return;
            firstDraw = true;
            onResize();
        }
    });

    $(window).resize(onResize);
});





});
</script>
