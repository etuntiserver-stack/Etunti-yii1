<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">
        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Laskuttettavat asiakkaat'); ?></h2>

	<form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">
                      <div class="col-md-2">
                        <div class="section">
                         <label class="field select">
							<select name="kk" id="kk" class="gui-input">
							<option value=""><?php echo Yii::t('main', 'Valitse kuukausi'); ?></option>
							<?php
							$months = Lasku::months();
							$i = 1;
							$month = strtotime(date("Y-m-d", strtotime("first day of this month")));
							while($i <= 24)
							{
								$month_name = date('n', $month);
								$year 	= date('Y', $month);
								(isset($_GET['kk']) and $_GET['kk'] == $year.'-'.$month_name)? $selected = 'selected' : $selected = '';
								echo '<option value="'.$year.'-'. $month_name.'" '.$selected.'>'.$months[$month_name].' '.$year.'</option>';
								$month = strtotime('-1 month', $month);
								$i++;
							}
							?>
							</select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
							<?php
								$list = array();
								$l = Valikkoot::model()->findAll(" select_type='tyoryhma' ",array('order' => "select_type"));
								foreach($l as $v)
									$list[$v->id] = $v->value;

								$selected = [];
								if(isset($_GET['tyoryhma']) and is_array($_GET['tyoryhma'])){
										foreach($_GET['tyoryhma'] as $rnum){
											$selected[$rnum] = ['selected'=>true];
										}
								}
				
								if(count($list) > 0)
								{
									echo CHtml::dropDownList('tyoryhma', 'tyoryhma[]', $list,
									array(
										'empty'=>'Valitse työryhmä',
										'class'=>'form-control form-group selectpicker', 
										'id'=>'tyoryhmaSelect', 
										'multiple' => 'yes', 
										'options' => $selected
										)
									);
								}
							?>
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
								$sarake = 'yrityksen_nimi';
								$placeholder = 'Asiakas';
								if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
								else $postvalue='';				
						 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
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

<?php if($kk !== null) : ?>
<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

	<div class="row">
	 <div class="table-responsive" id="tableContent">
	  <table class="table table-striped" id="mobileTable">
	  <thead class="myBgColors">
	  <tr>
	  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
	  <th><?php echo Yii::t('main', 'Lasku'); ?></th>
	  <th><?php echo Yii::t('main', 'KK summ.'); ?></th>
	  <th><?php echo Yii::t('main', 'Yhteensä tunnit'); ?></th>
	  <th></th>
	  </tr>
	  </thead>
	  <?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_la_asiakkaat',
	  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
		'viewData' => ['lista' => $lista, 'kk_hinta' => $kk_hinta], 
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
<?php endif; ?>

