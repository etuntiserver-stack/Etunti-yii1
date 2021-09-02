<?php
/* @var $this VuosilomatController */
/* @var $dataProvider CActiveDataProvider */


if(!isset($_GET['monday'])){
	$monday = '';
	$week 	= date("Y-W");
	$start 	= new DateTime ('previous monday', new DateTimeZone ('UTC'));
	$end 	= new DateTime ('previous monday +7 days', new DateTimeZone ('UTC'));
} else {
	$monday = '<input type="hidden" name="monday" value="'.$_GET['monday'].'">';
	$week 	= date("Y-W",strtotime($_GET['monday']));
	$start 	= new DateTime ($_GET['monday']);
	$end 	= new DateTime ($_GET['monday'].' +7 days');
}


$next		= date("Y-m-d",strtotime($start->format('Y-m-d'). " +1 week monday"));
$previous 	= date("Y-m-d",strtotime($start->format('Y-m-d'). " -1 week monday"));


$interval = new DateInterval ('P1D');
$range = new DatePeriod ($start, $interval, $end);

$criteria = new CDbCriteria();
$criteria->condition = " aktiivinen=1 ";

// <-- Return order etu ja sukunimella
$site = Yii::app()->createController('Site');
$criteria = $site[0]->etuSukunimiCriteria($criteria);
//     Return order etu ja sukunimella -->

// <-- Tyoryhmat
$tt = Yii::app()->createController('Tyontekijat');
$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
$ids = implode(",", $tt_arr);
if( count($tt_arr) > 0 ){
	$criteria->addCondition (" id IN ($ids)");
}
//    Tyoryhmat -->

$t = Tyontekijat::model()->findAll($criteria);

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<!-- begin: .tray-center -->
<div class="tray-center">
      <h2 class="myBgColors p10"> <i class="fa fa-calendar-check-o"></i> <?php echo Yii::t('main', 'Hyväksytyt tunnit (VKO)'); ?></h2>

</div>
<!-- end: .tray-center -->

<div class="row" id="haku">
    <div class="col-sm-12 form-inline">
    	<form methot="GET">
		   	<b class="form-control form-group myBgColors"><a href="vko?monday=<?php echo $previous; ?><?= (isset($_GET['tunnit']))? '&tunnit='.$_GET['tunnit']:''?>">
				<<</a> <?php echo $week; ?> <a href="vko?monday=<?php echo $next; ?><?= (isset($_GET['tunnit']))? '&tunnit='.$_GET['tunnit']:''?>">>></a>
		   	</b>
			<?=$monday?>
	   		<select name="tunnit" class="form-group form-control">
				<option value="hyvaksytyt" <?= (isset($_GET['tunnit']) and $_GET['tunnit']=='hyvaksytyt')? 'selected':''?>><?=Yii::t('main', 'Hyväksytyt tunnit')?></option>
				<option value="hyvaksynta" <?= (isset($_GET['tunnit']) and $_GET['tunnit']=='hyvaksynta')? 'selected':''?>><?=Yii::t('main', 'Hyväksyntä')?></option>
				<option value="luetut" <?= (isset($_GET['tunnit']) and $_GET['tunnit']=='luetut')? 'selected':''?>><?=Yii::t('main', 'Luetut')?></option>
			</select>
		   <div class="form-group input-group-btn">
				<button id="send" class="btn btn-primary btn-group myBgColors">OK</button>
		   </div>
	   </form>
    </div>
</div>
<br>


<div class="admin-form">
  <div class="panel heading-border">
    <div class="panel-body bg-light">
     <div class="row">


		<div class="table-responsive" id="taulukkoPaa">
		<TABLE id="verkko" class="table table-bordered">
		<?php 
		echo '<thead><TR>';
		echo '<TH>Nimi</TH>';

		foreach ($range as $date) {
			echo '<th>'.$date->format ('d.m.Y')."</th>";
		}

		echo '<TH>Yht.</TH>';
		echo '</TR></thead>';

		$mobile = Yii::app()->createController('Mobile');

		$hyv_arr 	= [3];
		$method 	= 0;
		
		if(!isset($_GET['tunnit']) or isset($_GET['tunnit']) and $_GET['tunnit'] == 'hyvaksytyt')
			$method 	= 3;
		elseif(isset($_GET['tunnit']) and $_GET['tunnit'] == 'hyvaksynta')
			$method 	= 2;
		elseif(isset($_GET['tunnit']) and $_GET['tunnit'] == 'luetut')
			$method 	= 1;

        $tids = [];
        foreach ($t as $data)
          $tids[] = $data->id;

		$data_tunnit 	= $mobile[0]->TidfromtoMobiiliAll($start->format('Y-m-d'), $end->format('Y-m-d'), $tids, $hyv_arr, $method, false, 0, true, null, null, false);
		/*
		echo '<pre>';
		print_r($data_tunnit);
		echo '</pre>';
		exit;
		*/
		foreach($t as $v)
		{
			$yht = 0;
			echo '<TR>';
			echo '<TD>'.$this->etuSukunimi($v->id).'</TD>';

			foreach ($range as $date)
			{
				$pvm 		= $date->format('Y-m-d');
				$maara 		= (isset($data_tunnit[$pvm][$v->id])) ? $data_tunnit[$pvm][$v->id] : 0;;
				$yht 		+= $maara;

				$cl = "";
				if($maara < 18000 and $maara > 0)
				$cl = "btn btn-xs btn-warning";
				elseif($maara > 28800 and $maara > 0)
				$cl = "btn btn-xs btn-danger";

				echo '<TD class="text-small" style="font-size:90%"><span class="'.$cl.'">'.$this->sprint($maara).'</span></TD>';

			}
			echo '<TD class="text-small"><b>'.$this->sprint($yht).'</b></TD>';
			echo '<TR>';
		}
		?>
		</TABLE>

		</div>


     </div>
    </div>
  </div>
</div>



