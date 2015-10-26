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
body{
    overflow-y: hidden;
}
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


   <!--<input type="text" id="totalForCut" class="form-control">-->

   <div class="row pull-right">
	<i id="trash"></i> 
	<i id="clear"></i> 
	<i class="glyphicon glyphicon-download-alt btn btn-success btn-sm btn-group" id="autoInsert"></i>
	<i class="glyphicon glyphicon-new-window btn btn-danger btn-sm btn-group" id="autoRemove"></i>
   </div>

<div class="row">
  <form action="#" id="yhtveto" class="form-inline" method="POST">

   <a href="#" id="deselAll" class="btn btn-default btn-sm glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="btn btn-default btn-sm glyphicon glyphicon-plus"></a>  

   <?php
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   $criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="Tekija[]" id="tyontekijat" class="selectpicker btn-sm" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>

</div>


<br>

<?php if(!empty($from) and !empty($to) and count(Yii::app()->session['Tekija']) > 0 and Yii::app()->session['Tekija'][0] != 0) : ?>
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
  		echo '<td style="background: #669999;color: white" class="text-center viikkoRivi fixed-column"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';
		foreach($tt as $t){
		 $vktyoaika = '--:--';
		 $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td style="background: #669999;color: white" class="viikkoRivi text-center" id="vk_'.date("W",strtotime($date)).'_'.$t->id.'">';
		  $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$t->id,'viikko'=>date("W",strtotime($date))));
		  echo '('.$vktyoaika.')';
		  echo '</td>';
		}
	    echo '</tr>';
	    }

  	}
        ?>
     </tbody>
     <tfoot>
        <?php
  	    echo '<tr>';
  		echo '<td style="background: #669999;color: white" class="text-center viikkoRivi fixed-column"></td>';
		foreach($tt as $t){
		  echo '<td style="background: #669999;color: white" class="text-center viikkoRivi fromto_'.$t->id.'" />';
		  $this->renderPartial('//tyovuoroot/fromto',array('tid'=>$t->id));
		  echo '</td>';
		}
	    echo '</tr>';
        ?>
     </tfoot>
  </table>
</div>
<?php endif; ?>



	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('fixedTable'); ?>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>


<script type="text/javascript">
$(document).ready(function(){


$('.selectpicker').selectpicker({
      style: 'btn-default',
      //size: 4
});

$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});



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


