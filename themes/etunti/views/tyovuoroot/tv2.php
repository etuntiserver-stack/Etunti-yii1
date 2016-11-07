<?php

?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot.css">

<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */




   $pvmtid = Yii::app()->request->getParam('pvmtid', 0);
   if(!empty($pvmtid)){
	$expl = explode("_",$pvmtid);
	Yii::app()->session['from'] = date("Y-m-d",strtotime($expl['0']));
	Yii::app()->session['to'] = date("Y-m-d",strtotime($expl['0']." +1 week"));
	Yii::app()->session['Tekija'] = array($expl['1']);
	?>
	<script type="text/javascript">
	$(document).ready(function(){
	
	  $('#<?php echo $pvmtid; ?>').addClass("alert alert-info");
	
	});
	</script>
	<?php
   }




		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));


		$from = '';
		$to = '';
		
		if(isset(Yii::app()->session['from']))
		$from = date("d.m.Y",strtotime(Yii::app()->session['from']));
		if(isset(Yii::app()->session['to']))
		$to = date("d.m.Y",strtotime(Yii::app()->session['to']));


   		$dTVfrom = date("Y-m-d",strtotime($from));
		echo '<input type="hidden" id="fromTV" value="'.$dTVfrom.'">';
		$dTVto = date("Y-m-d",strtotime($to));
		echo '<input type="hidden" id="toTV" value="'.$dTVto.'">';


// oletus arvot

   if(!isset(Yii::app()->session['from']) and !isset(Yii::app()->session['to']) and !isset(Yii::app()->session['Tekija'])){
	Yii::app()->session['from'] = date("Y-m-d");
	Yii::app()->session['to'] = date("Y-m-d",strtotime("+1 month", time()));


       		$criteria = new CDbCriteria();
        	$criteria->order = "id DESC LIMIT 5";
        	$criteria->select = "id,tekijan_nimi";
        	$criteria->condition = " aktiivinen = '1' ";
		$tt = Tyontekijat::model()->findAll($criteria);
		$tekijatOletuksena = array();
		foreach($tt as $t)
		$tekijatOletuksena[] = $t->id;

		Yii::app()->session['Tekija'] = $tekijatOletuksena;


   }



		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

       		$criteria = new CDbCriteria();
        	$criteria->order = "tekijan_nimi";
        	$criteria->select = "id,tekijan_nimi";
        	$criteria->condition = " aktiivinen = '1' ";

		if(Yii::app()->session['Tekija'] and (!isset($_GET['asiakas']) and !isset($_GET['kohde']))){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

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





//print_r(Yii::app()->session['tvuoroTekija']);

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
	$dateDiff = dateDiff($from, $to);

//echo Yii::app()->session['copymove'];

?>
<style>
.table tbody>tr>td{
    	vertical-align: top;
}
</style>


   <!--<input type="text" id="totalForCut" class="form-control">-->

        <!-- begin: .tray-center -->
<?php if(!isset($_GET['fullscreen'])) : ?>
<input type="hidden" id="korko" value="290">



<div class="row" id="ylapalkki" style="display:none">
 <div class="form-inline">
  <div class="form-group">

	<label><?php echo Yii::t('main', 'Haku'); ?></label><br>

  <form action="#" id="yhtveto" class="form-inline" method="POST">
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
   array('empty'=>Yii::t('main', 'Työnimike'),'class'=>'form-control form-group','id'=>'siivousTyonimike'));

   ?>
   <input type="text" name="from" size="10" class="form-control form-group datepickerFI" value="<?php echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>" placeholder="<?php echo Yii::t('main' ,'Päivämäärä'); ?>">
   <input type="text" name="to" size="10" class="form-control form-group datepickerFI" value="<?php echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>" placeholder="<?php echo Yii::t('main' ,'Päivämäärä'); ?>">

  <?php

   //
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   $criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="Tekija[]" id="tyontekijat" class="mult" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
  ?>

   <button type="submit" class="btn btn-primary myBgColors fa fa-search"></button>
   </form>

   </div><div class="form-group pull-right">

     <div class="form-inline">
      <div class="form-group">
	<label><?php echo Yii::t('main', 'Uusi tilaus'); ?></label><br>
	<button class="btn btn-primary myBgColors" id="uusiTilaus"><?php echo Yii::t('main', 'Tilaus'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </div><div class="form-group">
	<label><?php echo Yii::t('main', 'Valitse näkymä'); ?></label><br>
	<select class="form-control tvchange">
 	  <option value="index"><?php echo Yii::t('main', 'Viikko'); ?></option>
 	  <option value="tv2" selected><?php echo Yii::t('main', 'Työntekijä'); ?></option>
 	  <!--<option value="tv_kohteet"><?php echo Yii::t('main', 'Kohde'); ?></option>-->
	</select>
      </div>
     </div>

   </div>
 </div>
</div>

<br>


<?php else: ?>
<input type="hidden" id="korko" value="140">
<?php endif; ?>



<?php if(!empty($from) and !empty($to) and count(Yii::app()->session['Tekija']) > 0 and Yii::app()->session['Tekija'][0] != 0) : ?>

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
       		$criteria->condition = " siivous LIKE '%".$_POST['siivous']."%' ";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $kohde)
		$kohteenArr[] = $kohde->id;

		if(count($kohteenArr) > 0)
		$checkSiivous = true;
		else
		$checkSiivous = false;
	}

?>


<div class="row">
            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">
                 <div class="row">

<?php if((isset($checkSiivous) and $checkSiivous == true) or !isset($checkSiivous)) : ?>
<div class="tvuoro table-responsive">
  <table class="table table-striped table-condensed table-bordered" style="background: white">
     <thead class="">
     <tr>
     <th></th>
        <?php 

	// VARAUS
	  echo '<th><div class="latikkoAsetukset">';
 	  echo '<b class="text-warning">'.Yii::t('main', 'VARAUS').'</b>';	
	  echo '</div></th>';
	// VARAUS

	$asetukset = Asetukset::model()->findByPk(1);

	foreach($tt as $t){
	  echo '<th><div class="latikkoAsetukset">';
 	  echo $t->tekijan_nimi;	
	  echo '</div></th>';
	}
        ?>
     </tr>
     </thead>
     <tbody>
        <?php
	$arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
    	for ($i = 0; $i <= $dateDiff; $i++) {
	  $plus = "+$i day";
	  $date = '';
	  $date = date("d.m.Y",strtotime($from." ".$plus));
	  $did = date("Ymd",strtotime($from." ".$plus));

	  $columnDate = date("N/d.m",strtotime($date));
	  $explColDate = explode("/",$columnDate);

	  $clPyhat = '';
	  $pyhat = $this->pyhat($date);
	  if($pyhat == true)
	  $clPyhat = 'style="background:#ddd"';

  	    echo '<tr>';
  		echo '<td '.$clPyhat.' class="fixed-column" id="first_'.$did.'"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].'</b></td>';

		// VARAUS
		  echo '<td '.$clPyhat.' id="'.$did.'_0">';
		  $tv = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>0,'from'=>'tvuoro', 'kohteenArr'=>$kohteenArr, 'asetukset'=>$asetukset), true);
		  echo json_decode($tv, true);
		  echo '</td>';
		// VARAUS

		foreach($tt as $t){
		  echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'">';
		  $tv = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro', 'kohteenArr'=>$kohteenArr, 'asetukset'=>$asetukset), true);
		  echo json_decode($tv, true);
		  echo '</td>';
		}
	    echo '</tr>';

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';
  		echo '<td class="text-center myBgColors viikkoRivi fixed-column"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';



		// VARAUS
		  echo '<td class="viikkoRivi myBgColors text-center" id="vk_'.date("W",strtotime($date)).'_0">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>0,'viikko'=>$vko,'year'=>$year),true);

		  echo '<span>'.$kokoViikko.'</span>';
		  echo '</td>';
		// VARAUS



		foreach($tt as $t){
		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td class="viikkoRivi myBgColors text-center" id="vk_'.date("W",strtotime($date)).'_'.$t->id.'">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$t->id,'viikko'=>$vko,'year'=>$year),true);

		  $cl = '';
		  if(	(int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		  )
		  $cl = 'class="btn btn-xs btn-danger"';

		  echo '<span '.$cl.'>'.$kokoViikko. '('.$vktyoaika.')</span>';

		  echo '</td>';
		}
	    echo '</tr>';
	    }

  	}
        ?>
     </tbody>
     <tfoot>
        <?php
  	    echo '<tr class="myBgColors">';
  		echo '<td class="text-center viikkoRivi fixed-column"></td>';

		  echo '<td class="text-center viikkoRivi myBgColors fromto_0" />';
		  $this->renderPartial('//tyovuoroot/fromto',array('tid'=>0));
		  echo '</td>';

		foreach($tt as $t){
		  echo '<td class="text-center viikkoRivi myBgColors fromto_'.$t->id.'" />';
		  $this->renderPartial('//tyovuoroot/fromto',array('tid'=>$t->id));
		  echo '</td>';
		}
	    echo '</tr>';
        ?>
     </tfoot>
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
	<?php Yii::app()->clientScript->registerPackage('fixedTable'); ?>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>


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
        bAutoWidth: false,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true
    });

    var onResize = function () {
        var oSettings = dataTable.fnSettings();
        oSettings.oScroll.sY = tableHeight()-parseInt($('#korko').val()); 
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


