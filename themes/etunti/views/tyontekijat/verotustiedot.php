<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>
<?php if(isset($_POST['tulosta'])): ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<style>
.tb .col1{ width: 3%; text-align: left; }

.tb .col2{ width: 9%; }
.tb .col2 input{ width: 80%; }
</style>
<h1><?php echo Yii::t('main', 'Verotustiedot'); ?></h1>
<?php $tulostus = true; ?>
<?php endif; ?>


<?php if(!isset($_POST['tulosta'])): ?>
<?php $tulostus = false; ?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
  	     <!-- tulostus -->
     	      <form action="#" class="form-group" target="_blank" method="POST">
      	      <input type="submit" name="tulosta" class="btn btn-success myBgColors" value="PDF">
     	      </form>
  	     <!-- tulostus -->
           </div>

              <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'Verotustiedot'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

		<?php 
		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
        	$tal = '';
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   $tal[$exV[1]] = $exV[0];
		}
		$selectedValues = 1;
		if(isset($_POST['aktiivinen']))
		$selectedValues = array($_POST['aktiivinen']=> Array('selected' => 'selected'));

		echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, 
		array('class'=>'gui-input','options' => $selectedValues)) 
		?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="nimi" value="<?php if(isset($_POST['nimi'])) echo $_POST['nimi']; ?>" placeholder="<?php echo Yii::t('main', 'Nimi'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="puhelin"  class="gui-input" value="<?php if(isset($_POST['puhelin'])) echo $_POST['puhelin']; ?>" placeholder="<?php echo Yii::t('main', 'Puhelin'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-phone"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="osoite" class="gui-input" value="<?php if(isset($_POST['osoite'])) echo $_POST['osoite']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>...">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-home"></i>
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
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>
<?php endif; ?>



<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive tb">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Nimi'); ?></th>
  <th><?php echo Yii::t('main', 'Tuloraja ajalle'); ?></th>
  <th><?php echo Yii::t('main', 'Palkkaa varten<br> Perusprosentti'); ?></th>
  <th><?php echo Yii::t('main', 'Lisäprosentti'); ?></th>
  <th><?php echo Yii::t('main', 'A Kuukaudessa'); ?></th>
  <th><?php echo Yii::t('main', 'Kahdessa viikossa'); ?></th>
  <th><?php echo Yii::t('main', 'Viikossa'); ?></th>
  <th><?php echo Yii::t('main', 'Päivässä'); ?></th>
  <th><?php echo Yii::t('main', 'Laskennallinen tuloraja<br> ATK-järjestelmiä varten'); ?></th>
  <th><?php echo Yii::t('main', 'B Ennakonpidätys yhden<br> tulorajan mukaan'); ?></th>
  </tr>
  </thead>
  <?php
	foreach($model as $data)
	{
		echo $this->renderPartial('_verotustiedot', array('data'=>$data, 'tulostus'=>$tulostus));
	}
  ?>
  </table>
 </div>
 </div>


   </div>
  </div>
</div>


<?php if(!isset($_POST['tulosta'])): ?>
<script type="text/javascript">
$(document).ready(function(){


$(document).delegate(".valRiviSuhteet","blur",function(){

	var tid = $(this).attr("tid");
	var sarake = $(this).attr("for");
	var thisVal = $(this).val();

	$(this).addClass('btn btn-success');

        $.ajax({
           url: 'muuta_suhteet',
           type: "POST",
           data: { "tid" : tid, "sarake" : sarake, "value" : thisVal },
           success: function(data){
		console.log(data);
           }
        });

});

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
<?php endif; ?>
