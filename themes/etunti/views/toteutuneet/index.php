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
.oikeallaPlusV, .tp{
	display:none;
}
.table tbody>tr>td{
    	vertical-align: top;
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
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php 
  $arrDate = array(1=>"Maanantai",2=>"Tiistai",3=>"Keskiviikko",4=>"Torstai",5=>"Perjantai",6=>"Lauantai",7=>"Sunnuntai");
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
  $sl 		= 0;
  $spl 		= 0;
  $ls 		= 0;
  $vl 		= 0;

  $yhtSPLWeek	= 0;
  $yhtSLWeek	= 0;
  $yhtLSWeek	= 0;
  $yhtVLWeek	= 0;

  $yhtSPL	= 0;
  $yhtSL	= 0;
  $yhtLS	= 0;
  $yhtVL	= 0;


  for ($i = 0; $i <= $dateDiff; $i++) 
  {
  
    $plus = "+$i day";
    $date = '';
    $date = date("d.m.Y",strtotime($from." ".$plus));

    $columnDate = date("N/d.m",strtotime($date));
    $explColDate = explode("/",$columnDate);
    $did = date("Ymd",strtotime($date));



    echo '
	<tr><td colspan="4">
		<table class="" cellspacing="0" cellpadding="0">
		  <tr>
		   <td><h3>'.$arrDate[$explColDate[0]].' '.date("d.m",strtotime($date)).'</h3></td>
		  </tr>
		</table>
	</td></tr>';

    echo '<tr>';
  

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


    echo '<td>';
	   $luetutpvmtid = $this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true);
	   $exLatikoLu = explode('explode999', $luetutpvmtid);
	   echo $exLatikoLu[0];

    echo '</td>';

    echo '<td id="'.$did.'_'.$explTekija[0].'">';

	   $totpvmtid = $this->TotPvmTid($date,$explTekija[0]);
	   echo $totpvmtid['laatikot'];

    echo '</td>';

    if($suunnittelut > $totpvmtid['toteutuneetTunnit'])
    	$eroAika = $suunnittelut-$totpvmtid['toteutuneetTunnit'];
    else
    	$eroAika = ($totpvmtid['toteutuneetTunnit']-$suunnittelut);

    echo '<td class="yhteensaPvm_'.date("W",strtotime($date)).' forFooter" id="yhteensaPvm_'.$did.'_'.$explTekija[0].'">
		'.Yii::t('main', 'Suunn.: ').'<span class="pvmSuunn" total="'.(int)$suunnittelut.'">'.$this->sprint($suunnittelut).'</span><br>
		'.Yii::t('main', 'Tot.: ').'<span class="pvmTot" total="'.(int)$totpvmtid['toteutuneetTunnit'].'">'.$this->sprint($totpvmtid['toteutuneetTunnit']).'</span><br>
		'.Yii::t('main', 'Ero aika: ').'<span class="pvmEro">'.$this->sprint($eroAika).'</span><br>
	 </td>';

    echo '</tr>';




    $matka = $totpvmtid['matkat'];
    $yhtMatka += $matka;

    $return 	= $this->IltaYoSu($explTekija[0],$date);

    if(isset($return[0])){
    $tyoIlta 	= $return[0];
    $yhtIlta 	+= $tyoIlta;
    }
    if(isset($return[1])){
    $tyoYo 	= $return[1];
    $yhtYo 	+= $tyoYo;
    }
    if(isset($return[2])){
    $tyoSu 	= $return[2];
    $yhtSu 	+= $tyoSu;
    }

    $tyoPy 	= $this->pyhapaivat($explTekija[0],$date,"pyhat");
    $yhtPy 	+= $tyoPy;

    $tyoEl 	= $this->pyhapaivat($explTekija[0],$date,"el");
    $yhtEl 	+= $tyoEl;

    $spl 	= $totpvmtid['spl']; // Palkaton
    $sl 	= $totpvmtid['sl']; // Palkallinen
    $ls 	= $totpvmtid['ls']; // Lapsen sairaus
    $vl 	= $mobile[0]->TidfromtoVuosiloma($date,$date,$explTekija[0]);


    $yhtSPL 	+= $spl;
    $yhtSL 	+= $sl;
    $yhtLS 	+= $ls;
    $yhtVL 	+= $vl;

    echo '
	<tr><td colspan="4">
		<table class="yhteensaPvmAllaTaulu_'.date("W",strtotime($date)).' forFooterAlla table table-bordered" cellspacing="0" cellpadding="0" id="yhteensaPvmAllaTaulu_'.$did.'_'.$explTekija[0].'">
		 <thead>
		  <tr>
		   <th>'.Yii::t('main', 'Matkat').'</th>
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
		   <td><span class="allaMatkat" total="'.(int)$matka.'">'.$this->sprint($matka).'</span></td>
		   <td><span class="allaIlta" total="'.(int)$tyoIlta.'">'.$this->sprint($tyoIlta).'</span></td>
		   <td><span class="allaYo" total="'.(int)$tyoYo.'">'.$this->sprint($tyoYo).'</span></td>
		   <td><span class="allaSu" total="'.(int)$tyoSu.'">'.$this->sprint($tyoSu).'</span></td>
		   <td>'.$this->sprint($tyoPy).'</td>
		   <td>'.$this->sprint($tyoEl).'</td>
		   <td><span class="allaSL" total="'.(int)$sl.'">'.$this->sprint($sl).'</span></td>
		   <td><span class="allaSPL" total="'.(int)$spl.'">'.$this->sprint($spl).'</span></td>
		   <td><span class="allaLS" total="'.(int)$ls.'">'.$this->sprint($ls).'</span></td>
		   <td>'.$vl.'</td>
		  </tr>
		</table>
	</td></tr>';


    $yhtSuunnittelutWeek += $suunnittelut;
    if(isset($totpvmtid['toteutuneetTunnit'])){
		$yhtTotpvmtid 		+= $totpvmtid['toteutuneetTunnit'];
		$yhteensaToteutuneet	+= $totpvmtid['toteutuneetTunnit'];
    }
    if(isset($exLatikoLu[1])){
		$yhtLuetutpvmtid 	+= $exLatikoLu[1];
		$yhteensaLuetut 	+= $exLatikoLu[1];
    }

    $yhtMatkaWeek 	+= $matka;
    $yhtIltaWeek 	+= $tyoIlta;
    $yhtYoWeek 		+= $tyoYo;
    $yhtSuWeek 		+= $tyoSu;
    $yhtPyWeek 		+= $tyoPy;
    $yhtElWeek 		+= $tyoEl;
    $yhtSLWeek 		+= $sl;
    $yhtSPLWeek		+= $spl;
    $yhtLSWeek 		+= $ls;
    $yhtVLWeek 		+= $vl;














    		$tid = $explTekija[0];

	    if(date('N', strtotime($date)) == 7)
	    {


    echo '
	<tr><td colspan="4">
		<table class="table" cellspacing="0" cellpadding="0">
		  <tr>
		   <td><h3>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</h3></td>
		  </tr>
		</table>
	</td></tr>';

    echo '
	<tr><td colspan="4">
		<table class="table" cellspacing="0" cellpadding="0">
		 <thead class="myBgColors">
		  <tr>
		   <th>'.Yii::t('main', 'Suunn.').'</th>
		   <th>'.Yii::t('main', 'Luetut').'</th>
		   <th>'.Yii::t('main', 'Tot.').'</th>
		   <th>'.Yii::t('main', 'Matkat').'</th>
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
		   <td class="suunnWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtSuunnittelut).'<br>'.$this->num($yhtSuunnittelut).'</td>
		   <td class="luetutWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtLuetutpvmtid).'<br>'.$this->num($yhtLuetutpvmtid).'</td>
		   <td class="totWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtTotpvmtid).'<br>'.$this->num($yhtTotpvmtid).'</td>
		   <td class="matkatWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtMatkaWeek).'<br>'.$this->num($yhtMatkaWeek).'</td>
		   <td class="iltaWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtIltaWeek).'<br>'.$this->num($yhtIltaWeek).'</td>
		   <td class="yoWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtYoWeek).'<br>'.$this->num($yhtYoWeek).'</td>
		   <td class="suWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtSuWeek).'<br>'.$this->num($yhtSuWeek).'</td>
		   <td>'.$this->sprint($yhtPyWeek).'<br>'.$this->num($yhtPyWeek).'</td>
		   <td>'.$this->sprint($yhtElWeek).'<br>'.$this->num($yhtElWeek).'</td>

		   <td class="SLWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtSLWeek).'<br>'.$this->num($yhtSLWeek).'</td>
		   <td class="SPLWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtSPLWeek).'<br>'.$this->num($yhtSPLWeek).'</td>
		   <td class="LSWeek_'.date("W",strtotime($date)).'">'.$this->sprint($yhtLSWeek).'<br>'.$this->num($yhtLSWeek).'</td>
		   <td>'.$yhtVLWeek.'</td>
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
    		$yhtSLWeek 	= 0;
		$yhtSPLWeek	= 0;
		$yhtLSWeek 	= 0;
		$yhtVLWeek 	= 0;

	    }



  }
  ?>

  </tbody>
  <tfoot>


	<tr><td colspan="4">
		<table class="table" cellspacing="0" cellpadding="0" id="yhteensaFooterTaulu">
		 <thead class="myBgColors">
		  <tr>
		   <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
		   <th><?php echo Yii::t('main', 'Suunn.'); ?></th>
		   <th><?php echo Yii::t('main', 'Luetut'); ?></th>
		   <th><?php echo Yii::t('main', 'Tot'); ?></th>
		   <th><?php echo Yii::t('main', 'Matkat'); ?></th>
		   <th><?php echo Yii::t('main', 'Ilta'); ?></th>
		   <th><?php echo Yii::t('main', 'Yö'); ?></th>
		   <th><?php echo Yii::t('main', 'Su'); ?></th>
		   <th><?php echo Yii::t('main', 'Py'); ?></th>
		   <th><?php echo Yii::t('main', 'El'); ?></th>
		   <th><?php echo Yii::t('main', 'SL'); ?></th>
		   <th><?php echo Yii::t('main', 'SPL'); ?></th>
		   <th><?php echo Yii::t('main', 'LS'); ?></th>
		   <th><?php echo Yii::t('main', 'VL'); ?></th>
		  </tr>
		 </thead>
		  <tr>
		   <td></td>
		   <td><span class="suunnFoot"><?php echo $this->sprint($yhtSuunnittelutWeek); ?></span></td>
		   <td><?php echo $this->sprint($yhteensaLuetut); ?></td>
		   <td><span class="totFoot"><?php echo $this->sprint($yhteensaToteutuneet); ?></span></td>
		   <td><span class="matkatFoot"><?php echo $this->sprint($yhtMatka); ?></span></td>
		   <td><span class="iltaFoot"><?php echo $this->sprint($yhtIlta); ?></span></td>
		   <td><span class="yoFoot"><?php echo $this->sprint($yhtYo); ?></span></td>
		   <td><span class="suFoot"><?php echo $this->sprint($yhtSu); ?></span></td>
		   <td><?php echo $this->sprint($yhtPy); ?></td>
		   <td><?php echo $this->sprint($yhtEl); ?></td>
		   <td><span class="SLFoot"><?php echo $this->sprint($yhtSL); ?></span></td>
		   <td><span class="SPLFoot"><?php echo $this->sprint($yhtSPL); ?></span></td>
		   <td><span class="LSFoot"><?php echo $this->sprint($yhtLS); ?></span></td>
		   <td><?php echo $this->sprint($yhtVL); ?></td>
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
