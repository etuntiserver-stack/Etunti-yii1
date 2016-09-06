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

		if(Yii::app()->session['TekijaVuoro']){
		  if(count(Yii::app()->session['TekijaVuoro']) > 1)
		    $ids = implode(",",Yii::app()->session['TekijaVuoro']);
		  else
		    $ids = Yii::app()->session['TekijaVuoro'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}

		$tt = Tyontekijat::model()->findAll($criteria);



?>


<?php if(count(Yii::app()->session['TekijaVuoro']) > 0 and Yii::app()->session['TekijaVuoro'][0] != 0) : ?>

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
?>

<div class="row">
            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">




<div class="row">
 <div class="row col-sm-5">
  <form action="index" id="yhtveto" method="POST">

   <div class="form-inline">
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
   array('empty'=>Yii::t('main', 'Toimialue'),'class'=>'form-control form-group','id'=>'tekijanToimialue'));


   // Kohteen ryhman mukaan
   $list = array();
   $criteria = new CDbCriteria();
   $criteria->order = " select_type ";
   $criteria->condition = " select_type='siivous' ";
   $l = Valikkoot::model()->findAll($criteria);
   foreach($l as $v)
   $list[$v->value] = $v->value;

   echo CHtml::dropDownList('siivous', 'siivous', $list,
   array('empty'=>Yii::t('main', 'Työnimike'),'class'=>'form-control form-group','id'=>'siivousTyonimike'));


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
   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </div>
   </form>

 </div><div class="col-sm-4">


   <div class="form-inline">
     <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? $wkMaara : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><i class="fa fa-arrow-left" style="font-size: 120%"></i></a>

	<select class="form-control" id="viikkonhyppaminen">
	<?php
	define('NL', "\n");
	$year           = $year;
	$firstDayOfYear = mktime(0, 0, 0, 1, 1, $year);
	$nextMonday     = strtotime('monday', $firstDayOfYear);
	$nextSunday     = strtotime('sunday', $nextMonday);
	
	    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.$week.'&year='.$year.'">Vko:'.$week.', '. date('d.m.Y',strtotime($year ."W".$week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')).'</option>';

	while (date('Y', $nextMonday) == $year) {
	    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.date('W', $nextMonday), NL.'&year='.$year.'">Vko:'.date('W', $nextMonday), NL.', '.date('d.m.Y', $nextMonday), '-', date('d.m.Y', $nextSunday), NL.'</option>';
	
	    $nextMonday = strtotime('+1 week', $nextMonday);
	    $nextSunday = strtotime('+1 week', $nextSunday);
	}
	?>
	</select>
     <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == $wkMaara ? 1 : 1 + $week).'&year='.($week == $wkMaara ? 1 + $year : $year); ?>"><i class="fa fa-arrow-right" style="font-size: 120%"></i></a> 

   </div>


 </div><div class="col-sm-3">

 	<div class="pull-right row">
 	  <div class="form-inline row">
		<button class="btn btn-primary" id="uusiTilaus"><?php echo Yii::t('main', 'Tilaus'); ?></button>
		<div class="btn" id="vkolopput"><?php echo Yii::t('main', 'Viikonloput'); ?></div>
 	  </div>
 	</div>

 </div>
</div>



                </div>
              </div>
            </div>
</div>

<div id="ilmoitukset"></div>

<div class="row">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                




<?php
if(!isset($_SESSION['vkolopput']))
$numDays = 5;
else
$numDays = 7;
?>

<style>
.mennytPaivat{
	opacity: 0.4;
}
td .latikkoAsetukset{
	min-width: 70px;
	width: 200px;
	white-space: normal;
}
.forCut, .forCopy{ 
	display: none;
}
.mplus, .mcut, .clear, .trash{ 
	display: none;
}
td:hover .mplus, td:hover .mcut, td:hover .clear, td:hover .trash{
	display : block;
}
td .tp{
	//position:absolute;
}
.fullRivi{
	height: 100%;
	margin-bottom: 2px;
	border:1px #ccc solid;
	padding:3px 7px;
	background: white;
	border-radius:5px;
}
.luominen, .valitseKokopaiva{
	display: none;
}
.table tbody>tr>td{
    	vertical-align: top;
}
.table{
    height: 100%;
}
</style>

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
  <table class="table table-bordered table-striped small" style="background: white">
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



$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
});


});
</script>
