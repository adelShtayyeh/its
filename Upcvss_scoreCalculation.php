<?php
ini_set('memory_limit', '9216M');
// ---- connection
require "connectionDB.php";
//-----------------------------------------------------------------------------------
// $buff_cvss_score = "select DISTINCT * from buff_cvss_score where 1";
// $agentstrings = "select DISTINCT browser,version,platform from agentstrings WHERE checkCVSS = 0";
// $resultAgent = $conn->query($agentstrings);
// $resultABuff = $conn->query($buff_cvss_score);
// $i = 0;
// if ($resultABuff->num_rows > 0) {
//     while ($rowBuff = $resultABuff->fetch_assoc()) {
//         $time_cvss_score =  date("Y-m-d h:m:s");
//         $i++;
//         // $sqlupdate = "UPDATE agentstrings SET cvss_score=" . $rowBuff['cvss_score'] . ",checkCVSS=1 time_cvss_score='" . $time_cvss_score .
//         //     "' WHERE browser LIKE '" . $rowBuff['browser'] . "' AND version LIKE '" . $rowBuff['version'] . "' AND platform LIKE '" .
//         //     $rowBuff['platform'] . "'";
//         $sqlupdate = "UPDATE agentstrings SET cvss_score = " . $rowBuff['cvss_score'] . ", checkCVSS = 1, time_cvss_score = '" . $time_cvss_score .
//             "' WHERE browser LIKE '" . $rowBuff['browser'] . "' AND version =" . $rowBuff['version'] . " AND platform LIKE '" .
//             $rowBuff['platform'] . "'";
//         // echo $sqlupdate . "<br>$i<br>";
//         $conn->query($sqlupdate);
//     }
// }
//-----------------------------------------------------------------------------------
$AS = "SELECT DISTINCT browser,`version`,platform FROM agentstrings WHERE last_seen is not null or checkCVSS = 0";
$result_AS = $conn->query($AS);
$jj = 0;
$time_cvss_score = date("Y/m/d h:m:s");
if ($result_AS->num_rows > 0) {
    while ($rowAS = $result_AS->fetch_assoc()) {
        $jj++;
        $totalCVE = 0;
        $result = 0;
        $key1CVE = str_replace(" ", "+", $rowAS['browser']);
        $key2CVE = str_replace(" ", "+", $rowAS['version']);
        $key3CVE = str_replace(" ", "+", $rowAS['platform']);
        // echo "key1: $key1CVE \t<br>key2: $key2CVE \t<br>key3: $key3CVE<br>";
        $page1 = file_get_contents("https://services.nvd.nist.gov/rest/json/cves/1.0?keyword=" . $key1CVE . "+" . $key2CVE . "&" . $key3CVE);
        $data1 = (json_decode($page1, true));
        foreach ($data1 as $keyCVE => $value) {
            if ($keyCVE == "totalResults") {
                $result = $value;
            }
        }
        $num_of_vuln = $result;

        // echo "Total of Result: " . $result . "<hr>";
        if ($result > 0) {
            if ($result > 100) $result = 100; // top 100 vulnerability (sorted by publish date)
            $page = file_get_contents("https://services.nvd.nist.gov/rest/json/cves/1.0?resultsPerPage=" . $result . "&keyword=" . $key1CVE . "+" . $key2CVE . "&" . $key3CVE);
            $data = (json_decode($page, true));
            #----------------------------
            $baseScore = array();
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
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                }
                                                                            }
                                                                        } else {
                                                                        }
                                                                    }
                                                                } else {
                                                                    // from here extract (confidentialityImpact),(integrityImpact),(availabilityImpact),(baseScore),(baseSeverity)
                                                                    // echo $key7 . " : " . $value7 . "<br><hr>";
                                                                    if ($key7 == "baseScore") {
                                                                        array_push($baseScore, $value7);
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            // from here extract (exploitabilityScore),(impactScore)
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
                    // from here extract (totalResults)
                }
            }
            if (sizeof($baseScore) >= 1) {
                $totalCVE = round(array_sum($baseScore) / sizeof($baseScore), 1);
            } else {
                $totalCVE = 0;
            }
        }
        // else {
        //     $totalCVE = 0.00000000000000000000000001;
        // }
        // -------- End get score for CVE -----
        $sqlupdate = "UPDATE agentstrings SET cvss_score=" . $totalCVE . ", time_cvss_score='" . $time_cvss_score .
            "', checkCVSS = 1 ,num_of_vuln =" . $num_of_vuln . " WHERE browser LIKE '" . $rowAS['browser'] .
            "' AND version =" . $rowAS['version'] . " AND platform LIKE '" . $rowAS['platform'] . "'";
        // echo "$sqlupdate";
        $conn->query($sqlupdate);
    }
    echo "<br><h4>Was updated ($jj) values is done..</h4>";
} else {
    $sqlupdate = "UPDATE agentstrings SET time_cvss_score='" . $time_cvss_score . "', num_of_vuln = " . $num_of_vuln . " WHERE 1";
    $conn->query($sqlupdate);
    echo "<br><h4>All CVSS score for browsers are up to date</h4>";
}
