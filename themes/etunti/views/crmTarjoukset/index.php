<?php
if(isset($_POST['poistaTemplate'])){
	unlink($_POST['poistaTemplate']);
	exit;
}

  if(isset($_POST['crm_tarjous']))
  {

    if (!file_exists(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain, 0777, true);
    }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/';
  $tiedosto = 'crm_tarjous.docx';

  $uploadfile = $uploaddir . basename($tiedosto);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

    } else {
	echo Yii::t('main', 'Lataaminen ei onnistuu');
    }
  }

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Tarjoukset'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/crmTarjoukset/create',array('class'=>'btn btn-default fa fa-plus')); ?>

	<!-- Mallitiedoston oikeus -->
	<?php
	   $checkOikeus = "mallitiedostot_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $oikeusMallitiedoston = $site[0]->checkOikeusFields($checkOikeus);
	?>
	<?php if($oikeusMallitiedoston == 1) : ?>
		<button class="btn btn-default" data-toggle="collapse" data-target="#admin-form"><?php echo Yii::t('main', 'Mallitiedosto'); ?></button>
	<?php endif; ?>
	<!-- Mallitiedoston oikeus -->
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




<div class="col-sm-6">
<?php
  $nimike	= 'crm_tarjous.docx';
  $polku 	= Yii::app()->basePath;
  $tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

  if (file_exists($polku.$tiedosto))
  echo '<a href="'.$tiedosto.'">'.$nimike.'</a> <span class="poista btn btn-xs btn-danger" for="'.$polku.$tiedosto.'">X</span>';

  echo '
  <form action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button">'.Yii::t('main', 'Tarjous template').'</span>
         <input type="file" class="gui-file" name="file" onChange="document.getElementById(\'tiedostoUP\').value = this.value;">
         <input type="text" class="gui-input" name="crm_tarjous" id="tiedostoUP" placeholder="Valitse tiedosto..">
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

  echo '<a href="/../lib/mallit/crm_tarjous.docx">'.Yii::t('main', 'Tässä').'</a> '.Yii::t('main', 'on templaten esimerkki.');
?>
</div>


<div class="col-sm-6">
<p><b>Template variables</b></p><br>

<textarea class="form-control" rows="10" cols="60">
${yritys}
${yrityksen_osoite}
${yrityksen_postinumero} ${yrityksen_toimipaikka} ${paivays}

${asiakas}
${asiakkaan_osoite}
${asiakkaan_postinumero} ${asiakkaan_toimipaikka}

${teksti}
${tyonkuvaus}
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
  <th><?php echo Yii::t('main', 'Tarjous'); ?></th>
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


if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);


$(".haemob").click(function(){
	$("#mobForm").submit();
});

$(".laheta").click(function(){
	var id = $(this).attr('for');
	window.location.href="laheta?id=" + id;
/*
        $.ajax({
           url: 'laheta',
           type: "POST",
           data: { "id" : id },
           success: function(data){
		console.log(data);
		window.location.reload();
           }
        });
*/

});

});
</script>




