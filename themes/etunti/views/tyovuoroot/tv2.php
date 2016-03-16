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

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}

		$tt = Tyontekijat::model()->findAll($criteria);



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
td .latikkoAsetukset{
	min-width: 70px;
	width: 200px;
	white-space: normal;
}
.forCut, .forCopy{ 
	display: none;
}
.mplus, .mcut{ 
	display: none;
}
td:hover .mplus, td:hover .mcut{
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
.luominen{
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


   <?php
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
   <input type="text" name="from" id="from" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <input type="text" name="to" id="to" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>
 </div><div class="col-sm-4">

 	<div class="pull-right">
 	  <div class="form-inline">
		<div id="trash"></div> 
		<div id="clear"></div>
		<div class="btn btn-primary fa fa-plus" id="uusiTilaus"></div>
		<div class="btn btn-primary fa fa-calendar-plus-o" id="autoInsert"></div>
		<div class="btn btn-primary fa fa-calendar-minus-o" id="autoRemove"></div>
 	  </div>
 	</div>

 </div>
</div>

<br>


<?php else: ?>
<input type="hidden" id="korko" value="140">
<?php endif; ?>



<?php if(!empty($from) and !empty($to) and count(Yii::app()->session['Tekija']) > 0 and Yii::app()->session['Tekija'][0] != 0) : ?>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<div class="tvuoro table-responsive">
  <table class="table table-striped table-condensed table-bordered">
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
  		echo '<td '.$clPyhat.' class="fixed-column"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].'</b></td>';
		foreach($tt as $t){
		  echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'">';
		  $tv = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro'), true);
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


