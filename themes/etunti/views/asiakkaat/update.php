<?php


$head = '';

if(!empty($model->yrityksen_nimi))
$head = $model->yrityksen_nimi;
elseif(empty($model->yrityksen_nimi) and !empty($model->yhteyshenkilo))
$head = $model->yhteyshenkilo;
else
$head = $model->osoite;


if(isset($_POST['uploaded_t']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/asiakkaat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/asiakkaat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/asiakkaat/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}

if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
exit;
}
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <button class="btn btn-primary myBgColors" id="historiaSiirto"><?php echo Yii::t('main', 'Historia'); ?></button>

	   <?php
	   $a = Asetukset::model()->findbypk(1);
	   if($a->netvisor_kaytto != 1)
	   {
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
	   }
	   ?>

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


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>





<div class="row">
  <div class="col-sm-6">



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


	$i = 0;
	foreach(array_reverse(glob('tiedostot/asiakkaat/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  &nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
	$kuvat[$i] = $file;
	}
   ?>
     
   </div>
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
			'naytaTyovuorot'=>true,
			'naytaLaskut'=>true,
			'naytaTarjoukset'=>true,
			'naytaPalautteet'=>true,
			'naytaVinkit'=>true
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
