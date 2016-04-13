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


		if(isset($_POST['osoiteTV']))
		{

			Yii::app()->session['osoiteTV'] = $_POST['osoiteTV'];

       			$criteria = new CDbCriteria();
        		$criteria->select = "id";
        		$criteria->condition = " osoite LIKE '%".$_POST['osoiteTV']."%' ";
			$k = Kohteet::model()->findAll($criteria);

			$tekijanArr = array();
			$kohteenArr = array();
			$pvmArr = array();
			foreach($k as $kohde)
			{

				// TID
       				$criteria = new CDbCriteria();
        			$criteria->select = "tid";
        			$criteria->group = "tid";
        			$criteria->condition = " 
					kohde='".$kohde->id."' 
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')
					BETWEEN '".date("Y-m-d",strtotime($_POST['from']))."' AND '".date("Y-m-d",strtotime($_POST['to']))."'
				";
				$tv = Tyovuoroot::model()->findAll($criteria);
				if(isset($tv[0]))
				   foreach($tv as $dat)
				     $tekijanArr[$dat->tid] = (int)$dat->tid;


				// PVM
       				$criteria = new CDbCriteria();
        			$criteria->select = "pvm";
        			$criteria->group = "pvm";
        			$criteria->condition = " 
					kohde='".$kohde->id."' 
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')
					BETWEEN '".date("Y-m-d",strtotime($_POST['from']))."' AND '".date("Y-m-d",strtotime($_POST['to']))."'
				";
				$tv = Tyovuoroot::model()->findAll($criteria);
				if(isset($tv[0]))
				   foreach($tv as $dat)
				     $tpvmArr[$dat->pvm] = $dat->pvm;


				     $kohteenArr[] = $kohde->id;

			}

			//print_r($tpvmArr);
			//exit;

			$_POST['kohteenArr'] = $kohteenArr;

			if(isset($tekijanArr[0]))
			{
			$tekijanArr = implode(',',$tekijanArr);

       			$criteria = new CDbCriteria();
        		$criteria->order = "tekijan_nimi";
	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = " aktiivinen = '1' AND id IN ($tekijanArr) ";
			$tt = Tyontekijat::model()->findAll($criteria);
			} else {
				echo '<h1>'.Yii::t('main','Ei löydy').'</h1>';
			}

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
/*
body{
    overflow-y: hidden;
}
*/
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


   <!--<input type="text" id="totalForCut" class="form-control">-->

        <!-- begin: .tray-center -->
<?php if(!isset($_GET['fullscreen'])) : ?>
<input type="hidden" id="korko" value="250">


<div class="row">
 <div class="col-sm-8">
  <form action="#" id="yhtveto" class="form-inline" method="POST">


   <input type="text" name="osoiteTV" id="osoiteTV" class="form-control form-group" value="<?php echo Yii::app()->session['osoiteTV']; ?>" placeholder="Osoite..">
   <input type="text" name="from" id="from" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <input type="text" name="to" id="to" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>
 </div><div class="col-sm-4">

 	<div class="pull-right">
 	  <div class="form-inline">
		<div class="btn btn-primary fa fa-plus" id="uusiTilaus" data-toggle="tooltip" title="Uusi tilaus"></div>
		<div class="btn btn-primary fa fa-calendar-plus-o" id="autoInsert" data-toggle="tooltip" title="Toistuva työvuorot"></div>
		<div class="btn btn-primary fa fa-calendar-minus-o" id="autoRemove" data-toggle="tooltip" title="Poista toistuva työvuorot"></div>
 	  </div>
 	</div>

 </div>
</div>

<br>


<?php else: ?>
<input type="hidden" id="korko" value="140">
<?php endif; ?>



<?php if(!empty($from) and !empty($to) and isset($tt[0]) and isset($kohteenArr[0]) and isset(Yii::app()->session['osoiteTV'])) : ?>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<div class="tvuoro table-responsive">
  <table class="table table-striped table-condensed table-bordered" style="background: white">
     <thead class="">
     <tr>
     <th></th>
        <?php 
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
    	foreach ($tpvmArr as $date) {

	  $date = date("d.m.Y",strtotime($date));
	  $did = date("Ymd",strtotime($date));

	  $columnDate = date("N/d.m",strtotime($date));
	  $explColDate = explode("/",$columnDate);

	  $clPyhat = '';
	  $pyhat = $this->pyhat($date);
	  if($pyhat == true)
	  $clPyhat = 'style="background:#ddd"';

  	    echo '<tr>';
  		echo '<td '.$clPyhat.' class="fixed-column"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].'</b></td>';
		foreach($tt as $t){
		  echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'">';
		  $tv = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro','kohteenArr'=>$kohteenArr), true);
		  echo json_decode($tv, true);
		  echo '</td>';
		}
	    echo '</tr>';

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr class="myBgColors">';
  		echo '<td class="text-center viikkoRivi fixed-column"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';
		foreach($tt as $t){
		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td class="viikkoRivi text-center" id="vk_'.date("W",strtotime($date)).'_'.$t->id.'">';
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


});
</script>


