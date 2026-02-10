</div>
<!-- END PAGE CONTAINER -->


<!-- START SCRIPTS -->
<!-- START PLUGINS -->
<script type="text/javascript" src="assets/themes/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/themes/js/plugins/bootstrap/bootstrap.min.js"></script>        
<script type="text/javascript" src="assets/themes/js/plugins/bootstrap/bootstrap-timepicker.min.js" ></script>
<!-- END PLUGINS -->

<!-- START THIS PAGE PLUGINS-->      
<script type='text/javascript' src='assets/themes/js/plugins/icheck/icheck.min.js'></script>        
<script type="text/javascript" src="assets/themes/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="assets/themes/js/plugins/scrolltotop/scrolltopcontrol.js"></script>

<script type="text/javascript" src="assets/themes/js/plugins/fileinput/fileinput.min.js"></script>
<!-- END THIS PAGE PLUGINS-->        

<script type="text/javascript" src="assets/themes/js/plugins.js"></script>        
<script type="text/javascript" src="assets/themes/js/actions.js"></script>

<!-- END TEMPLATE -->
<script src="assets/libs/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="assets/libs/datatables/js/dataTables.responsive.js" type="text/javascript"></script>
<script src="assets/libs/datatables/plugins/Api/average().js" type="text/javascript"></script>
<script src="assets/libs/datatables/plugins/Api/sum().js" type="text/javascript"></script>
<script src="assets/libs/jquery.mousewheel.js" type="text/javascript"></script>
<script src="assets/libs/alertify/js/alertify.min.js" type="text/javascript"></script>
<script src="assets/libs/select2/js/select2.min.js" type="text/javascript"></script>
<script src="assets/libs/form-validator/jquery.form-validator.min.js" type="text/javascript"></script>
<script src="assets/libs/form-validator/custom-validation.js" type="text/javascript"></script>
<script src="assets/libs/datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/libs/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="assets/libs/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="assets/libs/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/themes/js/plugins/daterangepicker/daterangepicker.js"></script>
<script src="assets/libs/js.cookie.js" type="text/javascript"></script>
<script src="assets/js/webapp.js" type="text/javascript"></script>

<?php
if (isset($jsfiles) && is_array($jsfiles)) {
    foreach ($jsfiles as $file) {
        echo '<script src="assets/js/modules/' . $modules . '/' . $file . '.js" type="text/javascript"></script>';
    }
}
?>
<!-- END SCRIPTS -->         
</body>
</html>