<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Tunnit'),
);

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="fa fa-eur"></i> <?php echo Yii::t('main', 'Palkkataulukko (Työvuoroista)'); ?> 

	<?php if(!isset($_GET['kaikki_tyontekijat'])): ?>
	<?php echo ', '.Yii::t('main', 'aktiiviset työntekijät'); ?>
	<?php echo CHtml::link(Yii::t('main', 'Näytä kaikki'),'palkkataulukko?kaikki_tyontekijat', array('class'=>'btn btn-primary')); ?>
	<?php endif; ?>

   <!-- tulostus -->
   <div class="pull-right">
    <div class="form-inline">
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
	  </form>
    </div>
   </div>
   <!-- tulostus -->
	</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo date('d.m.Y', strtotime($from)); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo date('d.m.Y', strtotime($to)); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
					'Tekija', // name
					null, // class
					'tyontekijat', // id
					(isset($_GET['Tekija']))?$_GET['Tekija']:array(), //selected
					1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2  col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>


		<div class="row">
		 <div class="col-sm-4">
			<h4>Määritellään työvuorosuunnittelussa:</h4>
			<b>SL</b>  - Sairaus Palkallinen<br>
			<b>SPL</b> - Sairaus Palkaton<br>
			<b>LS</b>  - Lapsen sairaus<br>
			<b>VL</b>  - Vuosiloma<br>
			<b>VKL</b> - Viikkolomapäivä
		 </div>
		</div>

                </div>
              </div>
            </div>
	    </form> 
        <!-- loppu: .tray-center -->
        </div>


<br>



<?php if(isset($_GET['Tekija']) and $from and $to) : ?>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<div class="table-responsive" id="tableContent">

  <table class="table table-bordered small" id="palkkatauluTaulu">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Tp'); ?></th>
  <th><?php echo Yii::t('main', 'M'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'matka+<br>tunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Iltamatka+<br>Iltatunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Lounas'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'PY'); ?></th>
  <th><?php echo Yii::t('main', 'EL'); ?></th>
  <th><?php echo Yii::t('main', 'SL'); ?></th>
  <th><?php echo Yii::t('main', 'SPL'); ?></th>
  <th><?php echo Yii::t('main', 'LS'); ?></th>
  <th><?php echo Yii::t('main', 'VL'); ?></th>
  <th><?php echo Yii::t('main', 'VKL'); ?></th>
  </tr>
  </thead>

  <?php
  $mobile = Yii::app()->createController('Mobile');
  $tids = array();
  $totalTp	= 0;
  $tot_sun	= 0;
  $tp		= 0;
  $sl 		= 0;
  $ls 		= 0;
  $spl 		= 0;
  $vl 		= 0;
  $vlYht	= 0;
  $vklYht	= 0;
  $slYht	= 0;
  $splYht	= 0;
  $lsYht	= 0;
  $pyhatYht	= 0;
  $elYht	= 0;
  $matkaYht	= 0;
  $yht[0] 	= 0;
  $yht[1] 	= 0;
  $yht[2] 	= 0;
  $yht[3] 	= 0;
  $mPlusTYht	= 0;
  $matkaIltaYht = 0;
  $iltaMatkaPlusIltatunnitYht = 0;
  $loun		= 0;
  $lounYht	= 0;


  $begin = new DateTime(date("Y-m-d", strtotime($from)));
  $end = new DateTime(date("Y-m-d", strtotime($to." +1 day")));
  $interval = DateInterval::createFromDateString('1 day');
  $period = new DatePeriod($begin, $interval, $end);


  $tids = [];
  foreach ($model as $data)
	$tids[$data->id] = $data->id;

  // < -- UUSI MUOTO / VIRTUAALISET
  $getAll = $this->TidfromtoTyovuoroWithVirtual($from, $to, $tids, false, false, null);
/*
  echo '<pre>';
  print_r($getAll);
  echo '<pre>';
  exit;
*/
  // <-- SPL, SL, LS, VL, VKL, AP
  $sl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'SL'); // Palkallinen
  $spl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'SPL'); // Palkaton
  $ls_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'LS'); // Lapsen sairaus
  $vl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'VL'); // Vuosiloma
  $vkl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'VKL'); // Viikkolomapaiva  ( Poistettu kaytosta )
  $ap_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from,$to,$tids,'AP'); // Arkipaiva
  //     SPL, SL, LS, VL, VKL, AP -->

  foreach($model as $data)
  {
		$tp 		= (isset($getAll[$data->id]['tp_maara']))? $getAll[$data->id]['tp_maara'] : 0;
		$tyotunnit	= (isset($getAll[$data->id]['tyotunnit']['kaikki']))? $getAll[$data->id]['tyotunnit']['kaikki'] : 0;
		$iltatunnit	= (isset($getAll[$data->id]['tyotunnit']['ilta']))? $getAll[$data->id]['tyotunnit']['ilta'] : 0;
		$yotunnit	= (isset($getAll[$data->id]['tyotunnit']['yo']))? $getAll[$data->id]['tyotunnit']['yo'] : 0;
		$matkatunnit	= (isset($getAll[$data->id]['matkatunnit']['kaikki']))? $getAll[$data->id]['matkatunnit']['kaikki'] : 0;
		$matkaIlta	= (isset($getAll[$data->id]['matkatunnit']['ilta']))? $getAll[$data->id]['matkatunnit']['ilta'] : 0;
		$loun		= (isset($getAll[$data->id]['lounaat']['kaikki']))? $getAll[$data->id]['lounaat']['kaikki'] : 0;
		$pyhapaivat	= (isset($getAll[$data->id]['pyhapaivat']))? $getAll[$data->id]['pyhapaivat'] : 0;
		$erikoislauantai= (isset($getAll[$data->id]['erikoislauantai']))? $getAll[$data->id]['erikoislauantai'] : 0;
		$sutunnit	= (isset($getAll[$data->id]['sutunnit']))? $getAll[$data->id]['sutunnit'] : 0;

		// <-- SPL, SL, LS, VL, VKL, AP
		$sl 		= (isset($sl_all[$data->id]))? $sl_all[$data->id] : 0; // Palkallinen
		$spl 		= (isset($spl_all[$data->id]))? $spl_all[$data->id] : 0; // Palkaton
		$ls 		= (isset($ls_all[$data->id]))? $ls_all[$data->id] : 0; // Lapsen sairaus
		$vl 		= (isset($vl_all[$data->id]))? $vl_all[$data->id] : 0; // Vuosiloma
		$vkl 		= (isset($vkl_all[$data->id]))? $vkl_all[$data->id] : 0; // Viikkolomapaiva  ( Poistettu kaytosta )
		//     SPL, SL, LS, VL, VKL, AP -->

		$slYht += $sl;
		$lsYht += $ls;
		$splYht += $spl;
		$vlYht += $vl;
		$vklYht += $vkl;
		$totalTp += $tp;
		$pyhat = $pyhapaivat;
		$pyhatYht += $pyhat;
		$el = $erikoislauantai;
		$elYht += $el;

		$matkaYht += $matkatunnit;
		$mPlusTYht += $tyotunnit+$matkatunnit;
		$lounYht += $loun;
		$matkaIltaYht += $matkaIlta;
		$iltaMatkaPlusIltatunnitYht += $iltatunnit;

		$yht[0] += $tyotunnit;
		$yht[1] += $iltatunnit-$matkaIlta;
		$yht[2] += $yotunnit;
		$yht[3] += $sutunnit;


		$this->renderPartial('_palkkataulukko',array(
			'tt_order_1' => $tt_order_1,
			'tt_order_2' => $tt_order_2,
			'data'=>$data,
			'tyotunnit'=>$tyotunnit,
			'matkatunnit'=>$matkatunnit,
			'matkaIlta'=>$matkaIlta,
			'tp'=>$tp,
			'sl'=>$sl,
			'spl'=>$spl,
			'ls'=>$ls,
			'vl'=>$vl,
			'vkl'=>$vkl,
			'from'=>$from,
			'to'=>$to,
			'pyhat'=>$pyhat,
			'el'=>$el,
			'loun'=>$loun,
			'yotunnit' => $yotunnit,
			'iltatunnit' => $iltatunnit,
			'sutunnit' => $sutunnit
		));
  }

  $matkaIltaYhtfooter = '';
  if($matkaIltaYht > 0){  $matkaIltaYhtfooter = '<br><b>Matkat</b>:<br>'.$this->num($matkaIltaYht); }
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<td><?php if($totalTp != 0) echo $totalTp; ?></td>
	<td><?php echo $this->num($matkaYht); ?></td>
	<td><?php echo $this->num($yht[0]); ?></td>
	<td><?php echo $this->num($mPlusTYht); ?></td>
	<td><?php if(!empty($matkaIltaYhtfooter) or $this->num($yht[1]) != 0) echo '<b>Työt</b>:<br>'.$this->num($yht[1]).$matkaIltaYhtfooter; ?></td>
	<td><?php echo $this->num($iltaMatkaPlusIltatunnitYht); ?></td>
	<td><?php echo $this->num($lounYht); ?></td>
	<td><?php echo $this->num($yht[2]); ?></td>
	<td><?php echo $this->num($yht[3]); ?></td>
	<td><?php echo $this->num($pyhatYht); ?></td>
	<td><?php echo $this->num($elYht); ?></td>
	<td><?=($slYht > 0)?$slYht:''?></td>
	<td><?=($splYht > 0)?$splYht:''?></td>
	<td><?=($lsYht > 0)?$lsYht:''?></td>
	<td><?=($vlYht > 0)?$vlYht:''?></td>
	<td><?=($vklYht > 0)?$vklYht:''?></td>
  </tr>
  </tfoot>
  </table>
</div>


                 </div>
                </div>
              </div>
            </div>


<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){

  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });

  $(".haemob").click(function(){
	$("#yhtveto").submit();
  });

  $('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
  });

  $('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
  });

  $("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

  });


$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});


// <-- Tulostus
$(document).delegate(".palkkatauluTaulu","click",function(){
	
	$('#palkkatauluTaulu td,#palkkatauluTaulu th').css({"border":"1px #333 solid", "padding":"3px 5px"});

	var divToPrint = document.getElementById('palkkatauluTaulu');
	newWin = window.open("");
	newWin.document.write(divToPrint.outerHTML);
	newWin.print();
	newWin.close();
});
//    Tulostus -->


});
</script>
