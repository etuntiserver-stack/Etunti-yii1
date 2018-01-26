<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/js/onlinevaraus_maksu.js"></script>

<div class="container">

 <div class="row">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">

		<div class="pull-right">
		  <h2 class="link" data-toggle="modal" data-target=".mikaOnOnlinevaraus"><i class="fa fa-info-circle" aria-hidden="true"></i></h2>
		</div>
		<div class="form-group">
		  <?=CHtml::link('<h2><i class="fa fa-arrow-left" aria-hidden="true"></i></h2>','osoite')?>
		</div>
	  </div>
	</div>

	<div class="row">
	  <div class="col-sm-12">
		<?php 
		if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['onlinevarausID']))
		{

			$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
			if(isset($ov->id))
			{
			$return = $this->renderPartial('checkout', array(
				'amount'=>$_SESSION['onlinevaraus']['amount'],
				'kesto'=>$_SESSION['onlinevaraus']['sumTunti'],
				'etu_suku_nimet'=>$ov->yhteyshenkilo,
				'osoite'=>$ov->osoite,
				'postinumero'=>$ov->postinumero,
				'kaupunki'=>$ov->kaupunki,
			), true); 
		   	echo $return;
			}
		}
		?>

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
		$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>'maksu'), true); 
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

</div><!--container-->







                            <!-- Modal -->
                            <div class="modal fade kysymys mikaOnOnlinevaraus">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Maksu</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Tarkasta palveluntilauksen tiedot.<br>
2.       Muokkaa tilausta tai tietoja tarvittaessa.<br>
3.       Lue varaus- ja peruutusehdot ja hyväksy ne.<br>
4.       Valitse maksutapa ja suorita maksu.<br>
5.       Maksun suorittamisen jälkeen saat tilausvahvistuksen sähköpostiisi.<br>

<br><br> 

<p>Maksamisen jälkeen saat tilausvahvistuksen ja voit halutessasi tulostaa sen. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla. Ole yhteydessä asiakaspalveluun, mikäli sinulla on jotain kysyttävää.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

