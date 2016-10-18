<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKKAAT'); ?> 
	<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/asiakkaat/create',array('class'=>'btn btn-default fa fa-plus')); ?>

	 <div class="pull-right">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100">100</button>';

	   ?>
	 </div>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">

                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'osoite';
				$placeholder = 'Osoite';
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Yritys';
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                      </div>

		      <?php if(isset($_POST['aktiivinen'])) echo '<input type="hidden" id="akt" value="'.$_POST['aktiivinen'].'">'; ?>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="aktiivinen" id="aktiivinen">
       				<option value="1"><?php echo Yii::t('main', 'Aktiiviset'); ?></option>
       				<option value="0"><?php echo Yii::t('main', 'Passiviset'); ?></option>
       				<option value="kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

		      <?php if(isset($_POST['ryhma'])) echo '<input type="hidden" id="ryhma" value="'.$_POST['ryhma'].'">'; ?>
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
					array('empty'=>'Valitse ryhmä','class'=>'form-control form-group', 'id'=>'ryhmaSelect'));
					}
			?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yhteyshenkilo';
				$placeholder = 'Yhteyshenkilö';
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'puhelin';
				$placeholder = 'Puhelin';
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-phone"></i>
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


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-hovered" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		'.Yii::t('main', 'Yritys').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=yrityksen_nimi&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=yrityksen_nimi&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo Yii::t('main', 'Osoite'); ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		'.Yii::t('main', 'Yhteyshenkilo').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=yhteyshenkilo&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=yhteyshenkilo&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		'.Yii::t('main', 'Puhelin').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=puhelin&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=puhelin&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		'.Yii::t('main', 'Sähköposti').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=sahkoposti&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=sahkoposti&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		'.Yii::t('main', 'Ryhmä').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=ryhma&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=ryhma&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>


  <th><?php echo Yii::t('main', 'Tyyppi'); ?></th>
  <?php
    if($netvisor == true)
    echo '<th>'.Yii::t('main', 'Netvisor').'</th>';
  ?>
  
  <?php
	if($this->tas(2))
		echo '<th>'.Yii::t('main', 'Työvuorot').'</th>';
  ?>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'viewData' => array( 'netvisor' => $netvisor ),
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

 if($("#ryhma").val())
 $("#ryhmaSelect").val($("#ryhma").val());


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
