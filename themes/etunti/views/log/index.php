<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';

	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'kohdetta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'kohdetta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'kohdetta sivulla').'">100</button>';

	   ?>
	 </div>
        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Historia'); ?> 



	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-4">

		      <?php if(isset($_POST['aktiivinen'])) echo '<input type="hidden" id="akt" value="'.$_POST['aktiivinen'].'">'; ?>
                        <div class="section">
                          <label class="field select">


			   <select class="gui-input" name="log_category" id="log_category">
       				<option value="1" <?php echo (isset($_POST['log_category']) and $_POST['log_category'] == 1)? 'selected': '';?>><?php echo Yii::t('main', 'Sähköpostit'); ?></option>
       				<option value="2" <?php echo (isset($_POST['log_category']) and $_POST['log_category'] == 2)? 'selected': '';?>><?php echo Yii::t('main', 'Tapahtumat'); ?></option>
       				<option value="kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			   </select>

                            <label for="firstname" class="field-icon">
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepicker" name="from" value="<?php if(isset($_POST['from'])) echo $_POST['from']; ?>" placeholder="<?php echo Yii::t('main', 'Mistä'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepicker" name="to" value="<?php if(isset($_POST['to'])) echo $_POST['to']; ?>" placeholder="<?php echo Yii::t('main', 'Mihin'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                    </div>

                      </div>


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="email_to" value="<?php if(isset($_POST['email_to'])) echo $_POST['email_to']; ?>" placeholder="<?php echo Yii::t('main', 'Sähköpostin saaja'); ?>...">

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

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <!--<th></th>-->
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Tehtävän nimike'); ?></th>
  <?php if(isset($_POST['log_category']) and $_POST['log_category'] == 1): ?>
  <th><?php echo Yii::t('main', 'Saaja'); ?></th>
  <th><?php echo Yii::t('main', 'Otsikko'); ?></th>
  <th><?php echo Yii::t('main', 'Viesti'); ?></th>
  <th><?php echo Yii::t('main', 'Liitteen sisältö'); ?></th>
  <?php elseif(isset($_POST['log_category']) and $_POST['log_category'] == 2): ?>
  <th><?php echo Yii::t('main', 'Tilanne'); ?></th>
  <th><?php echo Yii::t('main', 'Vanhat arvot'); ?></th>
  <th><?php echo Yii::t('main', 'Uudet arvot'); ?></th>
  <?php endif; ?>
  </tr>
  </thead>
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
  </table>
 </div>
</div>

   </div>
  </div>
</div>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){


$(".nayta").click(function(){
	var id = $(this).attr('for');
	var get = $(this).attr('get');

        $.ajax({
           url: 'index',
           type: "POST",
           data: { naytaModal : "true", id : id, get : get },
           success: function(data){
		$('#showres').modal().html(''+
		  '<div class="modal-dialog">'+
		
		    '<!-- Modal content-->'+
		    '<div class="modal-content">'+
		      '<div class="modal-header">'+
		        '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
		        '<h4 class="modal-title">'+get+'</h4>'+
		      '</div>'+
		      '<div class="modal-body">'+
		        '<p>'+data+'</p>'+
		      '</div>'+
		      '<div class="modal-footer">'+
		        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
		      '</div>'+
		    '</div>'+
		
		  '</div>'
		);
           }
        });

});


if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);

$(".haemob").click(function(){
	$("#mobForm").submit();
});


 $(".kpl").click(function(){
	var kohteetPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "kohteetPerSivu" : kohteetPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
 });

});
</script>
