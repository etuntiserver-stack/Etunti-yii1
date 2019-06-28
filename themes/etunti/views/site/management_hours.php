<?php
 $start    = new DateTime('-2 years');
 $end      = new DateTime('+1 month');
 $interval = new DateInterval('P1M');
 $period   = new DatePeriod($start, $interval, $end);
 $dates = array();
 foreach ($period as $dt) {
    $dates[] = $dt->format("Y-m");
 }
 if(!isset($_GET['yearmonth'])){ $this->redirect(array('management_hours', 'yearmonth' => date("Y-m"))); }
?>
<div class="row">
  <div class="col-sm-12 text-center">
   <div class="form-inline">
      <?php echo CHtml::link('<i class="fa fa-arrow-left btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="Edellinen kuukausi"></i>',array('management_hours', 'yearmonth' => date("Y-m", strtotime($_GET['yearmonth']." -1 month")))); ?>
      <select class="form-control form-group yearmonth_valinta">
	<?php foreach (array_reverse($dates) as $date): ?>
        <option value="<?=$date?>" <?=(($date == $_GET['yearmonth'])?'selected':'')?>><?=$date?></option>
	<?php endforeach; ?>
      </select>
      <?php echo CHtml::link('<i class="fa fa-arrow-right btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="Seuraava kuukausi"></i>',array('management_hours', 'yearmonth' => date("Y-m", strtotime($_GET['yearmonth']." +1 month")))); ?>
   </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $('.yearmonth_valinta').change(function(){
	window.location.href="management_hours?yearmonth="+ $("option:selected", this).val();
  });

});
</script>

<?php
	$criteria = new CDbCriteria();
       	$criteria->select = "
		MIN(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m') = '".$_GET['yearmonth']."'
		AND status=3
		AND deleted=0
	";
	$lyhyin = Mobile::model()->find($criteria);
?>

<section id="content" class="animated fadeIn">
<h3>Luetut</h3>
<div class="row mb10">
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-alert light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="tunnit"><?=$lyhyin->l_tunnit?></div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main','Lyhyin'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-info light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="tehdyttunnittanaan">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main','Pisin'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-warning light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="suunnitteltutunnittanaan">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main', 'Keskiarvo'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3 pulsar">
    <div class="panel bg-danger light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="viestittanaan">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main', 'Keskihajonta'); ?></h5>
      </div>
    </div>
  </div>
</div>
</section>

<section id="content" class="animated fadeIn">
<h3>Hyväksytyt</h3>
<div class="row mb10">
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-alert light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="tunnit">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main','Tehdyt tunnit tänään'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-info light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="tehdyttunnittanaan">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main','Tehdyt tunnit tänään'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="panel bg-warning light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="suunnitteltutunnittanaan">TEST</div></b>
        </h2>
        <h5 class="text-muted"><?php echo Yii::t('main', 'Suunniteltu tänään'); ?></h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3 pulsar">
    <div class="panel bg-danger light of-h mb10">
      <div class="pn pl20 p5">
        <div class="icon-bg">
          <i class="fa fa-clock-o"></i>
        </div>
        <h2 class="mt15 lh15">
          <b><div id="viestittanaan">TEST</div></b>
        </h2>
        <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta"><h5 class="text-muted"><?php echo Yii::t('main', 'Lukemattomat viestit'); ?></h5></a>
      </div>
    </div>
  </div>
</div>
</section>

