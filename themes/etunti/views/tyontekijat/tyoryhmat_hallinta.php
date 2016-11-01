<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"><i class="fa fa-users" aria-hidden="true"></i> <?php echo Yii::t('main', 'Työryhmät hallinta'); ?>
	</h2>




   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="uusi_tyoryhma" placeholder="<?php echo Yii::t('main', 'Uusi työryhmä'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo'); ?>">
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
  <th></th>
  <th><?php echo Yii::t('main', 'Nimike'); ?></th>
  <th><?php echo Yii::t('main', 'Jaärjestelmanvalvoja'); ?></th>
  </tr>
  </thead>
  <?php
	$admins = Administrators::model()->findAll(array('order' => 'adm_nimi'));
	foreach($model as $data){
		echo $this->renderPartial('_tyoryhmat_hallinta', array('data'=>$data, 'admins'=>$admins));
	}
  ?>
  </table>
 </div>
</div>


   </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

 $(".m3").multiselect({

	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Järjestelmänvalvojat"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
 });

 $(".m3").change(function(){
	var thisFor = $(this).attr('for');
	updateValiko(thisFor);
 });
 $(".m2").keyup(function(){
	var thisFor = $(this).attr('for');
	updateValiko(thisFor);
 });

 function updateValiko(thisFor){
	$('#'+thisFor).removeClass().addClass('btn btn-warning tallenna');
 }


 $(document).delegate(".tallenna","click",function(){
	var thisID = $(this).attr('id');
	var a2 = $('#a2_'+thisID).val();
	var a3 = $('#a3_'+thisID+' :selected').map(function(){return $(this).val();}).get();

		console.log(a3)

        $.ajax({
           url: 'tyoryhmat_hallinta',
           type: "POST",
           data: { update : "true", id : thisID, value : a2, value2 : a3 },
           success: function(html){

		console.log(html);
		$('#'+thisID).removeClass().addClass('tallenna btn btn-success');

           }

        });
 });

});
</script>

