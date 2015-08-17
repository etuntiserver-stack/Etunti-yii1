<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */

  if(Yii::app()->request->getPost('etsi_month') == 'kaikki')
    unset(Yii::app()->session['etsi_month']);
  if(Yii::app()->request->getPost('etsi_month') and Yii::app()->request->getPost('etsi_month') != 'kaikki')
  {
    Yii::app()->session['etsi_month'] = Yii::app()->request->getPost('etsi_month');
  }


$from = date("d.m.Y",strtotime(Yii::app()->session['etsi_month']));
$to = date("d.m.Y",strtotime(Yii::app()->session['etsi_month']." +1 month"));

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
	$dateDiff = dateDiff($from, $to);

?>


<div class="row">
  <div class="row col-sm-2">
   <input type="month" class="btn btn-info form-control etsi_month" value="<?php echo Yii::app()->session['etsi_month']; ?>">
  </div>
</div>
<br>
<div class="row tvuoro">
  <table class="table table-striped table-condensed table-bordered">
     <thead>
     <tr>
     <th></th>
        <?php 
	$tt = Tyontekijat::model()->findAll("aktiivinen = '1'",array('select'=>'id,tekijan_nimi'));

	foreach($tt as $t){
	  echo '<th><div style="width:200px;">';
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

	$columnDate = date("N/d.m",strtotime($date));
	$explColDate = explode("/",$columnDate);

  	echo '<tr>';
  	echo '<td class="fixed-column"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].'</b></td>';

	foreach($tt as $t){
	  echo '<td><div class="small" style="white-space: nowrap;width:200px;min-height:70px">';
		$tv = Tyovuoroot::model()->findAll("tid = '".$t->id."' and pvm = '".$date."' ",array('select'=>'kohde')); 
		foreach($tv as $tvVal)
		{
		$k = Kohteet::model()->findbypk($tvVal->kohde);

	  	    $strlen = strlen($k['osoite']);

	     	  if($strlen > 18)
	  	    $k['osoite'] = substr($k['osoite'],0,18).'..';
	   	  else
		    $k['osoite'] = $k['osoite'];

		  if($tvVal->alku > 0 and $tvVal->loppu > 0)
		    $al = $tvVal->alku.'-'.$tvVal->loppu;
		  else
		    $al = '';

		  echo '<a href="#" class="link tv_edit" tvid="'.$tvVal->id.'">'.$al.' '.$k['osoite'].'</a><br>';
		}

	  echo '</div></td>';
	}


  	echo '</tr>';

  	}
        ?>
     </tbody>  
  </table>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
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



$(".etsi_month").on('change', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_month" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".tv_edit").click(function(){

	var thisVal = $(this).attr("tvid");

        $.ajax({
           url: 'update?id='+thisVal,
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});

});
</script>
