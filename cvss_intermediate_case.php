<?php
$totalCVE = 0;
// this row is new you must calculate that and insert it into the database.
// ----(part 6)------------------------------------------------------------------------ CVE Result ---------------------------------
$splitUA = explode(" ", $useragent);
$key1CVE = str_replace(" ", "+", $colvals[17]);
$key2CVE = str_replace(" ", "+", $colvals[20]);
$key3CVE = str_replace(" ", "+", $colvals[3]);
// echo "key1: $key1CVE \t<br>key2: $key2CVE \t<br>key3: $key3CVE<br>";
$result = 0;
$page1 = file_get_contents("https://services.nvd.nist.gov/rest/json/cves/1.0?keyword=" . $key1CVE . "+" . $key2CVE . "&" . $key3CVE);
$data1 = (json_decode($page1, true));
foreach ($data1 as $key => $value) {
    if ($key == "totalResults") {
        $result = $value;
    }
}
if ($result > 0) {
    $page = file_get_contents("https://services.nvd.nist.gov/rest/json/cves/1.0?resultsPerPage=" . $result . "&keyword=" . $key1CVE . "+" . $key2CVE . "&" . $key3CVE);
    $data = (json_decode($page, true));
    #----------------------------
    $totalResults = 0;
    $exploitabilityScore = array();
    $impactScore = array();
    $description = array();
    $confidentialityImpact = array();
    $integrityImpact = array();
    $availabilityImpact = array();
    $accessVector = array();
    $accessComplexity = array();
    $authentication = array();
    $privilegesRequired = array();
    $userInteraction = array();
    $baseScore = array();
    $baseSeverity = array();
    foreach ($data as $key1 => $value1) {
        if (is_array($value1)) {
            foreach ($value1 as $key2 => $value2) {
                if (is_array($value2)) {
                    foreach ($value2 as $key3 => $value3) {
                        if (is_array($value3)) {
                            foreach ($value3 as $key4 => $value4) {
                                if (is_array($value4)) {
                                    foreach ($value4 as $key5 => $value5) {
                                        if (is_array($value5)) {
                                            foreach ($value5 as $key6 => $value6) {
                                                if (is_array($value6)) {
                                                    foreach ($value6 as $key7 => $value7) {
                                                        if (is_array($value7)) {
                                                            foreach ($value7 as $key8 => $value8) {
                                                                if (is_array($value8)) {
                                                                    foreach ($value8 as $key9 => $value9) {
                                                                        if (is_array($value9)) {
                                                                            foreach ($value9 as $key10 => $value10) {
                                                                                if (is_array($value10)) {
                                                                                    foreach ($value10 as $key11 => $value11) {
                                                                                        if (is_array($value11)) {
                                                                                            foreach ($value11 as $key12 => $value12) {
                                                                                                print("sssssssssssss");
                                                                                            }
                                                                                        } else {
                                                                                            // echo $key11 . " : " . $value11 . "<br><hr>";
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    // echo $key10 . " : " . $value10 . "<br><hr>";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            // echo $key9 . " : " . $value9 . "<br><hr>";
                                                                        }
                                                                    }
                                                                } else {
                                                                    // echo $key8 . " : " . $value8 . "<br><hr>";
                                                                }
                                                            }
                                                        } else {
                                                            // echo $key7 . " : " . $value7 . "<br><hr>";
                                                            if ($key7 == "baseScore") {
                                                                array_push($baseScore, $value7);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    // echo $key6 . " : " . $value6 . "<br><hr>";
                                                }
                                            }
                                        } else {
                                            // echo $key5 . " : " . $value5 . "<br><hr>";
                                        }
                                    }
                                } else {
                                    // echo $key4 . " : " . $value4 . "<br><hr>";
                                }
                            }
                        } else {
                            // echo $key3 . " : " . $value3 . "<br><hr>";
                        }
                    }
                } else {
                    // echo $key2 . " : " . $value2 . "<br><hr>";
                }
            }
        } else {
            // echo $key1 . " : " . $value1 . "<br><hr>";
        }
    }
    if (sizeof($baseScore) >= 1) {
        $totalCVE = round(array_sum($baseScore) / sizeof($baseScore), 1);
        // echo "Total Score from CVSS(v2+v3): " . $totalCVE;
    } else {
        $totalCVE = 0;
    }
} 
// else {
//     $totalCVE = -1;
//     // echo "<br>No Result...<br>";
// }
// ------------------------------------------------------------------------------------ End CVE Result -----------------------------
$time_cvss_score = date("Y-m-d h:m:s");
$sqlupdateCVSS = "UPDATE agentstrings SET cvss_score=" . $totalCVE . ", time_cvss_score='" . $time_cvss_score . "' WHERE browser LIKE '" . $colvals[17] .
    "' AND version =" . $colvals[20] . " AND platform LIKE '" . $colvals[3] . "'";
$conn->query($sqlupdateCVSS);
