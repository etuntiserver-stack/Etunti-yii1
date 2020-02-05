<?php

	$a=Asiakkaat::model()->findbypk($id);
	if(isset($a->id) and !empty($a->yrityksen_nimi))
	$animi = $a->yrityksen_nimi;
	elseif(isset($a->id) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
	$animi = $a->yhteyshenkilo;
	else
	$animi = '';

	if(isset($a->id) and !empty($a->sahkoposti))
	$sahkoposti = $a->sahkoposti;
	else
	$sahkoposti = '';
?>

<?php if(!isset($_POST['tulosta'])) : ?>

        <!-- begin: .tray-center -->
        <div class="tray-center">
        <h2 class="myBgColors p15"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Työvuorot').' - '.$animi.', '.$sahkoposti; ?> 
	<!-- tulostus -->
	<div class="pull-right">
	 <div class="form-inline">
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="../mobile/tulostus" class="form-group" target="_blank" method="POST">
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



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="id" value="<?=$id?>">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>




                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

<?php else :  ?>
	<link rel="stylesheet" type="text/css" href="css/pdf_table.css">

<style>
.tb .col1{ width: 10%; }
.tb .col2{ width: 40%; }
.tb .col3{ width: 40%; }
.tb .col4{ width: 10%; }
</style>


        <h2 ><?php echo Yii::t('main', 'Työvuorot').' - '.$animi; ?> 
	</h2>
	<h4><?php echo date("d.m.Y",strtotime($_POST['from'])).' - '.date("d.m.Y",strtotime($_POST['to'])); ?></h4>
<?php endif; ?>

<br>

  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive tb" id="tableContent">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Muokkaa'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Aika'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Tietoja'); ?></th>
  </tr>
  </thead>
  <?php 
	foreach($dataAll as $arr){
		$tv = $this->renderPartial('_showshift',array(
			'data' => $arr['data'],
			'this_id' => $arr['this_id']
		), true);
		echo $tv;
	}
  ?>
  </table>
</div>

   </div>
  </div>

<script type="text/javascript">
$(document).ready(function(){
  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });
});
</script>
