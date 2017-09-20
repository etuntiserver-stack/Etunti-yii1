<?php

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Sopimukset'); ?> 
		
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/crmSopimukset/create',array('class'=>'btn btn-default fa fa-plus')); ?>
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


                      <div class="col-md-6">
                        <div class="section">

			<!-- lataus lomake -->
			<form action="#" class="form-input" method="post" enctype="multipart/form-data">
			     <div class="section input-group">
			       <label class="field prepend-icon append-button file">
			         <span class="button"><?=Yii::t('main', 'Työtodistus template')?></span>
			         <input type="file" class="gui-file" name="file" onChange="document.getElementById(\'tiedostoUP\').value = this.value;">
			         <input type="text" class="gui-input" name="file_upload" id="tiedostoUP" placeholder="Valitse tiedosto..">
			         <label class="field-icon">
			          <i class="fa fa-upload"></i>
			         </label>
			       </label>
				<span class="input-group-btn">
			          <input type="submit" value="Lataa docx" class="btn btn-primary btn-group myBgColors" />
				</span>
			    </div>
			</form>
			<!-- lataus lomake -->

			<!-- uploaded tiedostot -->
			<legend><?=Yii::t('main', 'Ladatut tiedostot')?></legend>
			<div class="row">
			 <div class="col-sm-12">
			  <table class="table table-striped">
			  <?php
			  foreach(glob(Yii::app()->baseUrl.$this->templates_polkku().'/*.docx') as $file) 
			  {
				$explNimi = explode("/",$file);

			 	echo '<tr><td>';

				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				echo CHtml::link(end($explNimi),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'docx'),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->
			 	echo '</td><td><i class="poista text-danger fa fa-trash link" for="'.Yii::app()->baseUrl.$this->templates_polkku().end($explNimi).'"></i><td>';
			 	echo '</tr>';

			  }
			  ?>
			  </table>
			 </div>
			</div>
			<!-- uploaded tiedostot -->


                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="section">
			  <a class="btn btn-primary myBgColors" href="/../lib/mallit/crm_sopimus.docx"><?=Yii::t('main', 'Esimerkki tiedosto')?></a> 
			  <button class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#tmpl_vars">
				<?=Yii::t('main', 'Käytettävät muuttujat'); ?> <i class="caret"></i>
			  </button>
			  <div class="collapse" id="tmpl_vars"><?=str_replace("\n", "<br>", $this->template_variables())?></div>
                        </div>
                      </div>




<br>

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
  <th></th>
  <th><?php echo Yii::t('main', 'Voimassa'); ?></th>
  <th><?php echo Yii::t('main', 'Sopimuksen kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Sopimus'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Lähettäminen'); ?></th>
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
	if(confirm('Haluatko varmaasti poista?'))
	{
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "poistaTemplate" : polku },
           success: function(data){
		console.log(data);
		window.location.href="index";
           }
        });
	}
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




