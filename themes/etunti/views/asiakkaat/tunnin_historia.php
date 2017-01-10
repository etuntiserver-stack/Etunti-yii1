<?php
	//print_r($_POST);
?>

	<h2 class="myBgColors p10" id="historia"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakas historia'); ?> </h2>

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

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php if(isset($_POST['from']) and !empty($_POST['from'])) echo date('d.m.Y', strtotime($_POST['from'])); ?>" placeholder="<?php echo Yii::t('main', 'Aloitus'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                   </div>

                   <div class="col-sm-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php if(isset($_POST['to']) and !empty($_POST['to'])) echo date('d.m.Y', strtotime($_POST['to'])); ?>" placeholder="<?php echo Yii::t('main', 'Lopetus'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                   </div>


                      <div class="col-md-2 col-md-offset-6">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                 </div>
		

                </div>
              </div>

	    </form>
            </div>
        </div>
