<?php
if( isset($_GET['haku']) ){
	// <-- Luetut
	$criteria = new CDbCriteria();
       	$criteria->limit = " 10 ";
	$criteria->order = " 
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) DESC
	";
       	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
	";
	$lu = Mobile::model()->findAll($criteria);

	$i=0;
	$tl = array();
	foreach($lu as $item){
	$tl[$item->l_tunnit] = '<b>Aloitus:</b> '.date("d.m.Y H:i", strtotime($item->aloitan)).'  <b>Lopetus:</b> '.date("d.m.Y H:i", strtotime($item->loppui)).'  <span class="text-danger">Kesto: ('.$this->sprint($item->l_tunnit).')</span> '.$this->etuSukunimi($item->tid);
	$i++; if($i>10){break;}
	}
	krsort($tl);
	//     Luetut -->

	// <-- Hyvaksytyt
	$criteria = new CDbCriteria();
       	$criteria->limit = " 10 ";
	$criteria->order = " 
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) DESC
	";
       	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";

	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->limit = " 10 ";
	$criteria->order = " 
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) DESC
	";
       	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
	";
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$i=0;
	$tt = array();
	foreach($result as $item){
	$tt[$item->l_tunnit] = '<b>Aloitus:</b> '.date("d.m.Y H:i", strtotime($item->aloitan)).'  <b>Lopetus:</b> '.date("d.m.Y H:i", strtotime($item->loppui)).'  <span class="text-danger">Kesto: ('.$this->sprint($item->l_tunnit).')</span> '.$this->etuSukunimi($item->tid);
	$i++; if($i>10){break;}
	}
	krsort($tt);
	//     Hyvaksytyt -->
}
?>


<!-- begin: .tray-center -->
<div class="tray-center">

	<h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Tunnit management'); ?></h2>

   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="hakuvoimassa">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Aikaväli')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Blaa'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
</div>

<?php if( isset($_GET['haku']) ): ?>
<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">
	<h2><?=Yii::t('main', '10 isommat kestot')?></h2>
	<table class="table table-bordered">
	<tr>
	<th><?=Yii::t('main', 'Luetut')?></th>
	<th><?=Yii::t('main', 'Hväksytyt')?></th>
	</tr>

	<tr>
	<th>
	<?php
	foreach($tl as $k=>$v){
	echo $v.'<br>';
	}
	?>
	</th>
	<th>
	<?php
	foreach($tt as $k=>$v){
	echo $v.'<br>';
	}
	?>
	</th>
	</tr>
	</table>
   </div>
  </div>
</div>
<?php endif; ?>

