<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'AVAIMET'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/avaimet/create',array('class'=>'btn btn-default fa fa-plus')); ?>

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   ?>
	 </div>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepicker" name="pvm" value="<?php if(isset($_GET['pvm'])) echo $_GET['pvm']; ?>" placeholder="<?php echo Yii::t('main', 'Päivämäärä'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="avainnumero"  class="gui-input" value="<?php if(isset($_GET['avainnumero'])) echo $_GET['avainnumero']; ?>" placeholder="<?php echo Yii::t('main', 'Avain nro..'); ?>..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-file-text-o"></i>
                            </label>
                          </label>
                        </div>
                      </div>



                      <div class="col-md-2 col-sm-offset-5">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>



<?php
       	$criteria = new CDbCriteria();
	$criteria->condition = " status=0 AND tekija='toimisto' ";
	$vi = Viestinta::model()->findAll($criteria);

	if(isset($vi[0]->id) and !empty($vi[0]->id))
	{
	echo '
<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">';

	echo '<h2>'.Yii::t('main','Vastaamattomat viestit').'</h2>';
	echo '<div class="row">';
	
	   foreach($vi as $v)
	   {

	   	echo '<div class="col-sm-4" id="v_'.$v->id.'">';
	   	echo '<div class="well">';

		echo '<span>'.str_replace("\n","<br>",$v->viesti).'</span>

		<div class="row">
	 	 <div class="pull-right">
		  '.CHtml::link("Vasta", Yii::app()->request->baseUrl.'/index.php/viestinta/update?id='.$v->id, array('class'=>'btn btn-xs btn-primary')).'
		  <div class="btn btn-xs btn-default vastaanotettu" for="v_'.$v->id.'">'.Yii::t('main','Sulje').'</div>
		 </div>
		</div>';
		echo '</div>';
		echo '</div>';
	   }
	echo '</div>
   </div>
  </div>
</div>
';
	}
?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th><?php echo Yii::t('main', 'Avainnumero'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
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
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});

 $(".kpl").click(function(){
	var asiakkaatPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "asiakkaatPerSivu" : asiakkaatPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
 });

});
</script>
