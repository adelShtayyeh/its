<?php
require "connectionDB.php";
require "arrays.php";
$getAS = "SELECT DISTINCT `parent`, `browser_bits`, `platform`, `platform_description`, `platform_bits`, `platform_maker`, 
`javaapplets`, `device_name`, `device_maker`, `device_code_name`, `device_brand_name`, `renderingengine_name`, `renderingengine_version`, 
`renderingengine_description`, `renderingengine_maker`, `comments`, `browser`, `browser_type`, `browser_maker`, `version`, `majorver`, 
`frames`, `iframes`, `tabless`, `cookies`, `javascript`, `ismobiledevice`, `cssversion`, `device_type`, `device_pointing_method`, 
`browser_modus`, `minorver`, `platform_version`, `alpha`, `beta`, `win16`, `win32`, `win64`, `backgroundsounds`, `vbscript`, 
`activexcontrols`, `istablet`, `issyndicationreader`, `crawler`, `isfake`, `isanonymized`, `ismodified`, `privacy_score`,
`new_privacy_score`,`cvss_score`,time_privacy_score,time_cvss_score FROM `agentstrings`";

$getNew = "SELECT DISTINCT `parent`, `browser_bits`, `platform`, `platform_description`, `platform_bits`, `platform_maker`, 
`javaapplets`, `device_name`, `device_maker`, `device_code_name`, `device_brand_name`, `renderingengine_name`, `renderingengine_version`, 
`renderingengine_description`, `renderingengine_maker`, `comments`, `browser`, `browser_type`, `browser_maker`, `version`, `majorver`, 
`frames`, `iframes`, `tabless`, `cookies`, `javascript`, `ismobiledevice`, `cssversion`, `device_type`, `device_pointing_method`, 
`browser_modus`, `minorver`, `platform_version`, `alpha`, `beta`, `win16`, `win32`, `win64`, `backgroundsounds`, `vbscript`, 
`activexcontrols`, `istablet`, `issyndicationreader`, `crawler`, `isfake`, `isanonymized`, `ismodified`, `privacy_score`,
`new_privacy_score`,`cvss_score`,`time_privacy_score`,`time_cvss_score` FROM `new`";

// ---------------------------------------------------------------------------
$deletePart1 = "DELETE FROM `new` WHERE 1";
if (mysqli_query($conn, $deletePart1)) {
    echo "delete new is done.. <br>";
} else {
    echo "Error deletePart1 record: " . $conn->error . "<br>";
}
// -------------------------------------------
$insertPart1 = "INSERT into new (parent, browser_bits, platform, platform_description, platform_bits, platform_maker, 
    javaapplets, device_name, device_maker, device_code_name, device_brand_name, renderingengine_name, renderingengine_version, 
    renderingengine_description, renderingengine_maker, comments, browser, browser_type, browser_maker, `version`, majorver, frames, 
    iframes, tabless, cookies, javascript, ismobiledevice, cssversion, device_type, device_pointing_method, browser_modus, minorver, 
    platform_version, alpha, beta, win16, win32, win64, backgroundsounds, vbscript, activexcontrols, istablet, issyndicationreader, 
    crawler, isfake, isanonymized, ismodified, privacy_score,new_privacy_score,cvss_score,time_privacy_score,time_cvss_score) $getAS";
if (mysqli_query($conn, $insertPart1)) {
    echo "insert new is done.. <br>";
} else {
    echo "Error insertPart1 record: " . $conn->error . "<br>";
}
// -------------------------------------------
$c = 0;
if ($_FILES['file']['name']) {
    $filename = explode(".", $_FILES['file']['name']);
    if ($filename[1] == 'txt') {
        $handle = fopen($_FILES['file']['tmp_name'], "r");
        while ($data = fgets($handle)) {
            $info = get_browser($data);
            $colvals = array_fill(0, 50, 0.0);
            $numofnulls = 0;
            foreach ($info as $heading => $value) {
                $pos = array_search($heading, $cols); // return position of value from heading
                if ($pos != false) {
                    if ($heading == "browser_name_regex" || $heading == "browser_name_pattern" || $heading == "aolversion") {
                        $value = -999;
                    } else if (($types[$pos] == "f" ||  $types[$pos] == "i") && empty($value)) {
                        $numofnulls += 1;
                        $value = -1;
                    } else if ($types[$pos] == "v" && empty($value)) {
                        $numofnulls += 1;
                        $value = "unknown";
                    }
                } else if ($heading == "parent") {
                    $value = $value;
                } else {
                    $value = -999;
                }
                if ($value != -999) {
                    if ($types[$pos] == "f") {
                        settype($value, "float");
                    } else if ($types[$pos] == "i") {
                        settype($value, "integer");
                    } else if ($types[$pos] == "v") {
                        settype($value, "string");
                    }
                    $colvals[$pos] = $value;
                }
                $colvals[0] = null;
                $colvals[48] = 47 - $numofnulls;
            }
            $checkRow = "SELECT parent FROM new WHERE parent like '$colvals[1]' and browser_bits = $colvals[2] 
            and platform like '$colvals[3]' and platform_description like '$colvals[4]' and platform_bits like '$colvals[5]' 
            and platform_maker like '$colvals[6]' and javaapplets like '$colvals[7]'  
            and device_name like '$colvals[8]' and device_maker like '$colvals[9]'  
            and device_code_name like '$colvals[10]' and device_brand_name like '$colvals[11]'  
            and renderingengine_name like '$colvals[12]' and renderingengine_version like '$colvals[13]'  
            and renderingengine_description like '$colvals[14]' and renderingengine_maker like '$colvals[15]'  
            and comments like '$colvals[16]' and browser like '$colvals[17]'  
            and browser_type like '$colvals[18]' and browser_maker like '$colvals[19]' 
            and `version` like '$colvals[20]' and `majorver` like '$colvals[21]'  
            and `frames` like '$colvals[22]' and `iframes` like '$colvals[23]'  
            and `tabless` like '$colvals[24]' and `cookies` like '$colvals[25]'  
            and `javascript` like '$colvals[26]' and `ismobiledevice` like '$colvals[27]'  
            and `cssversion` like '$colvals[28]' and `device_type` like '$colvals[29]'  
            and `device_pointing_method` like '$colvals[30]' and `browser_modus` like '$colvals[31]'  
            and `minorver` like '$colvals[32]'";
            $result = $conn->query($checkRow);
            if ($result->num_rows > 0) {
                // echo "This row already exists...<br>";
            } else {
                $insertPart2 = "INSERT into new (parent, browser_bits, platform, platform_description, platform_bits, platform_maker, 
    javaapplets, device_name, device_maker, device_code_name, device_brand_name, renderingengine_name, renderingengine_version, 
    renderingengine_description, renderingengine_maker, comments, browser, browser_type, browser_maker, `version`, majorver, frames, 
    iframes, tabless, cookies, javascript, ismobiledevice, cssversion, device_type, device_pointing_method, browser_modus, minorver, 
    platform_version, alpha, beta, win16, win32, win64, backgroundsounds, vbscript, activexcontrols, istablet, issyndicationreader, 
    crawler, isfake, isanonymized, ismodified, privacy_score) 
    values( '$colvals[1]','$colvals[2]','$colvals[3]','$colvals[4]','$colvals[5]','$colvals[6]','$colvals[7]','$colvals[8]','$colvals[9]',
    '$colvals[10]','$colvals[11]', '$colvals[12]','$colvals[13]','$colvals[14]','$colvals[15]','$colvals[16]','$colvals[17]','$colvals[18]',
    '$colvals[19]','$colvals[20]','$colvals[21]', '$colvals[22]','$colvals[23]','$colvals[24]','$colvals[25]','$colvals[26]','$colvals[27]',
    '$colvals[28]','$colvals[29]','$colvals[30]','$colvals[31]', '$colvals[32]','$colvals[33]','$colvals[34]','$colvals[35]','$colvals[36]',
    '$colvals[37]','$colvals[38]','$colvals[39]','$colvals[40]','$colvals[41]', '$colvals[42]','$colvals[43]','$colvals[44]','$colvals[45]',
    '$colvals[46]','$colvals[47]','$colvals[48]')";
                mysqli_query($conn, $insertPart2);
                $c++;
            }
        }
    }
}

// -------------------------------------------
$deletePart2 = "DELETE FROM `agentstrings` WHERE 1";
if (mysqli_query($conn, $deletePart2)) {
} else {
    echo "Error deletePart2: " . $conn->error . "<br>";
}
// -------------------------------------------
$insertPart3 = "INSERT INTO `agentstrings` (parent, browser_bits, platform, platform_description, platform_bits, platform_maker, 
    javaapplets, device_name, device_maker, device_code_name, device_brand_name, renderingengine_name, renderingengine_version, 
    renderingengine_description, renderingengine_maker, comments, browser, browser_type, browser_maker, `version`, majorver, frames, 
    iframes, tabless, cookies, javascript, ismobiledevice, cssversion, device_type, device_pointing_method, browser_modus, minorver, 
    platform_version, alpha, beta, win16, win32, win64, backgroundsounds, vbscript, activexcontrols, istablet, issyndicationreader, 
    crawler, isfake, isanonymized, ismodified, privacy_score,new_privacy_score,cvss_score,time_privacy_score,time_cvss_score) $getNew";
if (mysqli_query($conn, $insertPart3)) {
} else {
    echo "insertPart3" . $conn->error . "<br>";
}
// -------------------------------------------
echo "<h3>Has been checked $c rows..<br>";
