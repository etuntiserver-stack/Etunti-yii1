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
.well.fullRivi{
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

    echo '<select name="ilman[]" class="mult ilman"  multiple="multiple"  title="Ei lasketa">';
    echo '<option value="Lounastauko" '.$lounas.'>Lounastauko</option>';
    echo '<option value="MATKA" '.$matka.'>MATKA</option>';
    echo '</select>';
   ?>


                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2 col-md-offset-1">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepicker" value="<?php echo Yii::app()->session['from']; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepicker" value="<?php echo Yii::app()->session['to']; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>



<br>

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

  <table class="table table-striped">
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
  <th><?php echo Yii::t('main', 'Ma'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'Py'); ?></th>
  <th><?php echo Yii::t('main', 'El'); ?></th>
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

    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;
    echo '<td>'.$this->sprint($matka).'</td>';

    $tyoIlta = 0;
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'ilta');
    $yhtIlta += $tyoIlta;
    echo '<td>'.$this->sprint($tyoIlta).'</td>';

    $tyoYo = 0;
    $tyoYo = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'yo');
    $yhtYo += $tyoYo;
    echo '<td>'.$this->sprint($tyoYo).'</td>';

    $tyoSu = 0;
    $tyoSu = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)),'su');
    $yhtSu += $tyoSu;
    echo '<td>'.$this->sprint($tyoSu).'</td>';

    $tyoPy = 0;
    $tyoPy = $this->pyhapaivat($explTekija[0],$date,"pyhat");
    $yhtPy += $tyoPy;
    echo '<td>'.$this->sprint($tyoPy).'</td>';

    $tyoEl = 0;
    $tyoEl = $this->pyhapaivat($explTekija[0],$date,"el");
    $yhtEl += $tyoEl;
    echo '<td>'.$this->sprint($tyoEl).'</td>';





    $yhtMatkaWeek 	+= $matka;
    $yhtIltaWeek 	+= $tyoIlta;
    $yhtYoWeek 		+= $tyoYo;
    $yhtSuWeek 		+= $tyoSu;
    $yhtPyWeek 		+= $tyoPy;
    $yhtElWeek 		+= $tyoEl;
    if(isset($exLatiko[1]))
    $yhtTotpvmtid 	+= $exLatiko[1];
    if(isset($exLatikoLu[1]))
    $yhtLuetutpvmtid 	+= $exLatikoLu[1];

    $yhtSuunnittelutWeek += $suunnittelut;




    echo '</tr>';




    		$tid = $explTekija[0];

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';


  		echo '<td style="background: #669999;color: white" class="text-center viikkoRivi small myBgColors"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';

/*
		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$tid."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td style="background: #669999;color: white; text-align:center" class="viikkoRivi small myBgColors" id="vk_'.date("W",strtotime($date)).'_'.$tid.'">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$tid,'viikko'=>$vko,'year'=>$year),true);

		  $cl = '';
		  if(	(int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		  )
		  $cl = 'class="btn btn-xs btn-danger"';

		  echo '<span '.$cl.'>'.$kokoViikko. '<br>('.$vktyoaika.')</span>';

		  echo '</td>';
*/

    	if(in_array('2',$tas))
    	{

		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtSuunnittelut).'<br>('.$this->num($yhtSuunnittelut).')</td>';
	}

		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtLuetutpvmtid).'<br>('.$this->num($yhtLuetutpvmtid).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtTotpvmtid).'<br>('.$this->num($yhtTotpvmtid).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors"></td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtMatkaWeek).'<br>('.$this->num($yhtMatkaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtIltaWeek).'<br>('.$this->num($yhtIltaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtYoWeek).'<br>('.$this->num($yhtYoWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtSuWeek).'<br>('.$this->num($yhtSuWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtPyWeek).'<br>('.$this->num($yhtPyWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtElWeek).'<br>('.$this->num($yhtElWeek).')</td>';
	    echo '</tr>';


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
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas) and isset($explTekija[0])){

  //$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid','from'=>Yii::app()->session['from'], 'to'=>Yii::app()->session['to']),true);

  echo '<th><center>'.sprint($yhtSuunnittelutWeek).'</center></th>';
  }
  ?>

  <?php
  $total_luettu = 0;
  $total_luettu = $this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true);
  echo '<th><center>'.sprint($total_luettu).'</center></th>';
  ?>

  <?php
  $total_toteutu = $this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true);
  echo '<th><center>'.sprint($total_toteutu).'</center></th>';
  ?>

  <th></th>
  <th><?php echo $this->sprint($yhtMatka); ?></th>
  <th><?php echo $this->sprint($yhtIlta); ?></th>
  <th><?php echo $this->sprint($yhtYo); ?></th>
  <th><?php echo $this->sprint($yhtSu); ?></th>
  <th><?php echo $this->sprint($yhtPy); ?></th>
  <th><?php echo $this->sprint($yhtEl); ?></th>
  </tr>
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


$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });


});
</script>
