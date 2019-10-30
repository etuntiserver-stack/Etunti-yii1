<?php

?>

<!-- begin: .tray-center -->
<div class="tray-center">

<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Muoka ilmoitus'); ?> #<?=$model->id?> </h2>


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
	         <span class="button"><?php echo Yii::t('main', 'Tiedosto'); ?></span>
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
			'digisten', 
			'digisten_ilmoitukset', 
			$model->id
		);
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
