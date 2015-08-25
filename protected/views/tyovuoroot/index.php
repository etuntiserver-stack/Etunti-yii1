<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */

   $pvmtid = Yii::app()->request->getParam('pvmtid', 0);
   if(!empty($pvmtid)){
	$expl = explode("_",$pvmtid);
	Yii::app()->session['from'] = date("Y-m-d",strtotime($expl['0']));
	Yii::app()->session['to'] = date("Y-m-d",strtotime($expl['0']." +1 week"));
	Yii::app()->session['tvuoroTekija'] = $expl['1'];
	?>
	<script type="text/javascript">
	$(document).ready(function(){
	
	  $('#<?php echo $pvmtid; ?>').addClass("alert alert-info");
	
	});
	</script>
	<?php
   }


		if(Yii::app()->request->getPost('tvuoroTekija'))
		Yii::app()->session['tvuoroTekija'] = Yii::app()->request->getPost('tvuoroTekija');

       		$criteria = new CDbCriteria();
        	$criteria->select = "id,tekijan_nimi";
        	$criteria->condition = " aktiivinen = '1' ";

		if(Yii::app()->session['tvuoroTekija']){
		  if(count(Yii::app()->session['tvuoroTekija']) > 1)
		    $ids = implode(",",Yii::app()->session['tvuoroTekija']);
		  else
		    $ids = Yii::app()->session['tvuoroTekija'];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}

		$tt = Tyontekijat::model()->findAll($criteria);



		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));


		$from = date("d.m.Y",strtotime(Yii::app()->session['from']));
		$to = date("d.m.Y",strtotime(Yii::app()->session['to']));


if(!isset($from) or empty($from))
exit;

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
td .latikkoAsetukset{
	width: 220px;
	white-space: nowrap;
	min-height:70px;
}
.mplus, .mcut{ 
	display: none;
	font-size: 80%;
}
td:hover .mplus, td:hover .mcut{
	display : block;
}
td .tp{
	height:20px;
}
</style>


   <input type="hidden" id="totalForCut">

<div class="row">
  <form action="#" id="yhtveto" method="POST">

   <?php
    $model=new Tyontekijat;
    $list = CHtml::listData(Tyontekijat::model()->findAll("aktiivinen = '1'",array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');

    echo '<select name="tvuoroTekija[]" class="selectpicker" multiple title="Työntekijät">';
    //if(Yii::app()->session['tvuoroTekija'])

    foreach($list as $key=>$val){
      echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <input type="date" name="from" id="from" class="btn btn-default" value="<?php echo Yii::app()->session['from']; ?>">

   <input type="date" name="to" id="to" class="btn btn-default" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>

</div>


<br>

<div class="row tvuoro">
  <table class="table table-striped table-condensed table-bordered">
     <thead>
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
	  $date = date("d.m.Y",strtotime($from." ".$plus));
	  $did = date("Ymd",strtotime($from." ".$plus));

	  $columnDate = date("N/d.m",strtotime($date));
	  $explColDate = explode("/",$columnDate);

  	    echo '<tr>';
  		echo '<td class="fixed-column"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].'</b></td>';

		foreach($tt as $t){
		  echo '<td id="'.$did.'_'.$t->id.'">';
		  $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro'));
		  echo '</td>';
		}
	    echo '</tr>';

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';
  		echo '<td style="background: #669999;color: white" class="viikkoRivi fixed-column"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';

		foreach($tt as $t){
		  echo '<td style="background: #669999;color: white" class="viikkoRivi" id="'.$did.'_'.$t->id.'">';

		  echo '</td>';
		}
	    echo '</tr>';
	    }

  	}
        ?>
     </tbody>  
  </table>
</div>




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
        oSettings.oScroll.sY = tableHeight()-230; 
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


