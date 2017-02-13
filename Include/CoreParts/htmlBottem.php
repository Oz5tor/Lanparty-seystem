 <?php
      if($page == 'Admin'){
        ?>
    <script src="JS/Bootstrap/bootstrap-datetimepicker.js"></script>
    <script src="JS/Bootstrap/bootstrap-datetimepicker.da.js"></script>
     <script type="text/javascript">
      $('.form_datetime').datetimepicker({
          language:  'da',
          weekStart: 1,
          todayBtn:  1,
          autoclose: 1,
          todayHighlight: 1,
          startView: 2,
          forceParse: 0,
          showMeridian: 0
      });
    </script>
      <?php
      }
    ?>