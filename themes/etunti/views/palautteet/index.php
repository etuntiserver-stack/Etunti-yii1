<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Palautteet'); ?> 
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

   			    <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo date('d.m.Y',strtotime($from)); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
	
			   <div class="input-group">
				<?php
				$list = array();
		      		$l = Valikkoot::model()->findAll(" select_type='kategoria' ",array('order' => "select_type"));
				if(count($l) == 0)
		      		{
					$new_val = new Valikkoot;
					$new_val->select_type = "kategoria";
					$new_val->value = "Testi kategoria";
					if($new_val->save())
			      			$l = Valikkoot::model()->findAll(" select_type='kategoria' ",array('order' => "select_type"));
					else
						var_dump($new_val->getErrors());
				}
				foreach($l as $val)
					$list[$val->id] = $val->value;
		
		        		echo CHtml::dropDownList('kategoria', 'kategoria', $list,
					array('empty'=>'Kategoria', 'class'=>'form-control'));
		        	?>
				<span class="input-group-btn">
					<span class="btn btn-primary myBgColors muokaValiko" for="kategoria"><i class="fa fa-pencil-square-o"></i></span>
				</span>
			   </div>
                        </div>

			<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
			<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

<script type="text/javascript">
$(document).ready(function(){

/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

});
</script>


                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo date('d.m.Y',strtotime($to)); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Yritys / Yhteyshenkilö';
				if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
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


<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Työvuoro'); ?></th>
  <th><?php echo Yii::t('main', 'Aika'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Kategoria'); ?></th>
  <th><?php echo Yii::t('main', 'Palaute'); ?></th>
  <th><?php echo Yii::t('main', 'Tila'); ?></th>
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

if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
