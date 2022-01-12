<?php
/* @var $this KohteetController */
/* @var $keys array of Avaimet objects, extracted from shifts, mapped by their ID */
/* @var $shiftNeedMap array of Avaimet objects, mapped by employee ID, that 
the employee will need during the defined time period */
/* @var $from date string in Y-m-d format*/
/* @var $to date string in Y-m-d format */
/* @var $showTransferOnly 1 or 0 */
/* @var $showClientKeys 1 or 0 */
/* @var $selectedWorkgroups array of selected work group IDs */
/* @var $selectedEmployees array of selected employee IDs */
/* @var $employees Array of Tyontekijat objects, mapped by their ID */
/* @var $employeeKeys array of Avaimet objects, mapped by employee ID, that
the employee has right now */
/* @var $queryEmployeeIds an array of Tyontekijat IDs that matched the query
parameters (selected employees and employees belonging to 1 or more selected work groups)*/

?>

<div class="tray-center">
    <h2 class="myBgColors p10">
        <i class="glyphicon glyphicon-envelope"></i>
        <?= Yii::t("main", "AVAIMET"); ?>
    </h2>

    <form id="keyForm" action="#" class="form-inline" method="GET">
        <div class="admin-form">
            <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="section">
                                <label><?= Yii::t("main", "Aikaväli");?></label>
                                <label class="field prepend-icon">
                                    <input type="text" name="from" class="gui-input datepickerFI"
                                        value="<?=date('d.m.Y', strtotime($from));?>">
                                    <label class="field-icon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </label>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="section">
                                <label></label>
                                <label class="field prepend-icon">
                                    <input type="text" name="to" class="gui-input datepickerFI"
                                        value="<?=date('d.m.Y', strtotime($to));?>">
                                    <label class="field-icon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="section">
                                <label class="field"><?= Yii::t("main", "Työntekijä(t)");?></label>
                                <?php
                                    $crit = new CDbCriteria();
                                    $crit->addCondition("aktiivinen = 1");
                                    $empList = Tyontekijat::model()->findAll($crit);
                                    $dataEmployees = [];
                                    foreach($empList as $employee) {
                                        $dataEmployees[$employee->id] = $employee->tekijan_nimi . " " . $employee->sukunimi;
                                    }
                                    
                                ?>

                                <?= CHtml::dropDownList("employees", $selectedEmployees, $dataEmployees, 
                                    ["class" => "bootstrap-select2 select2", "multiple" => true,
                                        "style" => "width:100%"]); 
                                ?>
                            </div>

                            <div class="section">
                                <label class="field"><?= Yii::t("main", "Tyoryhmä(t)");?></label>
                                <?php
                                        $crit = new CDbCriteria();
                                        $crit->addCondition("select_type = 'tyoryhma'");
                                        $groups = Valikkoot::model()->findAll($crit);
                                        $groupData = [];
                                        foreach($groups as $group) {
                                            $groupData[$group->id] = $group->value;
                                        }
                                    ?>
                                <?= CHtml::dropDownList("work-groups", $selectedWorkgroups, $groupData, 
                                        ["class" => "bootstrap-select2 select2", "multiple" => "true",
                                            "style" => "width:100%"]);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="show-transfer-only" id="h-show-transfer-only"
                                value="<?= $showTransferOnly ? '1' : '0'?>">
                            <input type="checkbox" <?= $showTransferOnly ? 'checked="checked"' : ''?>
                                id="show-transfer-only">
                            <label for="show-transfer-only">Näytä vain siirrot</label>
                        </div>
                        <div class="col-md-12">
                            <input type="checkbox" <?= $showClientKeys ? 'checked="checked"' : ''?>
                                name="show-client-keys" id="show-client-keys">
                            <label for="show-client-keys">Näytä avaimet jotka ovat asiakkailla siirroissa</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-primary btn-lg btn-block myBgColors"
                                value="<?php echo Yii::t('main', 'Hae'); ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <div class="panel heading-border">
        <div class="panel-body bg-light">
            <table class="table table-striped">
                <?php foreach($queryEmployeeIds as $empId) : ?>
                    <?php 
                        $shiftKeys = $shiftNeedMap[$empId] ?? [];
                    ?>
                    <?php if($showTransferOnly && count($shiftKeys) === 0) continue; ?>
                    <?= $this->renderPartial("_employee_key", [
                        "keys" => $keys,
                        "empId" => $empId,
                        "shiftKeys" => $shiftKeys,
                        "employees" => $employees,
                        // pass in only those keys that this employee might have
                        // the offset ($empId) can be unset.
                        "employeeKeys" => $employeeKeys[$empId] ?? [],
                        "showTransferOnly" => $showTransferOnly
                    ]); ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>


</div>

<script>
$(document).ready(() => {
    // handle show-transfer-only hidden field, which needs to be on by default
    $("#show-transfer-only").change(() => {
        const checked = $("#show-transfer-only").is(":checked");
        if (checked) {
            $("#h-show-transfer-only").val(1);
        } else {
            $("#h-show-transfer-only").val(0);
        }
    });
    // init select2 elements
    $(".select2").select2();
});
</script>