<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas',
);
/*
$this->menu=array(
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
*/
?>





        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-ok"></i> <?php echo Yii::t('main', 'ASIAKKAIDEN HYVÄKSYMÄT TUNNIT'); ?> 
	      </h2>



   	    <form id="hyvaksytunnit" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="hyvaksytunnit">

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
		$criteria->order = " etunimi ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="asiakas" class="gui-input">';

	        echo '<option value="kaikki">'.Yii::t('main','Asiakas').'</option>';

		foreach($a as $aa)
		{
		    echo '<option value="'.$aa->id.'">'.$aa->Fullname.'</option>';
		}
		echo '</select>';
	?>

                            <i class="arrow double"></i>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

   <select name="status" class="gui-input">
   <option value="kaikki"><?php echo Yii::t('main','Tilanne'); ?></option>
   <option value="1"><?php echo Yii::t('main','Lähetetty'); ?></option>
   <option value="3"><?php echo Yii::t('main','Hyväksytty'); ?></option>
   <option value="2"><?php echo Yii::t('main','Hylätty'); ?></option>
   </select> 

                            <i class="arrow double"></i>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-1">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo Yii::app()->session['from']; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo Yii::app()->session['to']; ?>">

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






<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>



<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#hyvaksytunnit").submit();
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




});
</script>
