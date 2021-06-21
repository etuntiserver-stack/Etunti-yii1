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



	<?php if(isset($_POST['asiakasID'])) : ?>
	 <?php if(isset($naytaTyovuorot)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Työvuorot'); ?></h3></legend>
	 <?php elseif(isset($naytaLaskut)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Laskut'); ?></h3></legend>
	 <?php elseif(isset($naytaAlennuskoodit)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Alennuskoodit'); ?></h3></legend>
	 <?php elseif(isset($naytaTarjoukset)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Tarjoukset'); ?></h3></legend>
	 <?php elseif(isset($naytaPalautteet)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Palautteet'); ?></h3></legend>
	 <?php elseif(isset($naytaVinkit)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Vinkit'); ?></h3></legend>
	 <?php elseif(isset($naytaToteutuneetTunnit)): ?>
	  <legend><h3><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></h3></legend>
	 <?php endif; ?>
	<?php endif; ?>


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

	<?php if(isset($naytaToteutuneetTunnit) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->toteutuneetTunnitCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Toteutuneet tunnit').$kayttaja; ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>


	<?php if(isset($naytaAlennuskoodit) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->alennuskooditCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Alennuskoodit'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>


	<?php if(isset($naytaTyovuorot) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->tyovuorotCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Työvuorot'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaLaskut) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->laskutuksetCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Laskut'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaTarjoukset) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->tarjouksetCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Tarjoukset'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaPalautteet) and (isset($_POST['from']) or $naytaId != 0)) : ?>
	<?php $returnBod = $asiakkaat[0]->palautteetCRM($model, $from, $to, $naytaId, $kayttaja); ?>
	<?php
		if(isset($_POST['PalautteetVastaus']['this_id']))
		{
			$return = $this->palautteetVastaus($_POST);
			echo json_encode($return);
		}
	?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Palauteet'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if(isset($naytaVinkit) and isset($_POST['from'])) : ?>
	<?php $returnBod = $asiakkaat[0]->vinkitCRM($model, $from, $to); ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Vinkit'); ?></h3></legend>
	<?php endif; ?>
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="table-responsive tuloshakusta">
		 <?php if(empty($returnBod)): ?>
		    <p><?=Yii::t('main', 'Ei tuloksia')?></p>
	  	 <?php else: ?>
		    <?=$returnBod?>
		 <?php endif; ?>
		 </div>
                </div>
              </div>
            </div>
        </div>
	<?php endif; ?>

	<?php $returnBod = is_object($asiakkaat[0]) ? $asiakkaat[0]->getCustomerFreshdeskTickets($model) : ''; ?>
  <?php if(!empty($returnBod)): ?>
	<?php if($kayttaja == 'admin'): ?>
	  <legend><h3><?php echo Yii::t('main', 'Tickets'); ?></h3></legend>
	<?php endif; ?>
  <div class="tray-center">
      <div class="admin-form">
        <div class="panel heading-border">
          <div class="panel-body bg-light">
           <div class="table-responsive tuloshakusta">
            <?php if(empty($returnBod)): ?>
               <p><?=Yii::t('main', 'Ei tuloksia')?></p>
            <?php else: ?>
               <?=$returnBod?>
            <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
  </div>
  <?php endif; ?>