<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Tuotteet ja palvelut'); ?> 
		<?php echo CHtml::link('','/index.php/laskutusTuotteet/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää tuote') )); ?>
		</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="tuotenimi" value="<?php if(isset($_POST['tuotenimi'])) echo $_POST['tuotenimi']; ?>" placeholder="Tuotenimi..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="hinta_alv_0"  class="gui-input" value="<?php if(isset($_POST['hinta_alv_0'])) echo $_POST['hinta_alv_0']; ?>" placeholder="Hinta alv 0..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-tag"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="hinta_alv_sis" class="gui-input" value="<?php if(isset($_POST['hinta_alv_sis'])) echo $_POST['hinta_alv_sis']; ?>" placeholder="Hinta alv sis">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-key"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="alv" class="gui-input" value="<?php if(isset($_POST['alv'])) echo $_POST['alv']; ?>" placeholder="Alv..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="yksikko" class="gui-input" value="<?php if(isset($_POST['yksikko'])) echo $_POST['yksikko']; ?>" placeholder="Yksikko..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
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
  <th><?php echo Yii::t('main', 'Tuotenimi'); ?></th>
  <th><?php echo Yii::t('main', 'Hinta Alv 0'); ?></th>
  <th><?php echo Yii::t('main', 'Hinta Alv Sis'); ?></th>
  <th><?php echo Yii::t('main', 'Alv'); ?></th>
  <th><?php echo Yii::t('main', 'Yksikko'); ?></th>
  <?php /*
    if($netvisor == true)
    echo '<th>'.Yii::t('main', 'Netvisor').'</th>';
  */ ?>
  </tr>
  </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'viewData' => array( 'netvisor' => $netvisor ),
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
