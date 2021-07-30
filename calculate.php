<?php
$final_score = 0;
$checkRow = "SELECT * FROM agentstrings 
WHERE parent like'" . $colvals[1] . "' AND browser_bits=" . $colvals[2] . " AND platform LIKE '" . $colvals[3] .
    "' AND platform_description LIKE '" . $colvals[4] . "' AND platform_bits =" . $colvals[5] .
    " AND platform_maker LIKE '" . $colvals[6] . "' AND new_privacy_score != 0 AND checkCVSS != 0";
$resultCheckRow = $conn->query($checkRow); // best case
// ----------
$checkRow2 = "SELECT * FROM agentstrings 
WHERE parent like'" . $colvals[1] . "' AND browser_bits=" . $colvals[2] . " AND platform LIKE '" . $colvals[3] .
    "' AND platform_description LIKE '" . $colvals[4] . "' AND platform_bits =" . $colvals[5] .
    " AND platform_maker LIKE '" . $colvals[6] . "' AND new_privacy_score != 0 AND checkCVSS = 0";
$resultCheckRow2 = $conn->query($checkRow2); // intermediate case 1
// ----------
$checkRow3 = "SELECT * FROM agentstrings 
WHERE parent like'" . $colvals[1] . "' AND browser_bits=" . $colvals[2] . " AND platform LIKE '" . $colvals[3] .
    "' AND platform_description LIKE '" . $colvals[4] . "' AND platform_bits =" . $colvals[5] .
    " AND platform_maker LIKE '" . $colvals[6] . "' AND new_privacy_score = 0 AND checkCVSS != 0";
$resultCheckRow3 = $conn->query($checkRow3); // intermediate case 2
// -----------------------------------------------------------------------------------
if ($resultCheckRow->num_rows > 0) { // this row is founded in the database
    if ($rowCheck = $resultCheckRow->fetch_assoc()) { // best case
        $final_score = ($rowCheck['new_privacy_score'] + $rowCheck['cvss_score']);
        echo "<h3>Relative Score For Your Browser: " . $rowCheck['new_privacy_score'] . "</h3>";
        echo "<h3>CVSS Score For Your Browser: " . $rowCheck['cvss_score'] . "</h3>";
        echo "<h3>" . "Final Score For Your Browser: <span id='scoreColor'>" . $final_score . "</span></h3>";
        echo "<h5>Last Update In: " . $rowCheck['time_privacy_score'] . "</h5>";
        $new_date = date("Y-m-d h:m:s");
        $sqlLastSeen = "UPDATE agentstrings SET last_seen ='" . $new_date . "' WHERE browserid =" . $rowCheck['browserid'];
        $conn->query($sqlLastSeen);
    }
} else if ($resultCheckRow2->num_rows > 0) { // intermediate case 1 cvss_score is zero
    require "cvss_intermediate_case.php";
    $resultCheckRow = $conn->query($checkRow);
    if ($resultCheckRow->num_rows > 0) {
        if ($rowCheck = $resultCheckRow->fetch_assoc()) {
            $final_score = ($rowCheck['new_privacy_score'] + $rowCheck['cvss_score']);
            echo "<h3>Relative Score For Your Browser: " . $rowCheck['new_privacy_score'] . "</h3>";
            echo "<h3>CVSS Score For Your Browser: " . $rowCheck['cvss_score'] . "</h3>";
            echo "<h3>" . "Final Score For Your Browser: <span id='scoreColor'>" . $final_score . "</span></h3>";
            echo "<h3>" . $rowCheck['time_privacy_score'] . "</h3>";
            $new_date = date("Y-m-d h:m:s");
            $sqlLastSeen = "UPDATE agentstrings SET last_seen ='" . $new_date . "' WHERE browserid =" . $rowCheck['browserid'];
            $conn->query($sqlLastSeen);
        }
    }
} else if ($resultCheckRow3->num_rows > 0) { // intermediate case 2 new_privacy_score is zero
    require "privacy_score_intermediate_case.php";
} else { // worse case
    require "worse_case.php";
}
