<?php
/* @var $this AdministratorsController */
/* @var $dataProvider CActiveDataProvider */

?>


<!-- begin: .tray-center -->
<div class="tray-center">


    <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Käyttäjät'); ?>
        <?php echo CHtml::link('', Yii::app()->request->baseUrl . '/index.php/administrators/create', array('class' => 'btn btn-default fa fa-plus')); ?>
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

                                    <input type="text" class="gui-input" name="adm_login"
                                        value="<?php if (isset($_POST['adm_login'])) echo $_POST['adm_login']; ?>"
                                        placeholder="Käyttäjätunnus">

                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="section">
                                <label class="field prepend-icon">

                                    <input type="text" name="adm_nimi" class="gui-input"
                                        value="<?php if (isset($_POST['adm_nimi'])) echo $_POST['adm_nimi']; ?>"
                                        placeholder="Nimi">
                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-home"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="section">
                                <label class="field prepend-icon">

                                    <input type="text" name="adm_email" class="gui-input"
                                        value="<?php if (isset($_POST['adm_email'])) echo $_POST['adm_email']; ?>"
                                        placeholder="<?php echo Yii::t('main', 'Sähköposti'); ?>">
                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-at"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-offset-4">
                            <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i
                                    class="glyphicon glyphicon-search"> </i> Hae</button>
                        </div>

                    </div>



                </div>
            </div>
        </div>

    </form>


    <!-- loppu: .tray-center -->
</div>

<?php
  $as = Yii::app()->createController('Asetukset');
  $val = $as[0]->oikeudenOtsikot();
  $roleTitles = [];
  foreach($val as $id => $value) {
    $roleTitles[$id] = $value['nimike'];
  }
			
?>

<div class="panel heading-border">
    <div class="panel-body">

        <table class="table table-striped" id="mobileTable">
            <thead class="myBgColors">
                <tr>
                    <th></th>
                    <th><?php echo Yii::t('main', 'Käyttäjätunnus'); ?></th>
                    <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
                    <th><?php echo Yii::t('main', 'Nimi'); ?></th>
                    <th><?= Yii::t("main", "Käyttöoikeudet") ?></th>
                </tr>
            </thead>
            <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider' => $dataProvider,
              'itemView' => '_view',
              'template' => '{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
              'viewData' => ["roleTitles" => $roleTitles],

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




<script type="text/javascript">
$(document).ready(function() {

    $(".haemob").click(function() {
        $("#mobForm").submit();
    });

});
</script>