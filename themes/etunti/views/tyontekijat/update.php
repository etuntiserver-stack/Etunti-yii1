<?php

if(isset($_POST['uploaded']))
{

  if (!file_exists(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'.jpg');
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     echo "";
  } 
}


if(isset($_POST['uploaded_t']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/tekijat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/tekijat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/tekijat/'.Yii::app()->user->domain.'/';
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
	    <div class="form-inline">
	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,null);
	   ?>

  	     <!-- tulostus -->
     	      <form action="tulosta?id=<?php echo $model->id; ?>" class="form-group" target="_blank" method="POST">
      	      <input type="submit" name="tulosta" class="btn btn-success myBgColors" value="PDF">
     	      </form>
  	     <!-- tulostus -->
	    </div>
           </div>


	   <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo $model->tekijan_nimi; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>






<hr>

<div class="row">



 <div class="admin-form col-sm-6">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Työntekijän kuva'); ?></span>
         <input type="file" class="gui-file" name="file" id="i_file" onChange="document.getElementById('uploader1').value = this.value;">
         <input type="text" class="gui-input" name="uploaded" id="uploader1" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary btn-group myBgColors" />
	</span>
    </div>
  </form>
 </div>


 <div class="admin-form col-sm-6">
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
          <input type="submit" value="Lataa" class="btn btn-primary btn-group myBgColors" />
	</span>
    </div>
  </form>
 </div>

</div>




<br>

<div class="row">
  <div class="col-sm-12">
<?php

	$i = 0;
	foreach(array_reverse(glob('tiedostot/tekijat/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  <a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
	$kuvat[$i] = $file;
	}
?>
  </div>
</div>

<br>


        <!-- begin: .tray-center -->
        <div class="tray-center">


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<div class="row">
  <div class="col-sm-12">
	<?php 
		$tid = $model->id;


		$ts = Tyosuhdet::model()->find(" tid='".$model->id."' ");
		if(isset($ts['id']))
		{

			$m=Tyosuhdet::model()->findbypk($ts['id']);

		    if(isset($_POST['Tyosuhdet']))
		    {
			$m->attributes=$_POST['Tyosuhdet'];
			$m->tid=$tid;
			if($m->save())
				$this->redirect(array('update','id'=>$model->id));
		    }

			echo $this->renderPartial('//tyosuhdet/_form', array('model'=>$ts));
		} else {

			$m=new Tyosuhdet;

		    if(isset($_POST['Tyosuhdet']))
		    {
			$m->attributes=$_POST['Tyosuhdet'];
			$m->tid=$tid;
			if($m->save())
				$this->redirect(array('update','id'=>$model->id));
		    }

			echo $this->renderPartial('//tyosuhdet/_form',array('model'=>$m));
		}
	?>
  </div>
</div>


                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>





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
