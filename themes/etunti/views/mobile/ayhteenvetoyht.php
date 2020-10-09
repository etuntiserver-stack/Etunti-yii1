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




<?php if(isset($_GET['from']) and isset($_GET['to'])) : ?>

<div class="admin-form">
  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
         <div class="form-inline">
    	  <!--<button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>-->
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
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
       </div>
      </div>
      <br>
  </div>
  <div class="panel heading-border">
   <div class="panel-body">

	<div class="row">
	 <div class="table-responsive raporti_taulu" id="tableContent">
	  <?=$from?>-<?=$to?>
	  <table class="table table-bordered table-striped small" cellspacing="0" cellpadding="0" id="mobileTable">
	  <thead class="myBgColors">
	  <tr>
	  <?php
	  $suunnitelut 	= $this->AsiakasPvmLuTotSuunArrayYht($asiakas_id, $from, $to, 'suunnitelut');
	  $luetut 	= $this->AsiakasPvmLuTotSuunArrayYht($asiakas_id, $from, $to, 'luetut');
	  $hyvaksynta 	= $this->AsiakasPvmLuTotSuunArrayYht($asiakas_id, $from, $to, 'hyvaksynta');
	  $hyvaksytyt 	= $this->AsiakasPvmLuTotSuunArrayYht($asiakas_id, $from, $to, 'hyvaksytyt');
	  ?>
	  <th class="tdw1"><?php echo Yii::t('main', 'Asiakas'); ?></th>
	  <th class="tdw2"><?php echo Yii::t('main', 'Suunniteltut tunnit'); ?> (<?=count($suunnitelut)?>kpl)</th>
	  <th class="tdw3"><?php echo Yii::t('main', 'Luetut tunnit'); ?> (<?=count($luetut)?>kpl)</th>
	  <th class="tdw4"><?php echo Yii::t('main', 'Hyväksyntä'); ?> (<?=count($hyvaksynta)?>kpl)</th>
	  <th class="tdw4"><?php echo Yii::t('main', 'Hyväksytyt tunnit <br>laskutettavaksi'); ?> (<?=count($hyvaksytyt)?>kpl)</th>
	  </tr>
	  </thead>
	  <?php
	  foreach($asiakkaat as $asiakasnimi => $asiakas){

		$s = (isset($suunnitelut[$asiakas->id]))? array_sum($suunnitelut[$asiakas->id]) : 0 ;
		$l = (isset($luetut[$asiakas->id]))? array_sum($luetut[$asiakas->id]) : 0 ;
		$t = (isset($hyvaksynta[$asiakas->id]))? array_sum($hyvaksynta[$asiakas->id]) : 0 ;
		$h = (isset($hyvaksytyt[$asiakas->id]))? array_sum($hyvaksytyt[$asiakas->id]) : 0 ;

		$s_kpl = (isset($suunnitelut[$asiakas->id]))? count($suunnitelut[$asiakas->id]) : 0 ;
		$l_kpl = (isset($luetut[$asiakas->id]))? count($luetut[$asiakas->id]) : 0 ;
		$t_kpl = (isset($hyvaksynta[$asiakas->id]))? count($hyvaksynta[$asiakas->id]) : 0 ;
		$h_kpl = (isset($hyvaksytyt[$asiakas->id]))? count($hyvaksytyt[$asiakas->id]) : 0 ;

		echo '<tr>';
		echo '<td><h4>'.$asiakasnimi.'</h4></td>';
		echo '<td class="text-center"><table class="table table-bordered"><td width="33%">'.$this->sprint($s).'</td><th width="33%">'.number_format($this->num($s), 2, ',', '').'</th><th width="33%">'.$s_kpl.'kpl</th></table></td>';
		echo '<td class="text-center"><table class="table table-bordered"><td width="33%">'.$this->sprint($l).'</td><th width="33%">'.number_format($this->num($l), 2, ',', '').'</th><th width="33%">'.$l_kpl.'kpl</th></table></td>';
		echo '<td class="text-center"><table class="table table-bordered"><td width="33%">'.$this->sprint($t).'</td><th width="33%">'.number_format($this->num($t), 2, ',', '').'</th><th width="33%">'.$t_kpl.'kpl</th></table></td>';
		echo '<td class="text-center"><table class="table table-bordered"><td width="33%">'.$this->sprint($h).'</td><th width="33%">'.number_format($this->num($h), 2, ',', '').'</th><th width="33%">'.$h_kpl.'kpl</th></table></td>';
		echo '</tr>';
	  }
	  ?>
	  </table>
	 </div>
	</div>

   </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });
});
</script>
<?php endif; ?>

<?php /*
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

*/ ?>
