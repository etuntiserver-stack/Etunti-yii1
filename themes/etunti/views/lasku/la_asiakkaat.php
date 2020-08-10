<?php
$months=array(
	1=>Yii::t('main', 'Tammikuu'),
	2=>Yii::t('main', 'Helmikuu'),
	3=>Yii::t('main', 'Maaliskuu'),
	4=>Yii::t('main', 'Huhtikuu'),
	5=>Yii::t('main', 'Toukokuu'),
	6=>Yii::t('main', 'Kesäkuu'),
	7=>Yii::t('main', 'Heinäkuu'),
	8=>Yii::t('main', 'Elokuu'),
	9=>Yii::t('main', 'Syyskuu'),
	10=>Yii::t('main', 'Lokakuu'),
	11=>Yii::t('main', 'Marraskuu'),
	12=>Yii::t('main', 'Joulukuu')
	);
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
				<select name="tilanne" id="tilanne" class="gui-input">
				<option value=""><?php echo Yii::t('main', 'Valitse tilanne'); ?></option>
				<?php 
				$arr = [
					'laskutettavat_m' => Yii::t('main', 'Laskutettavat Mobiili'),
					//'laskuttamattomat_m' => Yii::t('main', 'Laskuttamattomat Mobiili'),
					//'laskutettavat_tv' => Yii::t('main', 'Laskutettavat Työvuorot'),
					//'laskuttamattomat_tv' => Yii::t('main', 'Laskuttamattomat Työvuorot'),
				]; 
				foreach($arr as $k => $v)
					echo '<option value="'.$k.'" '.((isset($_GET['tilanne']) and $_GET['tilanne'] == $k)? 'selected' : '' ).'>'.$v.'</option>';
				?>
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
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
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
	  <th></th>
	  </tr>
	  </thead>
	  <?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_la_asiakkaat',
	  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
		'viewData' => array( 'tilanne' => $_GET['tilanne'], 'lista' => $lista, 'tuotteet_lista' => $tuotteet_lista ), 
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

