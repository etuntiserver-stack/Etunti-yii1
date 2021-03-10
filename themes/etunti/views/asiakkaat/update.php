<?php
$head = $model->Fullname;

$asetukset = Asetukset::model()->findbypk(1);
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <?php if($asetukset->netvisor_kaytto == 1) : ?>
	   <button class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#netvisorTiedot"><?php echo Yii::t('main', 'Netvisor tiedot'); ?></button>
	   <?php endif; ?>
	   <button class="btn btn-primary myBgColors" id="historiaSiirto"><?php echo Yii::t('main', 'Historia'); ?></button>
	   <?php     
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
	   ?>
	   </div>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakkaiden hallinta'); ?>: <?php echo $head; ?> </h2>

	   <?php echo $this->VinkiTahdet($model->id); ?>

	   <!-- Vinkki -->
	   <?php 
		$vinkki = false;
		if($model->vinkki_id != 0)
		{
			$v = VinkkiExtranet::model()->findbypk($model->vinkki_id);
			if(isset($v->id))
			{
				$vinkki = true;
				$as = Asiakkaat::model()->findbypk($v->asiakas_id);
				$nimi = '';
				$as_id = '';

				if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
					$nimi = $as->yrityksen_nimi;
				elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
					$nimi = $as->yhteyshenkilo;

				$as_id = $as->id;
			}
		}
	   ?>
	   <?php if($vinkki): ?>
	   <div class="alert alert-default">
			<?=Yii::t('main', 'Suosittelija: ')?>
			<?php echo CHtml::link($nimi, array('//asiakkaat/update', 'id'=>$as_id), array('class'=>'link')); ?>
	   </div>
	   <?php endif; ?>
	   <!-- Vinkki -->


	   <div id="netvisorTiedot" class="collapse">
	   <div class="alert alert-default"><?php $this->netvisorAsiakasNouto($model->netvisorkey); ?></div>
	   </div>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>





<div class="row">




  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Tiedostot (sopimukset jne)'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUP" placeholder="<?php echo Yii::t('main', 'Valitse tiedosto'); ?>..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="<?php echo Yii::t('main', 'Lataa'); ?>" class="btn btn-primary myBgColors" />
	</span>
    </div>
  </form>

  <br>
  <?php
	Asetukset::model()->getFiles(
		Yii::app()->user->domain, 
		'asiakkaat', 
		$model->id
	);
  ?>

</div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>



	<?php 
	echo $this->renderPartial('asiakas_historia', 
		array(
			'model'=>$model,
			'naytaAlennuskoodit'=>true,
			'naytaTyovuorot'=>true,
			'naytaLaskut'=>true,
			'naytaTarjoukset'=>true,
			'naytaPalautteet'=>true,
			'naytaVinkit'=>true,
			'kayttaja' => 'admin',
		)
	); 
	?>

	

<br><br>





<script type="text/javascript">
$(document).ready(function(){

$("#historiaSiirto").click(function(){
	var divPosition = $('#historia').offset();
	$('html, body').animate({scrollTop: divPosition.top}, "slow");
});

$(".getLaskuPDF").click(function(){
	var thisID = $(this).attr('id');
	window.location.href="getLaskuPDF?id=" + thisID;
});



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
