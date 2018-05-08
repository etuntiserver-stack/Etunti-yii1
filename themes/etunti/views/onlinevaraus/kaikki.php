<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-default btn-sm" value="<?php echo Yii::t('main', 'ALV raportti'); ?>">
     </form>
   </div>
   <!-- tulostus -->


              <h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Onlinevaraukset'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

			    <select name="tila" id="tila" class="gui-input">
			     <option value="" <?php if ( isset($_GET['tila']) and $_GET['tila'] == '' ) echo 'selected'; ?>><?php echo Yii::t('main', 'Kaikki'); ?></option>
			     <option value="1" <?php if ( isset($_GET['tila']) and $_GET['tila'] == 1 ) echo 'selected'; ?>><?php echo Yii::t('main', 'Maksettu'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">

			    <select name="tyoryhma_tyyppi" id="tyoryhma_tyyppi" class="gui-input">
			     <option value="">Työryhmä tyyppi</option>
			     <option value="kohde" <?php if ( isset($_GET['tyoryhma_tyyppi']) and $_GET['tyoryhma_tyyppi'] == 'kohde' ) echo 'selected'; ?>><?php echo Yii::t('main', 'Työryhmä kohteiden mukaan'); ?></option>
			     <option value="tyontekija" <?php if ( isset($_GET['tyoryhma_tyyppi']) and $_GET['tyoryhma_tyyppi'] == 'tyontekija' ) echo 'selected'; ?>><?php echo Yii::t('main', 'Työryhmä työntekijöiden mukaan'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">

		<?php
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
		$criteria->addCondition ("value2 LIKE '%\"".Yii::app()->user->adminID."\"%'");
		}

		$listData = Valikkoot::model()->findAll($criteria);
		$selectedValues = array(Yii::app()->getRequest()->getParam('tyoryhma') => Array('selected' => 'selected'));
		?>
		<?php echo CHtml::dropDownList('tyoryhma', 'tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control', 'options' => $selectedValues)); 
		?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>">

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


<div class="admin-form">

  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
         <div class="form-inline">
    	  <!--<button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>-->
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Onlinevaraus">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
		<textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Onlinevaraus">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?php if(isset($_GET['raporti_tyyppi'])) echo $_GET['raporti_tyyppi']; ?>, <?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Onlinevaraus">
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
  <table class="table table-striped" id="mobileTable">
  <thead>
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Maksupäivä'); ?></th>
  <th><?php echo Yii::t('main', 'Työvuoro'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Hinta'); ?></th>
  <th><?php echo Yii::t('main', 'Tila'); ?></th>
  <th><?php echo Yii::t('main', 'Kuvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteyshenkilo'); ?></th>
  <th><?php echo Yii::t('main', 'Puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Tietoja'); ?></th>
  <th><?php echo Yii::t('main', 'Valokuvat'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </tbody>
  </table>
 </div>
</div>


   </div>
  </div>

</div>

<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobileTable').DataTable({
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false
    });

    $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val(JSON.stringify($('#tableContent').html()));
	$(this).closest('form').submit();
	e.preventDefault();
    });

});
</script>



<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
