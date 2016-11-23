<div class="row">
<?php ini_set("max_execution_time", "120"); ?>
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteuma'),
);

?>
<style>
.did.fullRivi{
	height: 100%;
	margin-bottom: 2px;
	border:1px #ccc solid;
	padding:3px 7px;
	background: white;
	border-radius:5px;
}
.oikeallaPlusV{
	display:none;
}
</style>

        <!-- begin: .tray-center -->
        <div class="tray-center">


   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'TUNTIEN HYVÄKSYNTÄ'); ?> 
	      </h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field select">

   <?php
    $criteria = new CDbCriteria();
    $criteria->order = " tekijan_nimi ";
    $criteria->condition = " aktiivinen=1 ";
    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="tekija" id="nimi" class="gui-input">';
    $explTekija = explode("//",Yii::app()->session['tekija']);

    if(isset($explTekija[0]) and isset($explTekija[1])){
       echo '<option value="'.$explTekija[0].'//'.$explTekija[1].'">'.$explTekija[1].'</option>';
    } else {
       echo '<option value="">'.Yii::t('main', 'Työntekijät').'</option>';
    }

    foreach($list as $key=>$val){
    echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

   <?php
    $lounas = '';
    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
    $matka = '';
    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

    echo '<select name="ilman[]" class="selectpicker ilman"  multiple="multiple"  title="'.Yii::t('main', 'Ei lasketa').'">';
    echo '<option value="Lounastauko" '.$lounas.'>'.Yii::t('main', 'Lounastauko').'</option>';
    echo '<option value="MATKA" '.$matka.'>'.Yii::t('main', 'Matka').'</option>';
    echo '</select>';
   ?>


                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2 col-md-offset-1">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>




<?php if(Yii::app()->session['tekija']) : ?>

<?php

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

		if(isset(Yii::app()->session['from']))
		$from = date("d.m.Y",strtotime(Yii::app()->session['from']));
		if(isset(Yii::app()->session['to']))
		$to = date("d.m.Y",strtotime(Yii::app()->session['to']));

		$dateDiff = dateDiff($from, $to);
?>

  <div class="panel heading-border">
   <div class="panel-body">

  <table class="table" cellspacing="0" cellpadding="0">
  <thead class="myBgColors">
  <tr>
  <th width=1><?php echo Yii::t('main', 'Pvm'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php 
  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $yhtMatka 	= 0;
  $yhtIlta 	= 0;
  $yhtYo 	= 0;
  $yhtSu 	= 0;
  $yhtMatkaWeek = 0;
  $yhtIltaWeek 	= 0;
  $yhtYoWeek	= 0;
  $yhtSuWeek	= 0;
  $yhtTotpvmtid	= 0;
  $yhtLuetutpvmtid = 0;
  $viikkoBreak 	= false;

  $tyoPy 	= 0;
  $yhtPy	= 0;
  $tyoEl 	= 0;
  $yhtEl	= 0;
  $yhtPyWeek	= 0;
  $yhtElWeek	= 0;

  $suunnittelut = 0;
  $yhtSuunnittelut = 0;
  $yhtSuunnittelutWeek = 0;

  $yhteensaLuetut = 0;
  $yhteensaToteutuneet = 0;

  $mobile = Yii::app()->createController('Mobile');
  $sl = 0;
  $spl = 0;
  $ls = 0;
  $vl = 0;


  for ($i = 0; $i <= $dateDiff; $i++) 
  {
  
    $plus = "+$i day";
    $date = '';
    $date = date("d.m.Y",strtotime($from." ".$plus));

    $columnDate = date("N/d.m",strtotime($date));
    $explColDate = explode("/",$columnDate);
    $did = date("Ymd",strtotime($date));

    echo '
    <tr>
  	<td width=1 class="small">'.$arrDate[$explColDate[0]].'<br>'.date("d.m",strtotime($date)).'</td>';
  
    if(in_array('2',$tas))
    {
	$dido = '';
	$dido = $this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili','yhteensa'=>true),true);
	
	$dido = explode("//", json_decode($dido, true));
	if(isset($dido[1]))
	{
		$didoResult = $dido[0];

		$suunnittelut = 0;
		$suunnittelut = $dido[1];
		$yhtSuunnittelut += $suunnittelut;
	} else {
		$didoResult = 0;
	}

    	echo '<td>'.$didoResult.'</td>';
    }

    echo '<td>';
	   $luetutpvmtid = $this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
	   $exLatikoLu = explode('explode999', $luetutpvmtid);
	   echo $exLatikoLu[0];

    echo '</td>';

    echo '<td id="'.$did.'_'.$explTekija[0].'">';

	   $totpvmtid = $this->renderPartial('totpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
	   $exLatiko = explode('explode999', $totpvmtid);
	   echo $exLatiko[0];

    echo '</td>

  	<td id="yht_'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($date)),'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    echo '</tr>';



    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;

    $tyoIlta = 0;
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'ilta');
    $yhtIlta += $tyoIlta;

    $tyoYo = 0;
    $tyoYo = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'yo');
    $yhtYo += $tyoYo;

    $tyoSu = 0;
    $tyoSu = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'su');
    $yhtSu += $tyoSu;

    $tyoPy = 0;
    $tyoPy = $this->pyhapaivat($explTekija[0],$date,"pyhat");
    $yhtPy += $tyoPy;

    $tyoEl = 0;
    $tyoEl = $this->pyhapaivat($explTekija[0],$date,"el");
    $yhtEl += $tyoEl;

    $sl = $mobile[0]->TidfromtoSL($date,$date,$explTekija[0]);
    $spl = $mobile[0]->TidfromtoSPL($date,$date,$explTekija[0]);
    $ls = $mobile[0]->TidfromtoLS($date,$date,$explTekija[0]);
    $vl = $mobile[0]->TidfromtoVuosiloma($date,$date,$explTekija[0]);


    echo '
	<tr><td colspan="5">
		<table class="table table-bordered" cellspacing="0" cellpadding="0">
		 <thead>
		  <tr>
		   <th>'.Yii::t('main', 'Matka').'</th>
		   <th>'.Yii::t('main', 'Ilta').'</th>
		   <th>'.Yii::t('main', 'Yö').'</th>
		   <th>'.Yii::t('main', 'Su').'</th>
		   <th>'.Yii::t('main', 'Py').'</th>
		   <th>'.Yii::t('main', 'El').'</th>
		   <th>'.Yii::t('main', 'SL').'</th>
		   <th>'.Yii::t('main', 'SPL').'</th>
		   <th>'.Yii::t('main', 'LS').'</th>
		   <th>'.Yii::t('main', 'VL').'</th>
		  </tr>
		 </thead>
		  <tr>
		   <td>'.$this->sprint($matka).'</td>
		   <td>'.$this->sprint($tyoIlta).'</td>
		   <td>'.$this->sprint($tyoYo).'</td>
		   <td>'.$this->sprint($tyoSu).'</td>
		   <td>'.$this->sprint($tyoPy).'</td>
		   <td>'.$this->sprint($tyoEl).'</td>
		   <td>'.$this->sprint($sl).'</td>
		   <td>'.$spl.'</td>
		   <td>'.$this->sprint($ls).'</td>
		   <td>'.$vl.'</td>
		  </tr>
		</table>
	</td></tr>';



    $yhtMatkaWeek 	+= $matka;
    $yhtIltaWeek 	+= $tyoIlta;
    $yhtYoWeek 		+= $tyoYo;
    $yhtSuWeek 		+= $tyoSu;
    $yhtPyWeek 		+= $tyoPy;
    $yhtElWeek 		+= $tyoEl;
    if(isset($exLatiko[1])){
		$yhtTotpvmtid 		+= $exLatiko[1];
		$yhteensaToteutuneet	+= $exLatiko[1];
    }
    if(isset($exLatikoLu[1])){
		$yhtLuetutpvmtid 	+= $exLatikoLu[1];
		$yhteensaLuetut 	+= $exLatikoLu[1];
    }

    $yhtSuunnittelutWeek += $suunnittelut;










    		$tid = $explTekija[0];

	    if(date('N', strtotime($date)) == 7)
	    {

    echo '
	<tr><td colspan="5">
		<table class="table" cellspacing="0" cellpadding="0">
		 <thead class="myBgColors">
		  <tr>
		   <th></th>
		   <th>'.Yii::t('main', 'Suunnitellut viikko').'</th>
		   <th>'.Yii::t('main', 'Luetut viikko').'</th>
		   <th>'.Yii::t('main', 'Toteutuneet viikko').'</th>
		   <th>'.Yii::t('main', 'Matka viikko').'</th>
		   <th>'.Yii::t('main', 'Ilta viikko').'</th>
		   <th>'.Yii::t('main', 'Yö viikko').'</th>
		   <th>'.Yii::t('main', 'Su viikko').'</th>
		   <th>'.Yii::t('main', 'Py viikko').'</th>
		   <th>'.Yii::t('main', 'El viikko').'</th>
		  </tr>
		 </thead>
		  <tr>
		   <td><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>
		   <td>'.$this->sprint($yhtSuunnittelut).'<br>('.$this->num($yhtSuunnittelut).')</td>
		   <td>'.$this->sprint($yhtLuetutpvmtid).'<br>('.$this->num($yhtLuetutpvmtid).')</td>
		   <td>'.$this->sprint($yhtTotpvmtid).'<br>('.$this->num($yhtTotpvmtid).')</td>
		   <td>'.$this->sprint($yhtMatkaWeek).'<br>('.$this->num($yhtMatkaWeek).')</td>
		   <td>'.$this->sprint($yhtIltaWeek).'<br>('.$this->num($yhtIltaWeek).')</td>
		   <td>'.$this->sprint($yhtYoWeek).'<br>('.$this->num($yhtYoWeek).')</td>
		   <td>'.$this->sprint($yhtSuWeek).'<br>('.$this->num($yhtSuWeek).')</td>
		   <td>'.$this->sprint($yhtPyWeek).'<br>('.$this->num($yhtPyWeek).')</td>
		   <td>'.$this->sprint($yhtElWeek).'<br>('.$this->num($yhtElWeek).')</td>
		  </tr>
		</table>
	</td></tr>';


		$yhtMatkaWeek 	= 0;
		$yhtIltaWeek 	= 0;
		$yhtYoWeek 	= 0;
		$yhtSuWeek 	= 0;
		$yhtPyWeek 	= 0;
		$yhtElWeek 	= 0;
		$yhtTotpvmtid 	= 0;
	 	$yhtLuetutpvmtid = 0;
		$yhtSuunnittelut = 0;

	    }



  }
  ?>

  </tbody>
  <tfoot>


	<tr><td colspan="5">
		<table class="table" cellspacing="0" cellpadding="0">
		 <thead class="myBgColors">
		  <tr>
		   <th><?php echo Yii::t('main', 'Yhteensä Suunnitellut'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Luetut'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Toteutuneet'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Matka'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Ilta'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Yö'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Su'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä Py'); ?></th>
		   <th><?php echo Yii::t('main', 'Yhteensä El'); ?></th>
		  </tr>
		 </thead>
		  <tr>
		   <td><?php echo $this->sprint($yhtSuunnittelutWeek); ?></td>
		   <td><?php echo $this->sprint($yhteensaLuetut); ?></td>
		   <td><?php echo $this->sprint($yhteensaToteutuneet); ?></td>
		   <td><?php echo $this->sprint($yhtMatka); ?></td>
		   <td><?php echo $this->sprint($yhtIlta); ?></td>
		   <td><?php echo $this->sprint($yhtYo); ?></td>
		   <td><?php echo $this->sprint($yhtSu); ?></td>
		   <td><?php echo $this->sprint($yhtPy); ?></td>
		   <td><?php echo $this->sprint($yhtEl); ?></td>
		  </tr>
		</table>
	</td></tr>

  </tfoot>

  </table>

   </div>
  </div>

<?php endif; ?>

</div>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>


	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>
	<?php Yii::app()->clientScript->registerPackage('toteuma'); ?>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/totrivi_poista.js"></script>

<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#yhtveto").submit();
});

$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();
  var nimi = $("#nimi").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (!nimi) {
        $('#nimi').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }


});




});
</script>
