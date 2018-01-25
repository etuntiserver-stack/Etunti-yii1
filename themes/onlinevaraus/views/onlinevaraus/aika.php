<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/js/onlinevaraus_aika.js"></script>

<div class="container">

 <div class="row">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">

		<div class="form-inline">
		 <div class="form-group">
		  <?=CHtml::link('<h2><i class="fa fa-arrow-left" aria-hidden="true"></i></h2>','index')?>
		 </div>
		 <div class="form-group pull-right">
		  <h2 class="link" data-toggle="modal" data-target=".mikaOnOnlinevaraus"><i class="fa fa-info-circle" aria-hidden="true"></i></h2>
		 </div>
		</div>

		


		<div id="kalenterit"></div>

		<br>
		<div id="aikoja"></div>
		<div id="tidTietoja"></div>

	  </div>
	</div>
  </div>
 </div>

 <!-- Order summary footer-->
 <div class="row panGetContent">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
	    <div id="panGetContent">
    	    <?php 
	    if(isset($_SESSION['onlinevaraus']['paapalvelu']))
	    {
		$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>'aika'), true); 
	   	echo json_decode($return, true);
	    }
	    ?>
	    </div>
	  </div>
	</div>
  </div>
 </div>

 <?php if(!empty($asetukset->onlinevaraus_asiakaspalvelu)) : ?>
 <div class="row" id="asiakaspalvelu">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
	      <div>
		  <h3><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h3>
		  <p><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_asiakaspalvelu); ?></p>
	      </div>
	  </div>
	</div>
  </div>
 </div>
 <?php endif; ?>

</div><!-- container-->



<input type="hidden" id="valinnuPvm">




                            <!-- Modal -->
                            <div class="modal fade kysymys mikaOnOnlinevaraus">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Ajan varaaminen</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Valitse kalenterista päivämäärä, jolloin haluat palvelun.<br>
2.       Valitse kellonaika, jolloin haluat palvelun alkavan.<br>
3.       Siirry eteenpäin antamaan osoitetiedot.<br>

<br><br> 

<p>Päivät, joissa on vapaita aikoja valittavana näkyvät vihreällä, harmaalla näkyvät päivät, joita ei voi valita ja oranssilla näkyvä on valitsemasi päivä. Kellonajat, joita päivämäärän valinnan jälkeen näkyy ovat kaikki mahdolliset vapaat ajat kyseiselle päivälle. Yhteenvetoon päivittyy, kun tietoja kirjataan. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla. Ole yhteydessä asiakaspalveluun, mikäli sinulla on jotain kysyttävää.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
