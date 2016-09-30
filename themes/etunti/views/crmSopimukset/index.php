<?php


if(isset($_POST['poistaTemplate'])){
	unlink($_POST['poistaTemplate']);
	exit;
}

foreach($tal as $k=>$v)
{

  if(isset($_POST[$k]))
  {

    if (!file_exists(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain, 0777, true);
    }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/';
  $array = explode('.', $_FILES['file']['name']);
  $extension = end($array);
  $tiedosto = $k.'.docx';

  $uploadfile = $uploaddir . basename($tiedosto);
    if ($extension == 'docx' and move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

    } else {
	echo Yii::t('main', 'Lataaminen ei onnistuu');
    }
  }


}



?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Sopimukset'); ?> 
		
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/crmSopimukset/create',array('class'=>'btn btn-default fa fa-plus')); ?>
		<button class="btn btn-default" data-toggle="collapse" data-target="#admin-form"><?php echo Yii::t('main', 'Extrat'); ?></button>
	</h2>





            <div class="admin-form collapse" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

<?php /*

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">

                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="osoite" value="<?php if(isset($_POST['osoite'])) echo $_POST['osoite']; ?>" placeholder="Osoite..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="yrityksen_nimi" value="<?php if(isset($_POST['yrityksen_nimi'])) echo $_POST['yrityksen_nimi']; ?>" placeholder="Yritys..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                      </div>

		      <?php if(isset($_POST['aktiivinen'])) echo '<input type="hidden" id="akt" value="'.$_POST['aktiivinen'].'">'; ?>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


			   <select class="gui-input" name="aktiivinen" id="aktiivinen">
       				<option value="1">Aktiiviset</option>
       				<option value="0">Passiviset</option>
       				<option value="kaikki">Kaikki</option>
			   </select>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="yhteyshenkilo" value="<?php if(isset($_POST['yhteyshenkilo'])) echo $_POST['yhteyshenkilo']; ?>" placeholder="Yhteyshenkilö">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="puhelin"  class="gui-input" value="<?php if(isset($_POST['puhelin'])) echo $_POST['puhelin']; ?>" placeholder="Puhelin">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-phone"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="sahkoposti" class="gui-input" value="<?php if(isset($_POST['sahkoposti'])) echo $_POST['sahkoposti']; ?>" placeholder="<?php echo Yii::t('main','Sähköposti'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>

*/ ?>




<div class="row">
<?php

  $i = 0;
  foreach($tal as $key=>$val)
  {

  $i++;

  $nimike	= $key.'.docx';
  $polku 	= Yii::app()->basePath;
  $tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

  echo '<div class="admin-form col-sm-6">';

  if (file_exists($polku.$tiedosto))
  echo '<a href="'.$tiedosto.'">'.$nimike.'</a> <span class="poista btn btn-xs btn-danger" for="'.$polku.$tiedosto.'">X</span>';

  echo '
  <form action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button">'.$val.'</span>
         <input type="file" class="gui-file" name="file" onChange="document.getElementById(\'tiedostoUP_'.$i.'\').value = this.value;">
         <input type="text" class="gui-input" name="'.$key.'" id="tiedostoUP_'.$i.'" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa docx" class="btn btn-primary btn-group myBgColors" />
	</span>
    </div>
  </form>
  <p><a href="/../tiedostot/templates/mallit/'.$nimike.'">'.Yii::t('main', 'Tässä').'</a> '.Yii::t('main', 'on '.$nimike.' templaten esimerkki.').'</p>
  <br>
 </div>
 ';
  

  }


?>
</div>


<div class="">
<p><b>Template variables</b></p><br>

<textarea class="form-control" rows="10" cols="60">
${paivays}

${yritys}
${y_tunnus}
${yrityksen_osoite}
${yrityksen_postinumero}
${yrityksen_toimipaikka}
${yrityksen_puhelin}
${yrityksen_sahkoposti}
${yrityksen_johtaja}

${asiakas}
${asiakkaan_osoite}
${asiakkaan_postinumero}
${asiakkaan_toimipaikka}
${asiakkaan_puhelin}
${asiakkaan_sahkoposti}

${teksti}
</textarea>
</div>


                </div>
              </div>
            </div>




        <!-- loppu: .tray-center -->
        </div>


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Teksti'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Lähettäminen'); ?></th>
  <th></th>
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


<script type="text/javascript">
$(document).ready(function(){

if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);

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

$(".haemob").click(function(){
	$("#mobForm").submit();
});

$(".laheta").click(function(){
	var id = $(this).attr('for');

        $.ajax({
           url: 'laheta',
           type: "POST",
           data: { "id" : id },
           success: function(data){
		console.log(data);
		window.location.reload();
           }
        });
});

});
</script>




