<?php
$tiedostonNimi = 'crm_kirje';

if(isset($_POST['poistaTemplate'])){
	unlink($_POST['poistaTemplate']);
	exit;
}

  if(isset($_POST[$tiedostonNimi]))
  {

    if (!file_exists(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain, 0777, true);
    }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/templates/'.Yii::app()->user->domain.'/';
  $tiedosto = $tiedostonNimi.'.docx';

  $uploadfile = $uploaddir . basename($tiedosto);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

    } else {
	echo Yii::t('main', 'Lataaminen ei onnistuu');
    }
  }

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Kirjeiden hallinta'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/kirjeidenHallinta/create',array('class'=>'btn btn-default fa fa-plus')); ?>
		<button class="btn btn-default" data-toggle="collapse" data-target="#admin-form"><?php echo Yii::t('main', 'Extrat'); ?></button>
	</h2>




            <div class="admin-form collapse" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">




<div class="col-sm-6">
<?php
  $nimike	= $tiedostonNimi.'.docx';
  $polku 	= Yii::app()->basePath;
  $tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

  if (file_exists($polku.$tiedosto))
  echo '<a href="'.$tiedosto.'">'.$nimike.'</a> <span class="poista btn btn-xs btn-danger" for="'.$polku.$tiedosto.'">X</span>';

  echo '
  <form action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button">'.Yii::t('main', 'Kirje template').'</span>
         <input type="file" class="gui-file" name="file" onChange="document.getElementById(\'tiedostoUP\').value = this.value;">
         <input type="text" class="gui-input" name="'.$tiedostonNimi.'" id="tiedostoUP" placeholder="Valitse tiedosto..">
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

  echo '<a href="/../tiedostot/templates/mallit/'.$tiedostonNimi.'.docx">'.Yii::t('main', 'Tässä').'</a> '.Yii::t('main', 'on templaten esimerkki.');
?>
</div>


<div class="col-sm-6">
<p><b>Template variables</b></p><br>

<textarea class="form-control" rows="10" cols="60">
${paivays}
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
  <th><?php echo Yii::t('main', 'Kohderyhmä'); ?></th>
  <th><?php echo Yii::t('main', 'Teksti'); ?></th>
  <th><?php echo Yii::t('main', 'Tiedostot'); ?></th>
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




