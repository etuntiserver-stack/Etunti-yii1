<?php
	$from = date("Y-m-d", strtotime('first day of this month'));
	$to = date("Y-m-d");
	if(isset($_POST['from'])) $from = $_POST['from'];
	if(isset($_POST['to'])) $to = $_POST['to'];

	$asiakkaat = Yii::app()->createController('Asiakkaat');
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

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" placeholder="<?php echo Yii::t('main', 'Aloitus'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                   </div>

                   <div class="col-sm-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>" placeholder="<?php echo Yii::t('main', 'Lopetus'); ?>..">

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



	<?php if(isset($naytaTyovuorot)) : ?>
	<h3><?php echo Yii::t('main', 'Työvuorot'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->tyovuorotCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaLaskut)) : ?>
	<h3><?php echo Yii::t('main', 'Laskut'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->laskutuksetCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaTarjoukset)) : ?>
	<h3><?php echo Yii::t('main', 'Tarjoukset'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->tarjouksetCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaPalautteet)) : ?>
	<h3><?php echo Yii::t('main', 'Palautteet'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->palautteetCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaVinkit)) : ?>
	<h3><?php echo Yii::t('main', 'Vinkit'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->vinkitCRM($model, $from, $to); ?>

                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>
