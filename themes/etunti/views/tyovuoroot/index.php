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


  $wkMaara = 53;
  $year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
  $week = (isset($_GET['week'])) ? $_GET['week'] : date('W');

  if($week > $wkMaara) {
    $year++;
    $week = 1;
  } elseif($week < 1) {
    $year--;
    $week = $wkMaara;
  }
    $week = sprintf("%02d", $week);


   $dTVfrom = date("Y-m-d",strtotime($year ."W". $week. '1'));
   echo '<input type="hidden" id="fromTV" value="'.$dTVfrom.'">';
   $dTVto = date("Y-m-d",strtotime($year ."W". $week. '7'));
   echo '<input type="hidden" id="toTV" value="'.$dTVto.'">';

   $nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
?>

<?php
// oletus arvot
   if(!isset(Yii::app()->session['TekijaVuoro'])){

       		$criteria = new CDbCriteria();
        	$criteria->order = "tekijan_nimi";
        	$criteria->select = "id,tekijan_nimi";
        	$criteria->condition = " aktiivinen = '1' ";
		$tt = Tyontekijat::model()->findAll($criteria);
		$tekijatOletuksena = array();
		foreach($tt as $t)
		$tekijatOletuksena[] = $t->id;

		Yii::app()->session['TekijaVuoro'] = $tekijatOletuksena;
   }



		if(Yii::app()->request->getPost('TekijaVuoro'))
		Yii::app()->session['TekijaVuoro'] = Yii::app()->request->getPost('TekijaVuoro');

       		$criteria = new CDbCriteria();
        	$criteria->order = "tekijan_nimi";
        	$criteria->select = "id,tekijan_nimi";
        	$criteria->condition = " aktiivinen = '1' ";

		if(Yii::app()->session['TekijaVuoro'] and (!isset($_GET['asiakas']) and !isset($_GET['kohde']))){
		  if(count(Yii::app()->session['TekijaVuoro']) > 1)
		    $ids = implode(",",Yii::app()->session['TekijaVuoro']);
		  else
		    $ids = Yii::app()->session['TekijaVuoro'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}
		// <-- Asiakas
		if(isset($_GET['asiakas']) and !empty($_GET['asiakas']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$dTVfrom' AND '$dTVto'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi LIKE '%".$_GET['asiakas']."%' OR yhteyshenkilo LIKE '%".$_GET['asiakas']."%' OR puhelin LIKE '%".$_GET['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset($_GET['kohde']) and !empty($_GET['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$dTVfrom' AND '$dTVto'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".$_GET['kohde']."%' OR puh_nro LIKE '%".$_GET['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tt = Tyontekijat::model()->findAll($criteria);



?>


<?php if(count(Yii::app()->session['TekijaVuoro']) > 0 and Yii::app()->session['TekijaVuoro'][0] != 0) : ?>


<div id="ylapalkki" style="display:none">
 <div class="form-inline">
  <div class="form-group">

	<label><?php echo Yii::t('main', 'Työntekijät'); ?></label><br>

  <form action="index" id="yhtveto" method="POST">

   <?php
   // Toimialue
   $list = array();
   $criteria = new CDbCriteria();
   $criteria->order = " select_type ";
   $criteria->condition = " select_type='tyo_toimialue' ";
   $l = Valikkoot::model()->findAll($criteria);
   foreach($l as $v)
   $list[$v->value] = $v->value;

   echo CHtml::dropDownList('siivous', 'siivous', $list,
   array('empty'=>Yii::t('main', 'Toimialue'),'class'=>'form-control form-group','id'=>'tekijanToimialue')).' ';


   // Kohteen ryhman mukaan
   $list = array();
   $criteria = new CDbCriteria();
   $criteria->order = " select_type ";
   $criteria->condition = " select_type='siivous' ";
   $l = Valikkoot::model()->findAll($criteria);
   foreach($l as $v)
   $list[$v->value] = $v->value;

   echo CHtml::dropDownList('siivous', 'siivous', $list,
   array('empty'=>Yii::t('main', 'Työnimike'),'class'=>'form-control form-group','id'=>'siivousTyonimike')).' ';


   //
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   $criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="TekijaVuoro[]" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
       if(isset(Yii::app()->session['TekijaVuoro']) and in_array($key,Yii::app()->session['TekijaVuoro']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>
   <button type="submit" class="btn btn-primary fa fa-search myBgColors"></button><?php echo $nbsp; ?>
   </form>


   </div><div class="form-group">

	<label><?php echo Yii::t('main', 'Viikot'); ?></label><br>


	<select class="form-control" id="vuodenhyppaminen">
	<?php
	$v = date('Y',strtotime('-5 year'));
	for ($i = 1; $i <= 10; $i++) {
		if($year == ($v+$i))
    			echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.$week.'&year='.(int)($v+$i).'" selected>'.(int)($v+$i).'</option>';
		else
    			echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.$week.'&year='.(int)($v+$i).'">'.(int)($v+$i).'</option>';
	}
	?>
	</select>

<div class="input-group">
  <span class="input-group-btn">
     	<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? $wkMaara : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><i class="fa fa-arrow-left btn btn-primary myBgColors"></i></a>
  </span>

	<select class="form-control" id="viikkonhyppaminen">
	<?php
	define('NL', "\n");
	$year           = $year;
	$firstDayOfYear = mktime(0, 0, 0, 1, 1, $year);
	$nextMonday     = strtotime('monday', $firstDayOfYear);
	$nextSunday     = strtotime('sunday', $nextMonday);
	
	    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.$week.'&year='.$year.'">'.date('d', strtotime($year ."W". $week . '1')), NL.'-'.date('d.m', strtotime($year ."W". $week . '7')), NL.'</option>';

	while (date('Y', $nextMonday) == $year) {
	    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.date('W', $nextMonday), NL.'&year='.$year.'">'.date('d', $nextMonday), NL.'-'.date('d.m', $nextSunday), NL.'</option>';
	
	    $nextMonday = strtotime('+1 week', $nextMonday);
	    $nextSunday = strtotime('+1 week', $nextSunday);
	}
	?>
	</select>
  <span class="input-group-btn">
     	<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == $wkMaara ? 1 : 1 + $week).'&year='.($week == $wkMaara ? 1 + $year : $year); ?>"><i class="fa fa-arrow-right btn btn-primary myBgColors"></i></a> <?php echo $nbsp; ?>
  </span>
</div>


   </div><div class="form-group">
	
	<label><?php echo Yii::t('main', 'Viikkonloput'); ?></label><br>
	<div class="btn" id="vkolopput"><?php echo Yii::t('main', 'Viikonloput'); ?></div>

   </div><div class="form-group">

    <div class="">
     <div class="form-inline">
      <div class="form-group">
	<label><?php echo Yii::t('main', 'Uusi tilaus'); ?></label><br>
	<button class="btn btn-primary myBgColors" id="uusiTilaus"><?php echo Yii::t('main', 'Tilaus'); ?></button><?php echo $nbsp; ?>
      </div><div class="form-group">
	<label><?php echo Yii::t('main', 'Valitse näkymä'); ?></label><br>
	<select class="form-control tvchange">
 	  <option value="index" selected><?php echo Yii::t('main', 'Viikko'); ?></option>
 	  <option value="tv2"><?php echo Yii::t('main', 'Työntekijä'); ?></option>
 	  <!--<option value="tv_kohteet"><?php echo Yii::t('main', 'Kohde'); ?></option>-->
	</select>
      </div>
     </div>
    </div>

   </div>
 </div>
</div>

<br>




            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">
                 <div class="row">

<?php
if(!isset($_SESSION['vkolopput']))
$numDays = 5;
else
$numDays = 7;
?>

<?php

	$kohteenArr = array();
	if(isset($_POST['siivous']) and !empty($_POST['siivous']))
	{

		echo '
		<script type="text/javascript">
		$(document).ready(function(){

		  $("#siivousTyonimike option[value=\''.$_POST['siivous'].'\']").attr(\'selected\',\'selected\');
		});
		</script>';

		$criteria = new CDbCriteria();
       		$criteria->select = "id";
       		$criteria->condition = " 
			siivous LIKE '%".$_POST['siivous']."%' 
			AND id IN(
				SELECT kohde FROM sivex_tvuoro 
				WHERE YEARWEEK(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')) = '".$year.$week."'
			)
		";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $kohde)
		 $kohteenArr[] = $kohde->id;

		if(count($kohteenArr) > 0)
		$checkSiivous = true;
		else
		$checkSiivous = false;

	}


?>

<?php if((isset($checkSiivous) and $checkSiivous == true) or !isset($checkSiivous)) : ?>
<div class="row table-responsive">
  <table class="table table-striped table-condensed table-bordered" style="background: white">
     <thead>
     <tr>
	<th><?php echo Yii::t('main', 'Nimi'); ?></th>
        <?php
	for($day= 1; $day <= $numDays; $day++)
	{
  	  $d = strtotime($year ."W". $week . $day);
	  $date = date('d.m',$d);
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
 	     $did = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>0,'from'=>'tvuoro', 'kohteenArr'=>$kohteenArr, 'asetukset'=>$asetukset), true);
	     echo json_decode($did, true);
	     echo '</td>';
	  }
	  echo '</tr>';
	// VARAUS





	foreach($tt as $t)
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
 	     $did = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro', 'kohteenArr'=>$kohteenArr, 'asetukset'=>$asetukset), true);
	     echo json_decode($did, true);
	     echo '</td>';
	  }
	  echo '</tr>';
	}
        ?>
     </tbody>
  </table>
</div>
<?php endif; ?>

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
        oSettings.oScroll.sY = tableHeight()-260; 
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


	$('#ylapalkki').show('slow');


$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
});

$('.tvchange').change(function(){
	var thisVal = $(this).val();
	window.location.href=thisVal;
});


});
</script>
