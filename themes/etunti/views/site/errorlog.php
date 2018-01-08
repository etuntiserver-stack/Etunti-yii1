<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


            <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'VIRHELOKI'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="number" class="gui-input" name="lines" value="<?php if(isset($_GET['lines'])) echo $_GET['lines']; ?>" placeholder="<?php echo Yii::t('main', 'Montako riveja'); ?>...">

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


<?php if(isset($_GET['lines'])) : ?>
<?php $output = shell_exec('tail -n '.$_GET['lines'].' protected/runtime/application.log'); ?>
<pre><?=$output?></pre>
<?php endif;c ?>

