<?php
$head = '';

if(!empty($model->yhteyshenkilo))
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
	   <?php     
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
	   ?>
	   </div>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo $head; ?> </h2>


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
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUP" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary myBgColors" />
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



	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakas historia'); ?> </h2>

<div class="row">
  <div class="col-sm-12">
        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<table class="table">
 <tr>
  <th><?php echo Yii::t('main', 'Tarjoukset'); ?></th>
 </tr>

  <?php
  $criteria=new CDbCriteria;
  $criteria->condition = " asiakas_id='".$model->id."' ";
  $tar = CrmTarjoukset::model()->findAll($criteria);
  foreach($tar as $data)
  {

		$f = '';
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".docx"))
	 	$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.docx">'.$data->liite.'.docx</a>';
		$f .= '<br>';
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
		$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.pdf">'.$data->liite.'.pdf</a>';


		$s = '';
		if($data->status == 0 and
  		(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
		)
		{
			$s .= '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'odotta lähetystä').'</button>';
		} elseif($data->status == 1){
			$s .= '<button class="btn btn-warning btn-block">'.Yii::t('main', 'Lähetetty').'</button>';
		} elseif($data->status == 2){
			$s .= '<button class="btn btn-success btn-block">'.Yii::t('main', 'Hyväksytty').'</button>';
		} elseif($data->status == 3){
			$s .= '<button class="btn btn-danger btn-block">'.Yii::t('main', 'Hylätty').'</button>';
		}

  	echo '<tr><td>';
		echo '
		<div class="row">
		   <div class="col-sm-4">'.date("d.m.Y", strtotime($data->time)).'</div>
		   <div class="col-sm-4">'.$f.'</div>
		   <div class="col-sm-4">'.$s.'</div>
		</div>';
	echo '</td></tr>';
  }
  ?>

</table>



                </div>
              </div>
            </div>
        </div>
  </div>
</div>

<br><br>












<script type="text/javascript">
$(document).ready(function(){


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
