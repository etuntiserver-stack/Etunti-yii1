<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "60");
?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	     <button class="btn btn-primary btn-sm myBgColors tulostataulun"><?php echo Yii::t('main', 'Tulosta'); ?></button>
	   </div>
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




<?php if(isset($_GET['from']) and isset($_GET['to'])) : ?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

  <p id="forTulostus">
	<?php 
		$asiakas_nimi = '<h3 class="text-danger">Asiakas ei esitetty</h3>';
		if(isset($asiakas->id) and $asiakas->tyyppi == 'yritys'){ $asiakas_nimi = $asiakas->yrityksen_nimi; } 
		if(isset($asiakas->id) and $asiakas->tyyppi == 'henkilo'){ $asiakas_nimi = $asiakas->yhteyshenkilo; } 
	?>
	<?=$asiakas_nimi?>, <?=$from?>-<?=$to?>
  </p>

  <table class="table table-bordered table-striped small" cellspacing="0" cellpadding="0" id="tunnit_taulu">
  <thead class="myBgColors">
  <tr>
  <th class="tdw1"><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th class="tdw2"><?php echo Yii::t('main', 'Suunniteltut tunnit'); ?></th>
  <th class="tdw3"><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th class="tdw4"><?php echo Yii::t('main', 'Hyväksytyt tunnit'); ?></th>
  </tr>
  </thead>
  <?php
  $sunYht = 0;
  $luetutYht = 0;
  $toteutuneetYht = 0;
  $date = $from;

  $suunnitelut = $this->AsiakasPvmLuTotSuunArray($asiakas_id, $from, $to, 'suunnitelut');
  $luetut = $this->AsiakasPvmLuTotSuunArray($asiakas_id, $from, $to, 'luetut');
  $toteutuneet = $this->AsiakasPvmLuTotSuunArray($asiakas_id, $from, $to, 'toteutuneet');
  $period = new DatePeriod(new DateTime(date("Y-m-d",strtotime($from))), new DateInterval('P1D'), new DateTime(date("Y-m-d",strtotime($to))));

  foreach($period as $d) {
	$date = $d->format("d.m.Y");
	$yht_s = 0;
	$body_suunnitelut = '';
	foreach($suunnitelut as $arr){
	    $item = $arr['data'];
	    if($arr['this_pvm'] == $date){
		$body_suunnitelut .= '<div class="row"><div class="col-sm-12">
		'.$item->osoiteById.' <div class="pull-right"><b>'.date("H:i", strtotime($item->alku)).'-'.date("H:i", strtotime($item->loppu)).' 
		<span class="text-success">('.$this->sprint(strtotime($item->loppu)-strtotime($item->alku)).')</span></b>
		</div></div></div>';
		$yht_s += strtotime($item->loppu)-strtotime($item->alku);
	    }
	}
	$yht_l = 0;
	$body_luetut = '';
	foreach($luetut as $item){
	    if(date("d.m.Y", strtotime($item->aloitan)) == $date){
		$body_luetut .= '<div class="row"><div class="col-sm-12">
		'.$item->kohde_kannasta.' <div class="pull-right"><b>'.date("H:i", strtotime($item->aloitan)).'-'.date("H:i", strtotime($item->loppui)).' 
		<span class="text-success">('.$this->sprint(strtotime($item->loppui)-strtotime($item->aloitan)).')</span></b>
		</div></div></div>';
		$yht_l += strtotime($item->loppui)-strtotime($item->aloitan);
	    }
	}
	$yht_t = 0;
	$body_toteutuneet = '';
	foreach($toteutuneet as $item){
	    if(date("d.m.Y", strtotime($item->aloitan)) == $date){
		$body_toteutuneet .= '<div class="row"><div class="col-sm-12">
		'.$item->kohde_kannasta.' <div class="pull-right"><b>'.date("H:i", strtotime($item->aloitan)).'-'.date("H:i", strtotime($item->loppui)).' 
		<span class="text-success">('.$this->sprint(strtotime($item->loppui)-strtotime($item->aloitan)).')</span></b>
		</div></div></div>';
		$yht_t += strtotime($item->loppui)-strtotime($item->aloitan);
	    }
	}
	if(empty($body_suunnitelut) and empty($body_luetut) and empty($body_toteutuneet)){ continue; }

	echo '<tr>';
	echo '<td><h4>'.$date.'</h4></td>';
	echo '<td style="vertical-align: top">'.$body_suunnitelut.'</td>';
	echo '<td style="vertical-align: top">'.$body_luetut.'</td>';
	echo '<td style="vertical-align: top">'.$body_toteutuneet.'</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td></td>';
	echo '<td><div class="pull-right">'.Yii::t('main', 'Yhteensä').': <span class="text-success">('.$this->sprint($yht_s).')</span></div></td>';
	echo '<td><div class="pull-right">'.Yii::t('main', 'Yhteensä').': <span class="text-success">('.$this->sprint($yht_l).')</span></div></td>';
	echo '<td><div class="pull-right">'.Yii::t('main', 'Yhteensä').': <span class="text-success">('.$this->sprint($yht_t).')</span></div></td>';
	echo '</tr>';
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
<?php endif; ?>

<script>
$(document).ready(function(){

$(document).delegate(".tulostataulun","click",function(){
	
    var divToPrint = document.getElementById('tunnit_taulu');
    var htmlToPrint = '' +
        '<style type="text/css">' +
	'.table tbody>tr>td{' +
	    	'vertical-align: top;' +
	'}' +
        'table th, table td {' +
        'border:1px solid #333;' +
        'padding:3px 5px;' +
	'font-size: 70%;' +
        '}' +
	'.tdw2, .tdw3, .tdw4{' +
	'width: 30%;' +
	'}' +
	'.tdw1{' +
	'width: 10%;' +
	'}' +

        '</style>';
    htmlToPrint += $('#forTulostus').html();
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
});

});
</script>
