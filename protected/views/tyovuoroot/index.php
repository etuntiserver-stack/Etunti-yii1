<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */

  if(Yii::app()->request->getPost('etsi_month') == 'kaikki')
    unset(Yii::app()->session['etsi_month']);
  if(Yii::app()->request->getPost('etsi_month') and Yii::app()->request->getPost('etsi_month') != 'kaikki')
  {
    Yii::app()->session['etsi_month'] = Yii::app()->request->getPost('etsi_month');
  }

   $pvmtid = Yii::app()->request->getParam('pvmtid', 0);
   if(!empty($pvmtid)){
	$expl = explode("_",$pvmtid);
	Yii::app()->session['etsi_month'] = date("Y-m",strtotime($expl['0']));
	?>
	<script type="text/javascript">
	$(document).ready(function(){
	
	  $('#<?php echo $pvmtid; ?>').addClass("alert alert-info");
	
	});
	</script>
	<?php
   }

if(!isset(Yii::app()->session['etsi_month']))
	Yii::app()->session['etsi_month'] = date("Y-m");

$from = date("d.m.Y",strtotime(Yii::app()->session['etsi_month']));
$to = date("d.m.Y",strtotime(Yii::app()->session['etsi_month']." +1 month"));

if(!isset($from) or empty($from))
exit;


function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
	$dateDiff = dateDiff($from, $to);

echo Yii::app()->session['copymove'];

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
  <div class="row col-sm-2">
   <input type="month" class="btn btn-info form-control etsi_month" value="<?php echo Yii::app()->session['etsi_month']; ?>">
  </div>
  <div class="col-sm-2"><i id="trash"></i> <i id="clear"></i></div>
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


<?php
/*
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){

        $('a.drag').draggable({
		appendTo: 'body',
		containment: 'parent',
		scroll: false,
                helper : 'clone',
               // opacity : 0.5,
		cursor: "pointer",
		//axis:        'x'
        });
        

        $('div.drop').droppable({
                tolerance : 'fit',
                accept : 'div.drop',
                drop : function(event, ui) {
                        $(this).append(ui.draggable);

		var dragID = $(ui.draggable).attr("drID");

                $.ajax({
                    type: "POST",
                    url: "index.php?r=tehtava/tehtava_ajax",
		    data: {"draggableID" : dragID, "dr" : '1' } ,
                    success: function (data) {
                        $('#result').html(data);
                    }
                });

                }

        });



});
</script>
*/
?>
