<style>
td{
	vertical-align: top !important;
}
</style>

        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10">Netvisor tarkistus lista</h2>
        
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
								<!-- Autocomplete -->
								<?php
					   			$site = Yii::app()->createController('Site');
								$mod = 'Asiakkaat';
								$sarake = 'yrityksen_nimi';
								$placeholder = 'Asiakas';
								if(isset($_GET[$sarake])) 			
									$postvalue = $_GET[$sarake]; 
								else 
									$postvalue = '';				
						 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
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
                          <label class="field select">
							   <select class="gui-input" name="valiko" id="valiko">
					   				<option value=""><?php echo Yii::t('main', 'Kaikki'); ?></option>
					   				<option value="problems" <?php echo (isset($_GET['valiko']) and $_GET['valiko'] == 'problems')? 'selected':''; ?>><?php echo Yii::t('main', 'Näytä lista jossa on ongelma Netvisor ja Etunti välillä.'); ?></option>
							   </select>
                            <label for="firstname" class="field-icon">
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2 col-sm-offset-6">
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
if(isset($_GET['getAsiakas']))
{
	echo '<h1>Tämä on: getcustomer.nv?id='.$_GET['getAsiakas'].' &nbsp;&nbsp;<a href="netvisor_customerlist">Takaisiin listaan</a></h1>';

	echo '<pre>';
	foreach($data as $arr)
	{
		foreach($arr as $key => $value)
		{
			foreach($value as $nimike => $arvo)
			{
				if(!is_array($arvo))
				{
					echo $nimike.': <b>'.$arvo.'</b><br>';

				} else {
					echo '<h3>'.$nimike.'</h3>';
					foreach($arvo as $k => $v)
					{
						echo '<p>'.$v.'<p>';
					}
				}
			}
		}
	}
	echo '</pre>';
				
} else {

	$criteria=new CDbCriteria;
	$criteria->select = "id,netvisorkey,yrityksen_nimi,y_tunnus,etunimi,sukunimi";
	$criteria->condition = " 
		netvisorkey>0
	";
	if(isset($_GET['yrityksen_nimi']) and !empty(trim($_GET['yrityksen_nimi']))){
        	$criteria->addCondition (" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%' ");
	}
	$asiakkaat 	= Asiakkaat::model()->findAll($criteria);
	$a_all		= [];
	foreach($asiakkaat as $item)
		$a_all[$item->netvisorkey] = $item;

	echo '<h1>Tämä on: customerlist.nv</h1>';
	echo '<table class="table table-bordered">';
	echo '<tr><th style="width:50%"><h1>Netvisor tiedot</h1></th><th style="width:50%"><h1>Etunti tiedot</h1></th></tr>';
	foreach($data as $arr)
	{
		foreach($arr as $key => $value)
		{
			if(isset($_GET['valiko']) and $_GET['valiko'] == 'problems' and isset($value['Netvisorkey']) and isset($a_all[$value['Netvisorkey']]))
			continue;

			if(isset($_GET['yrityksen_nimi']) and !empty(trim($_GET['yrityksen_nimi'])) and isset($value['Netvisorkey']))
			{
				if(!isset($a_all[$value['Netvisorkey']]))
					continue;
			}

			echo '<tr>';
			echo '<td style="width:50%">';
			foreach($value as $nimike => $arvo)
			{
				if(!is_array($arvo))
				{
					if($nimike == 'Uri')
					{
						$expl = explode("=", $arvo);
						if(isset($expl[1]))
							echo '<h3><a href="netvisor_customerlist?getAsiakas='.$expl[1].'">Lisää tietoja Netvisorista</a><h3>';

					} else {
						echo $nimike.': <b>'.$arvo.'</b><br>';
					}

				} else {
					echo '<h3>'.$nimike.'</h3>';
					foreach($arvo as $k => $v)
					{
						echo '<p>'.$v.'<p>';
					}
				}
			}
			echo '</td>';
			echo '<td style="width:50%">';
			if(isset($value['Netvisorkey']) and isset($a_all[$value['Netvisorkey']]))
			{
				foreach($a_all[$value['Netvisorkey']] as $ka => $va)
				{
					if(!empty($va))
						echo $ka.': <b>'.$va.'</b><br>';
				}
			} else {
				echo '<h2 class="text-danger">Ei löydy</h2>';
			}
			echo '</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
}
?>
