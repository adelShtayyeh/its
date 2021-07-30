<?php
$totalCVE = 0;
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
$num_of_vuln = $result;
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
    } else {
        $totalCVE = 0;
    }
}
// else {
//     $totalCVE = -1;
//     // echo "<br>No Result...<br>";
// }
// ------------------------------------------------------------------------------------ End CVE Result -----------------------------
// -------- get total of rows in our database ---
$sqlAgent = "SELECT * FROM agentstrings";
$resultAgent = $conn->query($sqlAgent);
$totalRows = 0;
if ($resultAgent->num_rows > 0) {
    $totalRows = mysqli_num_rows($resultAgent);
}
// ---------- define the constants our needed ---
// attribute_name
$Item = array(
    'renderingengine_name', 'renderingengine_version', 'renderingengine_description', 'renderingengine_maker', 'comment', 'browser', 'browser_type', 'browser_maker', 'version', 'majorver', 'minorver', 'parent', 'browser_bits', 'frames', 'iframes', 'tables', 'cookies', 'javascript', 'cssversion', 'browser_modus', 'alpha', 'beta', 'win16', 'win32', 'win64', 'backgroundsounds', 'vbscript', 'activexcontrols', 'issyndicationreader', 'crawler', 'isfake', 'isanonymized', 'ismodified', 'javaapplets', 'platform', 'platform_description', 'platform_bits', 'platform_version', 'platform_maker', 'device_name', 'device_code_name', 'device_maker', 'device_brand_name', 'ismobiledevice', 'device_type', 'device_pointing_method', 'istablet'
);

// visibility_const
$Visibility_Constant = array(
    1, 1, 1, 1, 0.5, 0.33, 1, 0.25, 0.33, 0.5, 0.5, 0.5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0.33, 0.33, 0.33, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0.33, 0.33, 1, 1, 1, 0.5, 0.5, 0.5, 0.5, 0.33, 0.33, 0.33, 1
);
// num_nulls
$Null = array(
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 70133, 70133, 70133, 70133, 70133, 0, 0, 1001404, 992225, 1001531, 661563, 661563, 669403, 669403, 669403, 1001327, 988295, 1001565, 1000694, 999103, 474825, 0, 0, 0, 0, 0, 0, 0, 0, 0, 599392, 0, 0, 942524
);
// num_uniques
$Unique = array(
    16, 116, 15, 11, 3735, 1385, 9, 581, 1189, 118, 166, 4161, 4, 2, 2, 2, 2, 2, 3, 15, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 99, 87, 4, 87, 34, 2304, 2457, 189, 192, 2, 9, 7, 2
);
$Sj = array_fill(0, 47, 0.0); // SJ = 1/Unique
$SofJ = array_fill(0, 47, 0.0); // SofJ =((totalRows-abs((totalRows-Null)))/totalRows)
$Vj = array_fill(0, 47, 0.0); // Vj = Visibility_Constant
$RoverJ = array_fill(0, 47, 0.0); // RoverJ = totalRows-Null
$RoverJmodN = array_fill(0, 47, 0.0); // RoverJmodN = RoverJ/totalRows
//-----------------------------------------------------------------------------------
$totalRows = $totalRows + 1;
// calculate Sj :
for ($i = 0; $i < 47; $i++) {
    $Sj[$i] = (1 / $Unique[$i]);
}
//-------------------------------------------------
// calculate Vi :
for ($i = 0; $i < 47; $i++) {
    $Vj[$i] = $Visibility_Constant[$i];
}
//-------------------------------------------------
// calculate SofI :
for ($i = 0; $i < 47; $i++) {
    // $SofJ[$i] = (($totalRows - ($totalRows - $Null[$i])) / $totalRows);
    $SofJ[$i] = (($totalRows - abs(($totalRows - $Null[$i]))) / $totalRows);

}
//-------------------------------------------------
// calculate RoverJ :
for ($i = 0; $i < 47; $i++) {
    $RoverJ[$i] = ($totalRows - $Null[$i]);
}
//-------------------------------------------------
// calculate RoverJmodN :
for ($i = 0; $i < 47; $i++) {
    $RoverJmodN[$i] = ($RoverJ[$i] / $totalRows);
}
//-----------------------------------------------------------------------------------
// insert into database:
$sqldel = "DELETE FROM attributes WHERE 1";
if ($conn->query($sqldel) === TRUE) {
    for ($i = 0; $i < 47; $i++) {
        $sql = "INSERT INTO attributes()
        VALUES (" . ($i + 1) . ",'" . $Item[$i] . "'," . $Visibility_Constant[$i] . "," . $Null[$i] . "," . $Unique[$i] . "," .
            $Sj[$i] . "," . $Vj[$i] . "," . $SofJ[$i] . "," . $RoverJmodN[$i] . ")";
        // echo $sql . "<br>";
        if ($conn->query($sql) === TRUE) {
            // echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error deleting record: " . $conn->error;
}
//-----------------------------------------------------------------------------------
$attributes = "SELECT * FROM attributes";
$result_att = $conn->query($attributes);
$key = array_keys($colvals);
$score = 0;
if ($result_att->num_rows > 0) {
    $count = 0;
    while ($rowatt = $result_att->fetch_assoc()) {
        if ($colvals[$count] == -1 || $colvals[$count] == "unknown") {
            $score += 0;
        } else {
            $score += (($rowatt["sofj"] + $rowatt["sj"]) * (($rowatt["rjovern"] + ($colvals[48] / 47)) + $rowatt["vj"]));
        }
        $count++;
    }
}
// $score = abs($score);
// echo "Score : $score <br>";

$MAX_MIN = "SELECT MIN(new_privacy_score) as a ,MAX(new_privacy_score) as b FROM agentstrings";
$result_MAX_MIN = $conn->query($MAX_MIN);
$min = 0;
$max = $score;
// if ($result_MAX_MIN->num_rows > 0) {
//     while ($result_M = $result_MAX_MIN->fetch_assoc()) {
//         $score = round($score, 2);
//         $min = $result_M['a'];
//         $max = $result_M['b'];
//         if ($min > $score) {
//             $min = $score;
//         } else {
//             $max = $score;
//         }
//     }
// }
$score = round(((($score - $min) / ($max - $min)) * 10), 2);
$colvals[49] = $score;

$tdate = date("Y-m-d h:m:s");
echo "<h3>Relative Score For Your Browser: " . $colvals[49] . "</h3>";
echo "<h3>CVSS Score For Your Browser: " . $totalCVE . "</h3>";
$final_score = (round(($colvals[49] + $totalCVE), 2));
echo "<h3>Final Score For Your Browser is: <span id='scoreColor'>" . $final_score . "</span></h3>";
// echo "<h3>Updated: " . $tdate . "</h3>";

$sqlNewRow = "INSERT into agentstrings(parent, browser_bits, platform, platform_description, platform_bits, platform_maker, 
    javaapplets, device_name, device_maker, device_code_name, device_brand_name, renderingengine_name, renderingengine_version, 
    renderingengine_description, renderingengine_maker, comments, browser, browser_type, browser_maker, `version`, majorver, frames, 
    iframes, tabless, cookies, javascript, ismobiledevice, cssversion, device_type, device_pointing_method, browser_modus, minorver, 
    platform_version, alpha, beta, win16, win32, win64, backgroundsounds, vbscript, activexcontrols, istablet, issyndicationreader, 
    crawler, isfake, isanonymized, ismodified, privacy_score, new_privacy_score,cvss_score,checkCVSS,time_privacy_score,time_cvss_score,last_seen,num_of_vuln) 
    values('$colvals[1]','$colvals[2]','$colvals[3]','$colvals[4]','$colvals[5]','$colvals[6]','$colvals[7]','$colvals[8]','$colvals[9]',
    '$colvals[10]','$colvals[11]', '$colvals[12]','$colvals[13]','$colvals[14]','$colvals[15]','$colvals[16]','$colvals[17]','$colvals[18]',
    '$colvals[19]','$colvals[20]','$colvals[21]', '$colvals[22]','$colvals[23]','$colvals[24]','$colvals[25]','$colvals[26]','$colvals[27]',
    '$colvals[28]','$colvals[29]','$colvals[30]','$colvals[31]', '$colvals[32]','$colvals[33]','$colvals[34]','$colvals[35]','$colvals[36]',
    '$colvals[37]','$colvals[38]','$colvals[39]','$colvals[40]','$colvals[41]', '$colvals[42]','$colvals[43]','$colvals[44]','$colvals[45]',
    '$colvals[46]','$colvals[47]','$colvals[48]','$colvals[49]',$totalCVE,1,'$tdate','$tdate','$tdate','$num_of_vuln')";
$conn->query($sqlNewRow);
