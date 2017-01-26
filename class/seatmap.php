<?php
function seatmap_generation($seatgen_SeatString, $seatgen_Width) {
  // Convert the $seatgen_SeatString to an array.
  // Each line must be $seatgen_Width long.
  $seatgen_Seats = str_split($seatgen_SeatString, $seatgen_Width);

  // See https://github.com/mateuszmarkowski/jQuery-Seat-Charts#map
  // for more on seatmaps.

  // Loop for each line in $seatgen_Seats.
  for ($seatgen_i=0; $seatgen_i < count($seatgen_Seats); $seatgen_i++) {
    // Start a new line.
    echo "'";
    // An independent iteration.
    $seatgen_i_iteration = 0;
    // Loop through all charactors in the line.
    for ($seatgen_j=0; $seatgen_j < strlen($seatgen_Seats[$seatgen_i]); $seatgen_j++) {
      // If the current character is an underscore, just echo it.
      if (substr($seatgen_Seats[$seatgen_i], $seatgen_j, 1) == "_") {
        echo substr($seatgen_Seats[$seatgen_i], $seatgen_j, 1);
      // If the current character is a C, give it an unique ID with "C" as it's label.
      } elseif (substr($seatgen_Seats[$seatgen_i], $seatgen_j, 1) == "c") {
        //                               // Unique_ID.         // Label.
        echo substr($seatgen_Seats[$seatgen_i], $seatgen_j, 1) . "[c". ($seatgen_i + 1) . "_" . $seatgen_j . ",c]";
      // If all of the above fails, we assume it's a regular seat.
      // Give it an ID based on current row and column.
      // Give it a Label based on current row and column.
      } else {
        $seatgen_i_iteration += 1;
        //                                // a[ID,LABEL]
        echo substr($seatgen_Seats[$seatgen_i], $seatgen_j, 1) . "[" . ($seatgen_i + 1) . "_" . $seatgen_i_iteration . "," . ($seatgen_i + 1). "_" . $seatgen_i_iteration . "]";
      }
    }
    // End the line.
    echo "',";
  } // End of loop
  // Gabage collection.
  unset($seatgen_SeatString, $seatgen_Seats, $seatgen_Width, $seatgen_i_iteration, $seatgen_i, $seatgen_j);
} // End of function
