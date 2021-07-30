<?php
$stmt1 = "SELECT DISTINCT `parent`, `browser_bits`, `platform`, `platform_description`, `platform_bits`, `platform_maker`, 
`javaapplets`, `device_name`, `device_maker`, `device_code_name`, `device_brand_name`, `renderingengine_name`, 
`renderingengine_version`, `renderingengine_description`, `renderingengine_maker`, `comments`, `browser`, `browser_type`, 
`browser_maker`, `version`, `majorver`, `frames`, `iframes`, `tabless`, `cookies`, `javascript`, `ismobiledevice`, `cssversion`, 
`device_type`, `device_pointing_method`, `browser_modus`, `minorver`, `platform_version`, `alpha`, `beta`, `win16`, `win32`, `win64`, 
`backgroundsounds`, `vbscript`, `activexcontrols`, `istablet`, `issyndicationreader`, `crawler`, `isfake`, `isanonymized`, 
`ismodified`, `privacy_score`, `new_privacy_score` , `cvss_score` FROM agentstrings 
WHERE browser_bits=$colvals[2] and platform_bits=$colvals[5] and browser_type like '$colvals[18]' and device_type like '$colvals[29]' 
and platform_maker like '$colvals[6]' and platform_description like '$colvals[4]' and privacy_score < $colvals[48] 
AND ((new_privacy_score + cvss_score) < $final_score) and `version` not like '0.0' ORDER BY cvss_score limit 5";

// echo "<br> $stmt1 <br";

$counter = 1;
$result1 = $conn->query($stmt1);
echo "<hr style='width:500px;text-align: center;margin:auto; margin-bottom:10px'>";
echo "<h4>Alternative Browsers Selected:</h4>";
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<h5> $counter. " .
            $row['browser'] . " " . $row['version'] . " "  . $row['browser_bits'] .
            " bit for " . $row['platform'] . " " . $row['platform_bits'] . " bit | Total Score: <b id ='scoreColor'>" . ($row['new_privacy_score'] + $row['cvss_score']) . "</b>"
            . "</h5>";
        $counter += 1;
    }
} else {
    echo "your Browser is Unique";
}
