<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "60");
?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

            <h2 class="myBgColors p10"> <i class="fa fa-home"></i> <?php echo Yii::t('main', 'Tuntiyhteenveto asiakkaat'); ?> </h2>

   	    <form id="yhtveto" action="#" class="form-inline" method="GET">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				$postvalue = '';
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; }
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo $from; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo $to; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>




<?php if(isset($_GET['yrityksen_nimi']) and $from and $to) : ?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped small">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th><?php echo Yii::t('main', 'Suunniteltut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  </tr>
  </thead>
  <?php
  $sunYht = 0;
  $luetutYht = 0;
  $toteutuneetYht = 0;
  $date = $from;

  $criteria = new CDbCriteria();
  $criteria->condition = " 
	yrityksen_nimi='".$_GET['yrityksen_nimi']."' OR yhteyshenkilo='".$_GET['yrityksen_nimi']."'
  ";
  $asiakas = Asiakkaat::model()->find($criteria);
  if(!isset($asiakas->id)){ die('Asiakas ei löydy.'); }

  $suunnitelut = $this->AsiakasPvmLuTotSuunArray($asiakas->id, $from, $to, 'suunnitelut');
  $luetut = $this->AsiakasPvmLuTotSuunArray($asiakas->id, $from, $to, 'luetut');
  $toteutuneet = $this->AsiakasPvmLuTotSuunArray($asiakas->id, $from, $to, 'toteutuneet');

  while (strtotime($date) <= strtotime($to)) {
	echo '<tr>';
	echo '<td>'.$date.'</td>';
	echo '<td>';
	foreach($suunnitelut as $item){
	    if($item->pvm == $date){
		echo '<div class="row"><div class="col-sm-12">
		<b>'.$item->osoiteById.'</b> <div class="pull-right">'.date("H:i", strtotime($item->alku)).'-'.date("H:i", strtotime($item->loppu)).'
		</div></div></div>';
	    }
	}
	echo '</td>';
	echo '<td>';
	foreach($luetut as $item){
	    if(date("d.m.Y", strtotime($item->aloitan)) == $date){
		echo '<div class="row"><div class="col-sm-12">
		<b>'.$item->kohde_kannasta.'</b> <div class="pull-right">'.date("H:i", strtotime($item->aloitan)).'-'.date("H:i", strtotime($item->loppui)).'
		</div></div></div>';
	    }
	}
	echo '</td>';
	echo '<td>';
	foreach($toteutuneet as $item){
	    if(date("d.m.Y", strtotime($item->aloitan)) == $date){
		echo '<div class="row"><div class="col-sm-12">
		<b>'.$item->kohde_kannasta.'</b> <div class="pull-right">'.date("H:i", strtotime($item->aloitan)).'-'.date("H:i", strtotime($item->loppui)).'
		</div></div></div>';
	    }
	}
	echo '</td>';
	echo '</tr>';
	$date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
  }
  ?>
<?php /*
  <tfoot>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo $this->sprint($sunYht); ?></th>
  <th><?php echo $this->sprint($luetutYht); ?></th>
  <th><?php echo $this->sprint($toteutuneetYht); ?></th>
  </tr>
  </tfoot>
*/ ?>
  </table>
 </div>
</div>


   </div>
  </div>
</div>
<?php endif; ?>
