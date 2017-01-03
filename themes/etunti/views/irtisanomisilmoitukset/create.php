<?php

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Luo irtisanomisilmoitus'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

<?php if (file_exists(Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/'.$this->tiedostonNimike().'.docx')) : ?>

                 <div class="row">
                  <div class="col-sm-4">

				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tyontekija', // name
						'form-control', //class
						'tyontekijat', // id
						null, //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
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
	<?php echo Yii::t('main', 'Mallitiedosto puutuu, jos haluat ominaisuuden käyttöön ota yhteyttä'); ?> <a href="mailto:tuki@etunti.fi">tuki@etunti.fi<a>
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

			$('#Irtisanomisilmoitukset_tid').val(tid);
			$('#Irtisanomisilmoitukset_tekijan_email').val(data['tekijan_email']);
			$('#Irtisanomisilmoitukset_tekijan_nimi').val(data['tekijan_nimi']);
			$('#Irtisanomisilmoitukset_tekijan_katuosoite').val(data['tekijan_katuosoite']);
			$('#Irtisanomisilmoitukset_tekijan_pnumero').val(data['tekijan_pnumero']);
			$('#Irtisanomisilmoitukset_tekijan_ptoimipaikka').val(data['tekijan_ptoimipaikka']);
			$('#Irtisanomisilmoitukset_tekijan_puh').val(data['tekijan_puh']);
			$('#Irtisanomisilmoitukset_tekijan_henkilotunnus').val(data['tekijan_henkilotunnus']);


      	},
  	error:function(data){
  		console.log(data); 
  	}
   });
 });

});
</script>

