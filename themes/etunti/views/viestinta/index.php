<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


<!-- begin: .tray-center -->
<div class="tray-center">


  <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'VIESTIT'); ?>
    <?php echo CHtml::link('', Yii::app()->request->baseUrl . '/index.php/viestinta/create', array('class' => 'btn btn-default fa fa-plus')); ?></h2>



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

                  <input type="text" class="gui-input datepicker" name="pvm" 
                    value="<?php if (isset($_POST['pvm'])) echo $_POST['pvm']; ?>" 
                    placeholder="<?php echo Yii::t('main', 'Päivämäärä'); ?>...">

                  <label for="firstname" class="field-icon">
                    <i class="glyphicon glyphicon-calendar"></i>
                  </label>
                </label>
              </div>
            </div>

            <div class="col-md-2">
              <div class="section">
                <label class="field prepend-icon">

                  <input type="text" name="id" class="gui-input" 
                    value="<?php if (isset($_POST['id'])) echo $_POST['id']; ?>" 
                    placeholder="<?php echo Yii::t('main', 'Keskustelu nro..'); ?>..">
                  <label for="firstname" class="field-icon">
                    <i class="fa fa-file-text-o"></i>
                  </label>
                </label>
              </div>
            </div>

            <div class="col-md-6">
              <div class="section">
                <label class="field prepend-icon">

                  <input type="text" name="sisalto" class="gui-input" 
                    value="<?php if (isset($_POST['sisalto'])) echo $_POST['sisalto']; ?>" 
                    placeholder="<?php echo Yii::t('main', 'Viestin sisältö'); ?>..">
                  <label for="firstname" class="field-icon">
                    <i class="fa fa-file-text-o"></i>
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



<?php
$criteria = new CDbCriteria();
$criteria->condition = " status=3 ";
$vi = Viestinta::model()->findAll($criteria);

/**
 * from what I gather the sivex_viestinta table works like this:
 * you can find the workers id from either "tekija" column or
 * "admin" column, before the first "," character, appended
 * with tt_.
 * if a person from the office replies to a message,
 * the ID will be in the "admin" column, but if they haven't
 * replied, it should be found in the "tekija" column.
 * and if a person from the office has replied to a message
 * "tekija" column will be office.
 * 
 * so first we'll look for workers ID in the "tekija" column, if
 * it's a number or numeric string, we can use that to find
 * the worker. if not, we'll attempt to parse the ID
 * from the "admin" column.
 * 
 * if worker was not found by either method, we'll
 * return an empty string.
 */
function printWorkerLocation($workerId, $message) {
  // check if $workerId is a number of numeric string
  if(is_numeric($workerId)) {
    // query worker
    $worker = Tyontekijat::model()->findByPk($workerId);
    if($worker) {
      // if worker found, return "tyoryhma"
      return locationString($worker);
    }
  } else {
    // look for worker id in "admin" column
    $adminCol = $message->admin;
    // check if tt_ and "," exists in the string, which should be the
    // case if $workerId was not numeric (it's probably "toimisto")
    if(strpos($adminCol, "tt_") !== false and strpos($adminCol, ",") !== false) {
      // split the string, which should look like this:
      // tt_123,Some name here
      $split = explode(",", $adminCol);
      $workerString = $split[0];
      // make sure we have the tt_ string
      if(strpos($workerString, "tt_") !== false) {
        $splitWorkerString = explode("tt_", $workerString);
        // ID should be in index 1
        if(isset($splitWorkerString[1]) and is_numeric($splitWorkerString[1])) {
          $parsedId = $splitWorkerString[1];
          $worker = Tyontekijat::model()->findByPk($parsedId);
          return locationString($worker);
        }
      }
    }
  }
  return "";
}

/**
 * Returns a comma separated string that includes
 * workers "tyo_toimialue" and "tyoryhma" columns.
 */
function locationString(Tyontekijat $worker) {
  $str = "";
  // parse tyo_toimialue
  $territories = json_decode($worker->tyo_toimialue, true);
  if($territories) {
    foreach($territories as $territory) {
      $str .= "$territory, ";
    }
  }
  // parse tyoryhma
  $workgroups = json_decode($worker->tyoryhma, true);
  if($workgroups) {
    foreach($workgroups as $workgroup) {
      $str .= "$workgroup, ";
    }
  }
  $str = trim($str);
  if(strlen($str) > 0) {
    // remove last character, which should be a ','
    $str = substr($str, 0, -1);
  }
  return $str;
}

if (isset($vi[0]->id) and !empty($vi[0]->id)) {
  echo '
    <div class="admin-form">
      <div class="panel heading-border">
      <div class="panel-body">';

      echo '<h2>' . Yii::t('main', 'Vastaamattomat viestit') . '</h2>';
      echo '<div class="row">';

      foreach ($vi as $v) {

        echo '<div class="col-sm-4" id="v_' . $v->id . '">';
        echo '<div class="well">';
        echo '<p>'.printWorkerLocation($v->tekija, $v).'</p>';
        echo '<span>' . str_replace("\n", "<br>", $v->viesti) . '</span> .

        <div class="row">
        <div class="pull-right">
          ' . CHtml::link("Vastaa", Yii::app()->request->baseUrl . '/index.php/viestinta/update?id=' . $v->id, array('class' => 'btn btn-xs btn-primary')) . '
          <div class="btn btn-xs btn-default vastaanotettu" for="v_' . $v->id . '">' . Yii::t('main', 'Sulje') . '</div>
        </div>
        </div>';
        echo '</div>';
        echo '</div>';
      }
      echo '</div>
      </div>
      </div>
    </div>
  ';
}
?>

<div class="admin-form">
  <div class="panel heading-border">
    <div class="panel-body">

      <div class="row">
        <div class="table-responsive">
          <table class="table table-striped" id="mobileTable">
            <thead class="myBgColors">
              <tr>
                <th></th>
                <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
                <th><?php echo Yii::t('main', 'Viesti'); ?></th>
                <th><?php echo Yii::t('main', 'Lähettäjä'); ?></th>
                <th><?php echo Yii::t('main', 'Vastaanottaja'); ?></th>
              </tr>
            </thead>
            <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider' => $dataProvider,
              'itemView' => '_view',
              'template' => '{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


              'pager' => array(
                'firstPageLabel' => '<<',
                'prevPageLabel' => '< Edellinen',
                'nextPageLabel' => 'Seuraava >',
                'lastPageLabel' => '>>',
                //'maxButtonCount'=>'10',
                'header' => '<h3>Siirry sivulle:</h3>',
                'cssFile' => false,
              ),

            )); ?>
          </table>
        </div>
      </div>


    </div>
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function() {

    $(".haemob").click(function() {
      $("#mobForm").submit();
    });


    $(document).delegate(".vastaanotettu", "click", function() {

      var thisVid = $(this).attr("for");
      var id = $(this).attr("for").split("_");

      $.ajax({
        url: location.protocol + "//" + location.host + '/index.php/viestinta/vastaanotettu?id=' + id[1],
        success: function(data) {
          console.log(data);
          $("#" + thisVid).hide('slow');
        }
      });

    });

  });
</script>