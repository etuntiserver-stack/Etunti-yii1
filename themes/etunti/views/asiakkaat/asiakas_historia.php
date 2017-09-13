<?php
	$from = date("Y-m-d", strtotime('first day of this month'));
	$to = date("Y-m-d", strtotime('last day of this month'));
	$naytaId = 0;
	if(isset($_POST['from'])) $from = $_POST['from'];
	if(isset($_POST['to'])) $to = $_POST['to'];
	if(isset($id) and !empty($id)) $naytaId = $id;


	$asiakkaat = Yii::app()->createController('Asiakkaat');
?>


<br>

        <div class="tray-center">
   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		<?php if(isset($_POST['asiakasID'])) : ?>
		<!-- Mobile -->
                 <div class="row">
                  <div class="col-sm-12">


      			<div class="form-group">
   			    <input type="date" class="form-control" name="from" value="<?php echo date('Y-m-d', strtotime($from)); ?>" >
      			</div>

      			<div class="form-group">
   			    <input type="date" class="form-control" name="to" value="<?php echo date('Y-m-d', strtotime($to)); ?>">
      			</div>

      			<div class="form-group">
        	            <button type="submit" class="btn btn-block btn-primary haemob myBgColors">Hae</button>
      			</div>


                  </div>
                 </div>
		<!-- Mobile -->
		<?php else: ?>
		<!-- WEB -->
                 <div class="row">
                  <div class="col-sm-12">


      			<div class="form-group">
   			    <input type="text" class="form-control datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>" >
      			</div>

      			<div class="form-group">
   			    <input type="text" class="form-control datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>">
      			</div>

      			<div class="form-group">
        	            <button type="submit" class="btn btn-block btn-primary haemob myBgColors">Hae</button>
      			</div>


                  </div>
                 </div>
		<!-- WEB -->
		<?php endif; ?>
		

                </div>
              </div>

	    </form>
            </div>
        </div>


	<?php if(isset($naytaToteutuneetTunnit)) : ?>
	<h3><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->toteutuneetTunnitCRM($model, $from, $to); ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>


	<?php if(isset($naytaAlennuskoodit)) : ?>
	<h3><?php echo Yii::t('main', 'Alennuskoodit'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->alennuskooditCRM($model, $from, $to); ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>


	<?php if(isset($naytaTyovuorot)) : ?>
	<h3><?php echo Yii::t('main', 'Työvuorot'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->tyovuorotCRM($model, $from, $to); ?>
		 </div>
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
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->laskutuksetCRM($model, $from, $to); ?>
		 </div>
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
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->tarjouksetCRM($model, $from, $to); ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaPalautteet)) : ?>
	<?php
		if(isset($_POST['PalautteetVastaus']['this_id']))
		{
			$return = $this->palautteetVastaus($_POST);
			echo json_encode($return);
		}
	?>
	<h3><?php echo Yii::t('main', 'Palautteet'); ?></h3>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		  <?php echo $asiakkaat[0]->palautteetCRM($model, $from, $to, $naytaId, $kayttaja); ?>

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
                 <div class="table-responsive">
		  <?php echo $asiakkaat[0]->vinkitCRM($model, $from, $to); ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>
