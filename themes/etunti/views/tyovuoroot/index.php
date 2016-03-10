<?php


/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */
/*
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
*/

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




<?php if(count(Yii::app()->session['TekijaVuoro']) > 0 and Yii::app()->session['TekijaVuoro'][0] != 0) : ?>

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

?>



<div class="row">
 <div class="row col-sm-4">
  <form action="index" id="yhtveto" method="POST">

   <div class="form-inline">
   <?php
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

 </div><div class="col-sm-5">


   <div class="form-inline">
     <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == 1 ? $wkMaara : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>"><i class="fa fa-arrow-left"></i></a>

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
     <a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week == $wkMaara ? 1 : 1 + $week).'&year='.($week == $wkMaara ? 1 + $year : $year); ?>"><i class="fa fa-arrow-right"></i></a> 

   </div>


 </div><div class="col-sm-3">

 	<div class="pull-right row">
 	  <div class="form-inline row">
		<div id="trash"></div> 
		<div id="clear"></div>
		<div class="btn btn-primary fa fa-plus" id="uusiTilaus"></div>
		<div class="btn btn-primary fa fa-calendar-plus-o" id="autoInsert"></div>
		<div class="btn btn-primary fa fa-calendar-minus-o" id="autoRemove"></div>
		<!--<a class="btn btn-primary ad ad-screen-full myBgColors" href="/index.php/tyovuoroot/index?fullscreen=true"></a>-->
		<div class="btn" id="vkolopput"><?php echo Yii::t('main', 'Viikonloput'); ?></div>
 	  </div>
 	</div>

 </div>
</div>

<br>



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

<div class="row table-responsive">
  <table class="table table-bordered small">
     <thead class="">
     <tr>
	<th class="myBgColors">Nimi</th>
        <?php
	for($day= 1; $day <= $numDays; $day++)
	{
  	  $d = strtotime($year ."W". $week . $day);
	  $date = date('d.m',$d);
	  echo '<th class="myBgColors">'.$paivat[date('N',$d)].', '.$date.'</th>';
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
		    	'.$t->tekijan_nimi.'
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
	     echo '<td id="'.$did.'_'.$t->id.'" valign="top">';
 	     $did = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$t->id,'from'=>'tvuoro'), true);
	     echo json_decode($did, true);
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
</div>

<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>




