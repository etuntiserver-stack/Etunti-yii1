<?php ?>

    <!-- layout-->
    <div id="layout" class="layout-wide">

        <!-- Header-->
        <header>
            <!-- Container-->
            <div class="container">
                <!-- Row-->
                <div class="row">
                    <!-- Logo-->
                    <div class="col-md-3">
                        <div class="logo">
                            <!--<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php" title="Return Home">-->

	<!-- Firman oma logo -->
	<?php if (isset(Yii::app()->user->domain)) : ?>
  	<?php $site = Yii::app()->createController('Site'); echo $site[0]->logoShower(50); ?>
	<?php endif; ?>
	<!-- Firman oma logo -->



                            <!--</a>-->
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 time-remaining" id="countTimer"></div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->




