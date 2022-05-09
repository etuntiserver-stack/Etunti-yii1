<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'Työntekijät'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/tyontekijat/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää työntekijä') )); ?>

	 <div class="pull-right">
	  <form action="<?=Yii::app()->request->baseUrl?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="excel_list" value="true">
	    <input type="hidden" name="ext" value="xls">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	 </div>

<script type="text/javascript">
$(document).ready(function() {

  $(".submitForm").on('click', function(e){
	$('.lahetys').remove();
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });

  $(".kpl").click(function(){
	var tyontekijatPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "tyontekijatPerSivu" : tyontekijatPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
  });
});
</script>

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';
	   ($perSivu == 2000) ? $defcl2000 = 'btn-success' : $defcl2000 = 'btn-default';
	   ($perSivu == 10000) ? $defcl10000 = 'btn-success' : $defcl10000 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl2000.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   echo '<button class="btn '.$defcl10000.' kpl" kpl="10000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10000 '.Yii::t('main', 'asiakasta sivulla').'">10000</button>';
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
                          <label class="field select">

				<?php 
				$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
		        	$tal = array();
				foreach($a as $v){
				$exV = explode("/",$v->value);
				   if(isset($exV[0]) and isset($exV[1]))
				   $tal[$exV[1]] = $exV[0];
				}
				$selectedValues = 1;
				if(isset( Yii::app()->session['aktiivinen']))
				$selectedValues = array( Yii::app()->session['aktiivinen']=> Array('selected' => 'selected'));

				echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, 
				array('class'=>'gui-input','options' => $selectedValues)) 
				?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field select">

			        <?php
			       	$criteria = new CDbCriteria();
				$criteria->order = " value, value2 ";
				$criteria->condition = " select_type='tyoryhma' ";
				$vm=Valikkoot::model()->findAll($criteria);
			        ?>
				<select class="gui-input" name="tyoryhma">
				<option value=""><?php echo Yii::t('main', 'Valitse työryhmä'); ?></option>
				<?php foreach($vm as $tyoryhma): ?>
				<option value="<?=$tyoryhma->value?>" <?=(isset($_GET['tyoryhma']) and $_GET['tyoryhma'] == $tyoryhma->value)?'selected':''?>><?=$tyoryhma->value?></option>
				<?php endforeach; ?>
				</select>
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
				$mod = 'Tyontekijat';
				$sarake = 'tekijan_nimi';
				$placeholder = 'Nimi';
				if(isset(Yii::app()->session['tekijan_nimi']))  $postvalue = Yii::app()->session['tekijan_nimi']; 
				else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, array('tekijan_nimi', 'sukunimi'), $placeholder, $postvalue);
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
				$mod = 'Tyontekijat';
				$sarake = 'tekijan_puh';
				$placeholder = 'Puhelin';
				if(Yii::app()->session['tekijan_puh'])          $postvalue = Yii::app()->session['tekijan_puh']; 
				else $postvalue='';
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

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Tyontekijat';
				$sarake = 'tekijan_katuosoite';
				$placeholder = 'Osoite';
				if(isset(Yii::app()->session['tekijan_katuosoite'])) $postvalue = Yii::app()->session['tekijan_katuosoite'];
				else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-home"></i>
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
				$mod = 'Tyontekijat';
				$sarake = 'tekijan_email';
				$placeholder = 'Sähköposti';
				if(isset(Yii::app()->session['tekijan_email'])) 	
                                $postvalue = Yii::app()->session['tekijan_email']; 				       
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

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


<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive" id="tableContent">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th>ID</th>
  <th><?php echo Yii::t('main', 'Nimi'); ?></th>
  <th><?php echo Yii::t('main', 'Työpuhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Oma puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Työryhmä'); ?></th>
  <th><?php echo Yii::t('main', 'Sovelluksen versio'); ?></th>
  <th class="lahetys"></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
	'viewData' => array( 'current_app_versio' => $current_app_versio ), 

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

});
</script>
