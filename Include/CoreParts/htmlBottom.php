
<script src="JS/Bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="JS/moment/moment.js"></script>
<script type="text/javascript" src="JS/Bootstrap/tempusdominus-bootstrap-4.js"></script>
<script type="text/javascript">
    
 $(function() {
    $('.datetimepicker1').datetimepicker({
        locale: 'da',
        format: 'DD/MM/YYYY HH:mm',
        buttons: {showClose: true}
    });
   });
   $(function() {
    $('.datetimepicker2').datetimepicker({
        locale: 'da',
        format: 'D/M/YYYY HH:mm:ss',
        buttons: {showClose: true}
    });
   });
    $(function() {
    $('.birthday').datetimepicker({
        locale: 'da',
        format: 'DD-MM-YYYY',
        ignoreReadonly: true,
        buttons: {showClose: true}
    });
   });

</script>
<!-- ==================== -->
