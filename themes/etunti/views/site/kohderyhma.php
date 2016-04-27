<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'Kohderyhmä'); ?> 
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
                          <label class="field select">

 		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo CHtml::dropDownList('ryhma', 'ryhma', $list,
		array('empty'=>'Valitse ryhmä','class'=>'gui-input'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>

                            <label for="firstname" class="field-icon">
                              <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Luo lista</button>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Mistä'); ?></th>
  <th><?php echo Yii::t('main', 'Yrityksen nimi'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteyshenkilö'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Postitoimipaikka'); ?></th>
  <th><?php echo Yii::t('main', 'puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Myyjä'); ?></th>
  </tr>
  </thead>
  <?php 
if(isset($data) and isset($_POST['ryhma']))
{
  foreach($data as $m)
  {
    echo '

	  <td>'.$m[0].'</td>
	  <td>'.$m[1].'</td>
	  <td>'.$m[2].'</td>
	  <td>'.$m[3].'</td>
	  <td>'.$m[4].'</td>
	  <td>'.$m[5].'</td>
	  <td>'.$m[6].'</td>
	  <td>'.$m[7].'</td>


	';	
  }
}
  ?>
  </table>
</div>

   </div>
  </div>




<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
