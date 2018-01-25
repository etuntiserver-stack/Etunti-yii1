<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/js/onlinevaraus_osoite.js"></script>

<div class="container">

 <div class="row">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">

		<div class="form-inline">
		 <div class="form-group">
		  <?=CHtml::link('<h2><i class="fa fa-arrow-left" aria-hidden="true"></i></h2>','aika')?>
		 </div>
		 <div class="form-group pull-right">
		  <h2 class="link" data-toggle="modal" data-target=".mikaOnOnlinevaraus"><i class="fa fa-info-circle" aria-hidden="true"></i></h2>
		 </div>
		</div>

		

		<div id="fullLomake">
		   <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Osoite'); ?></h4>
		   <span class="text-sininen"><?php echo Yii::t('main', 'Tunnistaudu sähköpostilla'); ?></span>
	
		      <!--<span class="small"><?php echo Yii::t('main', 'sähköpostillaTeksti'); ?></span>-->
		      <br>

		   <div class="row">
		      <div class="col-sm-12">
	
		     	<div class="sahkoposti">
			<label><?php echo Yii::t('main', 'Sähköposti'); ?></label>
			<input type="text" id="sahkoposti" class="form-control input-lg" placeholder="Sähköposti" value="<?php if(isset($_SESSION['onlinevaraus']['sahkoposti'])) echo $_SESSION['onlinevaraus']['sahkoposti'] ;?>">
		     	</div>

		      </div><div class="col-sm-12">
		       	<div id="loytynytOsoitteet"></div>
		      </div>
		   </div>

		     <br>

		     <span class="text-sininen"><?php echo Yii::t('main', 'Tai täytä yhteystietokentät'); ?></span>
		     <!--<span class="small"><?php echo Yii::t('main', 'taitaytateksti'); ?></span>-->
		     <br>

		     <div id="lomake">
	
		      <div class="row">
		       <div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Asiakastyyppi '); ?></label>
			  <select id="tyyppi" class="form-control input-lg">
			  <option value="henkilo">Yksityishenkilö</option>
			  <option value="yritys">Yritys</option>
			  </select>

		       </div>
		      </div>

		      <div class="row">
		       <div class="col-sm-6">
		
			<label><?php echo Yii::t('main', 'Yhteyshenkilö'); ?></label>
			  <input type="text" id="yhteyshenkilo" class="form-control input-lg">
	
			<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
			  <input type="text" id="puhelin" class="form-control input-lg">
	
		       </div><div class="col-sm-6">

		        <div class="yritys">
			<label><?php echo Yii::t('main', 'Yrityksen Nimi'); ?></label>
			  <input type="text" id="yrityksen_nimi" class="form-control input-lg">
			</div>

		        <div class="yritys">
			<label><?php echo Yii::t('main', 'Y-tunnus'); ?></label>
			  <input type="text" id="y_tunnus" class="form-control input-lg">
			</div>
	
		       </div>
		      </div>

		      <br>
		      <center><h4><?php echo Yii::t('main', 'Osoite'); ?></h4></center>

		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Osoite'); ?></label>
			  <input type="text" id="osoite" class="form-control input-lg">
		       </div>
		      </div>


		      <div class="row">
		       <div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Postinumero'); ?></label>
			  <input type="text" id="postinumero" class="form-control input-lg">
		       </div><div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Postitoimipaikka'); ?></label>
			  <input type="text" id="kaupunki" class="form-control input-lg">
		       </div>
		      </div>

		      <br>
		      <center><h4><?php echo Yii::t('main', 'Lisätietoja'); ?></h4></center>

		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Lisätietoja'); ?></label>
			  <textarea id="lisatietoja" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Lemmikkejä, ovikoodi ja muuta lisätietoa'); ?>" rows="5"></textarea>
		       </div>
		      </div>

		      <br>
		      <div id="getMyPictures"></div>
		      <br>
		
		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Kuvien lisääminen'); ?></label>
			<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-filestyle.js"> </script>
		  	<form id="uploadKuva" action="#" method="post" enctype="multipart/form-data">
		     	  <div class="input-group">
				<input type="hidden" name="kuvanLisaaminen">
				<input type="file" name="file" class="filestyle" data-icon="false" data-size="lg" data-buttonName="btn-primary" data-buttonText="<?php echo Yii::t('main', 'Lisää kuva'); ?>">
				<span class="input-group-btn">
		          		<input type="submit" value="Lataa" class="btn btn-primary btn-lg btn-group myBgColors" />
				</span>
		    	  </div>
			</form>
		       </div>
		      </div>
		     </div>

		</div><!-- Full lomake -->



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
		$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>'osoite'), true); 
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



<?php
if(isset(Yii::app()->user->aid)){
	$a = Asiakkaat::model()->findByPk(Yii::app()->user->aid);
	if(isset($a->id) and !empty($a->sahkoposti))
	echo '<input type="hidden" id="aid_sahkoposti" value="'.$a->sahkoposti.'">';
}
?>




                            <!-- Modal -->
                            <div class="modal fade kysymys mikaOnOnlinevaraus">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Osoite</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Kirjoita osoitekenttiin pyydetyt yhteystiedot. Jos olet käyttänyt palvelua aikaisemmin, niin yhteystietosi löytyvät sähköpostisi perusteella.<br>
2.       Jos sinulla on lemmikkejä, huoneita, jonne et halua kenenkään menevän, ovikoodi tai muuta työntekijän saapumiseen tai palvelun suorittamiseen liittyviä asioita, niin kirjoita ne lisätietoja osioon.<br>
3.       Siirry eteenpäin hyväksymään ja maksamaan palvelun.<br>

<br><br> 

<p>Kaikki kentät ovat pakollisia. Antamasi tiedot tallentuvat järjestelmään, jolloin tietoja ei tarvitse kirjoittaa uudestaan, kun palveluja tilataan tulevaisuudessa. Sinulla voi olla useampia osoitteita tallentuneena. Yhteenveto kenttä päivittyy, kun tietoja kirjataan. Mikäli haluat poistaa tietyn osoitteen järjestelmästä, niin ota yhteyttä asiakaspalveluumme. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

