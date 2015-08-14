<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Työvuoroot'),
);


Yii::app()->session['from'] = '01.07.2015';
Yii::app()->session['to'] = '01.08.2015';

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
	$dateDiff = dateDiff(Yii::app()->session['from'], Yii::app()->session['to']);

?>





    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.rawgit.com/DataTables/FixedColumns/RELEASE_2_0_3/media/js/FixedColumns.js"></script>

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
    	for ($i = 0; $i <= $dateDiff; $i++) {

	$plus = "+$i day";
	$date = date("d.m.Y",strtotime(Yii::app()->session['from']." ".$plus));

  	echo '<tr>';
  	echo '<td class="fixed-column">'.$i.'</td>';

	foreach($tt as $t){
	  echo '<td><div class="small" style="white-space: nowrap;width:200px;min-height:70px">';
		$tv = Tyovuoroot::model()->find("tid = '".$t->id."' and pvm = '".$date."' ",array('select'=>'kohde')); 
		$k = Kohteet::model()->findbypk($tv['kohde']);

	  	    $strlen = strlen($k['osoite']);

	     	  if($strlen > 18)
	  	    $k['osoite'] = substr($k['osoite'],0,18).'..';
	   	  else
		    $k['osoite'] = $k['osoite'];

		  if($tv['alku'] > 0 and $tv['loppu'] > 0)
		    $al = $tv['alku'].'-'.$tv['loppu'];
		  else
		    $al = '';

		  echo '<a href="#" class="link">'.$al.' '.$k['osoite'].'</a>';

	  echo '</div></td>';
	}


  	echo '</tr>';

  	}
        ?>
     </tbody>  
  </table>
</div>


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
        oSettings.oScroll.sY = tableHeight()-200; 
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
