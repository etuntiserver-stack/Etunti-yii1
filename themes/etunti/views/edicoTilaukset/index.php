<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'eDico Tilaukset'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field select">

			   <select name="asiakas_id" class="gui-input">
			   <option value=>Asiakkaat</option>
			    <?php
				$criteria = new CDbCriteria;
				$criteria->select = 'asiakas_id';
				$criteria->group = 'asiakas_id';
				$asiakas = EdicoTilaukset::model()->findAll($criteria);
				foreach($asiakas as $item)
				{
				  if(isset($item->asiakkaat->id))
				    echo '<option value="'.$item->asiakkaat->id.'">'.$item->asiakkaat->Fullname.'</option>';
				}
			    ?>
			   </select>

                            <i class="arrow double"></i>
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
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Tilauksen osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Toivottu aikaa'); ?></th>
  <th><?php echo Yii::t('main', 'Tilaus'); ?></th>
  <th><?php echo Yii::t('main', 'Viesti'); ?></th>
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


$(document).delegate(".vastaanotettu","click",function(){

	var thisVid = $(this).attr("for");
	var id = $(this).attr("for").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/viestinta/vastaanotettu?id='+id[1],
           success: function(data){
		console.log(data);
		$("#"+thisVid).hide('slow');
           }
        });

});

});
</script>
