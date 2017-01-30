<?php
if(isset($_GET['id']) && $_GET['id'] != ''){
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
}
?>
<div class="col-lg-7">
  <!-- Instructions - How to make a seatmap. -->
  <h3>Instruktioner</h3>
  <p>Lav et seatmap ved at skrive nedenstående betegnelser i boksen til højre.</p>
  <div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Betegnelser</h3>
  </div>
  <div class="panel-body">
    <ul class="hlpf_admin_seatmap">
      <li><code>a</code> En plads der er tilgængelig / kan købes.</li>
      <li><code>A</code> Arkade. Spille maskiner og andet.</li>
      <li><code>c</code> En plads der kun er til crew / kan ikke købes.</li>
      <li><code>_</code> Fri rum.</li>
      <li><code>k</code> Kiosk / kantine område.</li>
      <li><code>s</code> Scene / Podie.</li>
    </ul>
  </div>
</div>
<p><small>Note: Alle linjer skal have samme længde! Fyld resten af en linje med <code>_</code> hvis det er nødvendigt.</small></p>
</div>
<div class="form-group col-lg-5">
  <h3>Seatmap</h3>
  <textarea id="generate-seat-map" class="hlpf_seatmap-gen" name="generate-seat-map" rows="8" cols="50" autofocus><?php // Keep this tag close to the textarea!
    if ($action == "Edit" AND !empty($SeatmapID)) {
      $result = $db_conn->query("SELECT * FROM Seatmap WHERE SeatmapID = $SeatmapID");
      if (!empty($result)) {
        $row = $result->fetch_assoc();
        $SeatString = $row['SeatString'];
        $correction = 0;
        for ($i=$row['Width']; $i < strlen($SeatString); $i += $row['Width']) {
          $SeatString = substr_replace($SeatString, "\n", $i+$correction, 0);
          $correction += 1;
        }
        echo $SeatString;
        unset($correction, $aSeatString, $result, $row); // Quick garbage colletion...
      }
    }
    ?></textarea>
    <br>
    <button class="btn btn-primary" onclick="clickMe()">Generate</button>
</div>
<!-- Find a way to show seatmap here! -->
<div class="col-lg-12">
  <div id="View-seatmap"></div>
</div>
<script type="text/javascript">
  function clickMe() {
    $('#View-seatmap')
      .load(
        'Include/Admin/Seatmap/showseatmap.php',
        {'generated_seatmap': 'kkk______________________________ kkk______________________________ __c__aaaaaaaaaa_aaaaaaaaaa_______ __c__aaaaaaaaaa_aaaaaaaaaa___AAA_ __c__________________________AAA_ __c__aaaaaaaaaa_aaaaaaaaaa___AAA_ __c__aaaaaaaaaa_aaaaaaaaaa___sss_ __c__________________________sss_ __c__aaaaaaaaaa_aaaaaaaaaa___sss_ __c__aaaaaaaaaa_aaaaaaaaaa___sss_ __c__________________________sss_ _cc__aaaaaaaaaa_aaaaaaaaaa___sss_ _cc__aaaaaaaaaa_aaaaaaaaaa___AAA_ _cc__________________________AAA_ _cc__aaaaaaaaaa_aaaaaaaaaa___AAA_ _cc__aaaaaaaaaa_aaaaaaaaaa_______ _cc______________________________'}
    )
  };
</script>
