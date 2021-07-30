<?php
// 1) take the total number of rows in agentstrings table
// 2) calculate new attribute score (attribute table) depends on total rows in agentstrings table
// 3) calculate new_privacy_score on (agentstrings table) (update rows)
// 4) this formula must run in loop, run before every add new row
// ----(part 1)------------------------------------------------------------ Connection with DataBase -------------------------
ini_set('memory_limit', '9216M');
require "connectionDB.php";
// ---------------------------------------------------------------------------------
// -------- get total of rows in our database ---
$sqlAgent = "SELECT * FROM agentstrings";
$resultAgent = $conn->query($sqlAgent);
$totalRows = 0;
if ($resultAgent->num_rows > 0) {
    $totalRows = mysqli_num_rows($resultAgent);
}
// echo "Total Rows : " . $totalRows;
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
// calculate Sj :
for ($i = 0; $i < 47; $i++) {
    $Sj[$i] = 1 / $Unique[$i];
}
//-------------------------------------------------
// calculate Vi :
for ($i = 0; $i < 47; $i++) {
    $Vj[$i] = $Visibility_Constant[$i];
}
//-------------------------------------------------
// calculate SofI :
for ($i = 0; $i < 47; $i++) {
    $SofJ[$i] = (($totalRows - abs(($totalRows - $Null[$i]))) / $totalRows);
    // $SofJ[$i] = (($totalRows - ($totalRows - $Null[$i])) / $totalRows);
}
//-------------------------------------------------
// calculate RoverJ :
for ($i = 0; $i < 47; $i++) {
    $RoverJ[$i] = $totalRows - $Null[$i];
}
//-------------------------------------------------
// calculate RoverJ :
for ($i = 0; $i < 47; $i++) {
    $RoverJmodN[$i] = $RoverJ[$i] / $totalRows;
}
//-----------------------------------------------------------------------------------
// insert into database:
$sqldel = "DELETE FROM attributes WHERE 1";
if ($conn->query($sqldel) === TRUE) {
    for ($i = 0; $i < 47; $i++) {
        $sql = "INSERT INTO attributes()
VALUES (" . ($i + 1) . ",'" . $Item[$i] . "'," . $Visibility_Constant[$i] . "," . $Null[$i] . "," . $Unique[$i] . "," . $Sj[$i] . "," . $Vj[$i] . "," . $SofJ[$i] . "," . $RoverJmodN[$i] . ")";
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
// echo "<hr>";
$time_privacy_score = date("Y-m-d h:m:s");
$MAX_MIN = "SELECT MIN(new_privacy_score) as a ,MAX(new_privacy_score) as b FROM agentstrings";
$result_MAX_MIN = $conn->query($MAX_MIN);
$min = 0;
$max = 0;
if ($result_MAX_MIN->num_rows > 0) {
    while ($result_M = $result_MAX_MIN->fetch_assoc()) {
        $min = $result_M['a'];
        $max = $result_M['b'];
    }
}
if ($resultAgent->num_rows > 0) {
    while ($rowAS = $resultAgent->fetch_assoc()) {
        $count = 0;
        $attributes = "SELECT * FROM attributes";
        $result_att = $conn->query($attributes);
        $key = array_keys($rowAS);
        if ($result_att->num_rows > 0) {
            $score = 0;
            while ($rowatt = $result_att->fetch_assoc()) {
                $count++;
                if ($rowAS[$key[$count]] == -1 || $rowAS[$key[$count]] == "unknown") {
                    $score += 0;
                } else {
                    $score += (($rowatt["sofj"] + $rowatt["sj"]) * (($rowatt["rjovern"] + ($rowAS['privacy_score'] / 47)) + $rowatt["vj"]));
                }
            }
        }
        if ($min > $score) {
            $min = $score;
        } else if ($max < $score) {
            $max = $score;
        }
        $score = round(((($score - $min) / ($max - $min)) * 10), 2);
        $sqlupdate = "UPDATE agentstrings SET new_privacy_score=" . $score . ", time_privacy_score='" . $time_privacy_score . "' WHERE browserid=" . $rowAS['browserid'];
        $conn->query($sqlupdate);
    }
}