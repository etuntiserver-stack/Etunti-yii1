<?php

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p20"> <i class="fa fa-gear"></i> <?php echo Yii::t('main', 'TIEDOSTOT'); ?> </h2>



            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">



<div class="row">
 <div class="col-sm-6">
	<?php
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/*')) as $file) {
	$i++;
	$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div> ';

				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				echo CHtml::link(basename($file),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->

	echo '</div>';
	$kuvat[$i] = $file;
	}
	?>
 </div>
</div>

<hr>



<div class="row">
 <div class="col-sm-12">

 <div class="admin-form col-sm-6" id="onlinevarausehdot">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Onlinevarausehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_ove').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_onlinevarausehdot" id="tiedostoUP_ove" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Sopimukset jne.'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_sop').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUP_sop" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Toimitusehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_te').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_toimitusehdot" id="tiedostoUP_te" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Konevuokraus toimitusehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_kv').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_Konevuokraus_toimitusehdot" id="tiedostoUP_kv" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'eDico APP käyttöehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_eke').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_edico_kayttoehdot" id="tiedostoUP_eke" placeholder="Valitse tiedosto..">
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
</div>


                 </div>
                </div>
              </div>
            </div>

        </div>



<script type="text/javascript">
$(document).ready(function(){

  $(".poistaTiedosto").click(function(){
	var forThis = $(this).attr("this");
	var model = $(this).attr("model");
	var forID = $(this).attr("for");
	if(confirm('Oletko varmaa?'))
	{
        $.ajax({
           url: "update?id="+model,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
	}
  });


});
</script>
