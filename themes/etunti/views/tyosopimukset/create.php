<?php

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Luo työsopimus'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

<?php if (file_exists(Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/'.$this->tiedostonNimike().'.docx')) : ?>

                 <div class="row">
                  <div class="col-sm-4">
   <?php 
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   $criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="tyontekija" id="tyontekijat" title="Työntekijät" class="form-control">';
       	 echo '<option>'.Yii::t('main', 'Valitse työntekijä').'</option>';
    foreach($list as $key=>$val){
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

                  </div>
                 </div>

		<hr>

		<?php
			$firma = FirmanTiedot::model()->findbypk(1);
			$model->tyonantaja = $firma->tyonantaja;
			$model->osoite = $firma->osoite;
			$model->postinumero = $firma->postinumero;
			$model->postitoimipaikka = $firma->postitoimipaikka;
			$model->y_tunnus = $firma->y_tunnus;
			$model->puhelin = $firma->puhelin;
			$model->sahkoposti = $firma->sahkoposti;
			$model->TyonantajanEdustaja = $firma->johtaja;
			$model->Paivays = date('d.m.Y');



			echo $this->renderPartial('_form', array('model'=>$model)); 
		?>


<?php else : ?>
	<?php echo Yii::t('main', 'Template tiedosto puutuu'); ?>
<?php endif; ?>


                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>




<script type="text/javascript">
$(document).ready(function(){


 $('#tyontekijat').change(function(){

   var tid = $(this).val();
   $.ajax({
   url: 'tekijan_tiedot',
      type: "POST",
      data: { tid : tid },
      	success: function(data){
		data = JSON.parse(data);
  	  	console.log(data);

			$('#Tyosopimukset_tid').val(tid);
			$('#Tyosopimukset_tekijan_email').val(data['tekijan_email']);
			$('#Tyosopimukset_tekijan_nimi').val(data['tekijan_nimi']);
			$('#Tyosopimukset_tekijan_katuosoite').val(data['tekijan_katuosoite']);
			$('#Tyosopimukset_tekijan_pnumero').val(data['tekijan_pnumero']);
			$('#Tyosopimukset_tekijan_ptoimipaikka').val(data['tekijan_ptoimipaikka']);
			$('#Tyosopimukset_tekijan_puh').val(data['tekijan_puh']);
			$('#Tyosopimukset_tekijan_henkilotunnus').val(data['tekijan_henkilotunnus']);


      	},
  	error:function(data){
  		console.log(data); 
  	}
   });
 });

});
</script>

