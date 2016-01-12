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
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
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
    $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="mult" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>


                          </label>

   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>


                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-7">
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
    foreach($k as $kohde)
    {
	$avaimet .= '<div class="row">
			<div class="col-sm-6">'.$kohde->osoite.'</div>
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


$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });

});
</script>
<?php endif; ?>
