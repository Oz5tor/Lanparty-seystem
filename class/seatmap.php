<?php
/*
  Author:
    Patrick Rosenheim - @XDRosenheim
  Date: Jan/Feb - 2017
  Nope Copyright 2017

  See https://github.com/mateuszmarkowski/jQuery-Seat-Charts#map for more on seatmaps.

  Description:
    Use this function if:
    You have your map string stored in a single line together with a number that defines
    the length of a row


  Dependecies:
    This function is to be used with @mateuszmarkowski's jQuery-Seat-Charts.

  How and where to use:
    Use this in the "map" section of JSC:
      var sc = $('#generated-seat-map').seatCharts({
        map:[INSERT ME HERE.]
      })

  @return void
*/
function seatmap_generation($seatString, $width) {
  // This array contains the character which should not get an ID or a label.
  // Label will be '_' (Underscore).
  // Just set the colour to the same as the background-colour.
  $dontIdMe = ["_", "k", "s", "A"];
  // Convert the $seatString to an array.
  // Each line must be $width long.
  $seats = str_split($seatString, $width);
  // Independent iterations.
  $i_iteration = 1;
  $i_crewiteration = 1;
  // Loop for each line in $seats.
  for ($i=0; $i < count($seats); $i++) {
    // Start a new line.
    echo "'";
    // Loop through all charactors in the line.
    for ($j=0; $j < strlen($seats[$i]); $j++) {
      // If the current character is found in the array $dontIdMe, we just echo them.
      if ( in_array(substr($seats[$i], $j, 1), $dontIdMe) ) {
        echo substr($seats[$i], $j, 1) . "[,_]";
      // If the current character is a C, give it an unique ID with "C" as it's label.
      } elseif (substr($seats[$i], $j, 1) == "c") {
        //  -->>                      // c[cID,cLABEL]
        echo substr($seats[$i], $j, 1) . "[c". sprintf("%'.02d", $i_crewiteration) .
                    ",c" . sprintf("%'.02d", $i_crewiteration) . "]";
        $i_crewiteration += 1;
      // If all of the above fails, we assume it's a regular seat.
      // Give it an ID based on the current i_iteration.
      // Give it a Label based on the current i_iteration.
      } else {
        //  -->>                      // a[ID,LABEL]
        echo substr($seats[$i], $j, 1) . "[" . sprintf("%'.03d", $i_iteration) .
                    "," . sprintf("%'.03d", $i_iteration) . "]";
        $i_iteration += 1;
      }
    }
    // End the line.
    echo "',";
  } // End of loop
  // Gabage collection.
  unset($seatString, $seats, $width, $i_iteration, $i, $j, $i_crewiteration);
} // End of function
