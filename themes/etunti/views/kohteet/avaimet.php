<?php

?>
<?php if(isset($_POST['tulosta'])) : ?>
<style>
table{ width: 640px}
td{ border:1px #333 solid; padding: 3px 7px; }
legend{  padding: 3px 7px; }
</style>
<?php echo Yii::t('main','Tulostus pvm: ').date("d.m.Y"); ?>
<br>
<?php endif; ?>








<?php if(!isset($_POST['tulosta'])) : ?>
        <!-- begin: .tray-center -->
        <div class="tray-center">


   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->
              <h2 class="myBgColors p10"> <i class="fa fa-key"></i> <?php echo Yii::t('main', 'AVAIMET'); ?> 
		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field">


   <?php
    $criteria = new CDbCriteria();
    $criteria->order = "tekijan_nimi";
    $criteria->condition = "aktiivinen=1";
    $m = Tyontekijat::model()->findAll($criteria);

    $list = CHtml::listData($m, 'id', 'tekijan_nimi');

    echo '<select name="Tekija[]" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset($_POST['Tekija']) and in_array($key,$_POST['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>


                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="avain" value="<?php if(isset($_POST['avain'])) echo $_POST['avain']; ?>" placeholder="Avain">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-key"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-5">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>

<?php endif; ?>


  <div class="panel heading-border">
   <div class="panel-body">

<table class="table table-stripped">
<thead class="myBgColors">
 <tr>
  <th><?php echo Yii::t('main','Työntekijä'); ?></th>
  <th><?php echo Yii::t('main','Avain'); ?></th>
 </tr>
</thead>
<tbody>
  <?php
  foreach($model as $data)
  {
    $k = Kohteet::model()->findAll(" SUBSTRING_INDEX(kenella_on_avain, '//', 1) = '".$data->id."' ");
	$avaimet = '';
    $i = 0;
    foreach($k as $kohde)
    {
    $i++;
	$avaimet .= '<div class="row">
			<div class="col-sm-6">'.$i.'. '.$kohde->osoite.'</div>
			<div class="col-sm-6"><b>'.$kohde->avain.'</b></div>
		     </div>';
    }

    echo '<tr>';
    echo '<td>'.$data->tekijan_nimi.'</td>';
    echo '<td>'.$avaimet.'</td>';
    echo '</tr>';
  }
  ?>
</tbody>
</table>

   </div>
  </div>


<?php if(!isset($_POST['tulosta'])) : ?>
<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#yhtveto").submit();
});


$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});


$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
});

});
</script>
<?php endif; ?>
