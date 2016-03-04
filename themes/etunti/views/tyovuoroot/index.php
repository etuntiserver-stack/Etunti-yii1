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
        	$criteria->order = "tekijan_nimi";
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
td .latikkoAsetukset{
	min-width: 70px;
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
	//border-bottom: 0px #ddd solid;
	margin-bottom: 2px;
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
<input type="hidden" id="korko" value="350">

        <div class="tray-center">

	   <?php echo CHtml::link('','/index.php/tyovuoroot/index?fullscreen=true',array('class'=>'pull-right btn btn-primary myBgColors btn-sm ad ad-screen-full')); ?>
	   <h2 class="myBgColors p5"> <i class="fa fa-table"></i> <?php echo Yii::t('main','Työvuorot'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">



   <div class="pull-right">
	<div class="btn btn-default btn-group" id="autoInsert">
		<?php echo Yii::t('main', 'Lisää toistuvia työvuoroja'); ?></div>
	<div class="btn btn-danger btn-group" id="autoRemove">
		<?php echo Yii::t('main', 'Poista toistuvia työvuoroja'); ?></div>
   </div>


<div class="row">
  <form action="#" id="yhtveto" class="form-inline" method="POST">

   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>

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
   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>

</div>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

<?php else: ?>
<input type="hidden" id="korko" value="140">
<?php endif; ?>



<?php if(count(Yii::app()->session['Tekija']) > 0 and Yii::app()->session['Tekija'][0] != 0) : ?>

<?php

  $paivat=array(
	1=>'Ma',
	2=>'Ti',
	3=>'Ke',
	4=>'To',
	5=>'Pe',
	6=>'La',
	7=>'Su',
	);


  $wkMaara = 53;
  $year = (isset($_GET['year'])) ? $_GET['year'] : date("Y", strtotime($from));
  $week = (isset($_GET['week'])) ? $_GET['week'] : date('W', strtotime($from));

  if($week > $wkMaara) {
    $year++;
    $week = 1;
  } elseif($week < 1) {
    $year--;
    $week = $wkMaara;
  }
    $week = sprintf("%02d", $week);

?>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="pull-right">
			<i id="trash"></i> 
			<i id="clear"></i>
                 </div>

                 <div class="row">

<center>
<h2>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? $wkMaara : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><<</a> 
  <?php echo date('d.m.Y',strtotime($year ."W".$week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?>
  <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == $wkMaara ? 1 : 1 + $week).'&year='.($week == $wkMaara ? 1 + $year : $year); ?>">>></a> 
</h2>
</center>


<div class="row tvuoro table-responsive">
  <table class="table table-bordered small">
     <thead class="">
     <tr>
	<th>Nimi</th>
        <?php
	for($day= 1; $day <= 7; $day++)
	{
  	  $d = strtotime($year ."W". $week . $day);
	  $date = date('d.m',$d);
	  echo '<td>'.$paivat[date('N',$d)].', '.$date.'</td>';
	}
        ?>
     </tr>
     </thead>
     <tbody>
        <?php
	foreach($tt as $t)
	{
	  echo '<tr>';
	  echo '<td width=1>';
 	  echo $t->tekijan_nimi;	
	  echo '</td>';

	  for($day= 1; $day <= 7; $day++)
	  {
  	     $d = strtotime($year ."W". $week . $day);
	     $date = date('d.m.Y',$d);
	     $did = date('Ymd',$d);
	     echo '<td id="'.$did.'_'.$t->id.'" valign="top">';
 	     echo $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro'));	
	     echo '</td>';
	  }
	  echo '</tr>';
	}
        ?>
     </tbody>
  </table>
</div>

                 </div>
                </div>
              </div>
            </div>


<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>


<script type="text/javascript">
$(document).ready(function(){

$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });


$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});




});
</script>


