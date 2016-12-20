<?php

  $template = $this->tiedostonNimike();


  if(isset($_POST['poistaTemplate'])){
	unlink($_POST['poistaTemplate']);
	exit;
  }

  if(isset($_POST[$template]))
  {

    if (!file_exists(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain, 0777, true);
    }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/';
  $template = $template.'.docx';

  $uploadfile = $uploaddir . basename($template);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

    } else {
	echo Yii::t('main', 'Lataaminen ei onnistuu');
    }

  }
?>



        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Irtisanomisilmoitukset'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/irtisanomisilmoitukset/create',array('class'=>'btn btn-default fa fa-plus')); ?>
		<button class="btn btn-default" data-toggle="collapse" data-target="#admin-form"><?php echo Yii::t('main', 'Extrat'); ?></button>
	</h2>





            <div class="admin-form collapse" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-6">
                        <div class="section">


<?php
  $nimike	= $template.'.docx';
  $polku 	= Yii::app()->basePath;
  $tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

  if (file_exists($polku.$tiedosto))
  echo '<a href="'.$tiedosto.'">'.$nimike.'</a> <span class="poista btn btn-xs btn-danger" for="'.$polku.$tiedosto.'">X</span>';

  echo '
  <form action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button">'.Yii::t('main', 'Template tiedosto').'</span>
         <input type="file" class="gui-file" name="file" onChange="document.getElementById(\'tiedostoUP\').value = this.value;">
         <input type="text" class="gui-input" name="'.$template.'" id="tiedostoUP" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa docx" class="btn btn-primary btn-group myBgColors" />
	</span>
    </div>
  </form>
 ';

  echo '<a href="/../lib/mallit/'.$this->tiedostonNimike().'.docx">'.Yii::t('main', 'Tässä').'</a> '.Yii::t('main', 'on templaten esimerkki.');
?>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="section">
			<h3><?php echo Yii::t('main', 'Template variables'); ?></h3>

<textarea class="form-control" rows="17" cols="60">
${tyonantaja}
${tyonantaja_osoite}
${tyonantaja_y_tunnus}
${tyonantaja_puhelin}
${tyonantaja_sahkoposti}

${tyontekija_nimi}
${tyontekija_osoite}
${tyontekija_henkilotunnus}
${tyontekija_puhelin}
${tyontekija_sahkoposti}

${aika}
${paikka}
${johtajan_nimi}
${teksti}</textarea>

                        </div>
                      </div>



                    </div>

                      <div class="">
        	        <!--<input type="submit" class="btn btn-primary btn-lg haemob myBgColors" value="<?php echo Yii::t('main', 'Luo'); ?>">-->
		      </div>

<br>




                </div>
              </div>
            </div>




        <!-- loppu: .tray-center -->
        </div>






<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Nimi'); ?></th>
  <th><?php echo Yii::t('main', 'Kirjallinen varoitus'); ?></th>
  <th><?php echo Yii::t('main', 'Tiedosto'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>
 </div>
</div>


   </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){


$(".poista").click(function(){
	var polku = $(this).attr('for');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "poistaTemplate" : polku },
           success: function(data){
		console.log(data);
		window.location.href="index";
           }
        });
});



});
</script>
