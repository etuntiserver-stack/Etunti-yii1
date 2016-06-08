<?php
$head = '';

if(!empty($model->yhteyshenkilo))
$head = $model->yhteyshenkilo;
else
$head = $model->osoite;


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <?php     
		echo CHtml::link(Yii::t('main', 'Kirjaudu ulos'), Yii::app()->request->baseUrl.'/index.php/asiakkaat/ulos', array(
		'class'=>'btn btn-primary'
		));
	   ?>
	   </div>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo $head; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>





<div class="row">
  <div class="col-sm-6">
    <?php


	$i = 0;
	foreach(array_reverse(glob('tiedostot/asiakkaat/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  &nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
	$kuvat[$i] = $file;
	}
   ?>
   </div>
</div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>



	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakas historia'); ?> </h2>

        <div class="tray-center">

   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
                   <div class="col-sm-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php if(isset($_POST['from'])) echo date('d.m.Y', strtotime($_POST['from'])); ?>" placeholder="<?php echo Yii::t('main', 'Aloitus'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                   </div>

                   <div class="col-sm-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php if(isset($_POST['to'])) echo date('d.m.Y', strtotime($_POST['to'])); ?>" placeholder="<?php echo Yii::t('main', 'Lopetus'); ?>..">

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

	    </form>

            </div>
        </div>



<?php
	$from = '';
	$to = '';
	if(isset($_POST['from'])) $from = date("Y-m-d", strtotime($_POST['from']));
	if(isset($_POST['to'])) $to = date("Y-m-d", strtotime($_POST['to']));
?>

	<h3><?php echo Yii::t('main', 'Työvuorot'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $this->tyovuorotCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>

	<h3><?php echo Yii::t('main', 'Laskut'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $this->laskutuksetCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>

	<h3><?php echo Yii::t('main', 'Tarjoukset'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $this->tarjouksetCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>


<br><br>












<script type="text/javascript">
$(document).ready(function(){


$(".poistaTiedosto").click(function(){
	var forThis = $(this).attr("this");
	var model = $(this).attr("model");
	var forID = $(this).attr("for");

        $.ajax({
           url: "update?id="+model,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
});


});
</script>
