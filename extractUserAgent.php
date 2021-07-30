<?php
// ------------------------------------------------------------------------- Connection with DataBase -------------------------
use function PHPSTORM_META\type;

$servername = "localhost";
$username = "root";
$password = "";
$db = "useragents";
// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    $msg1 = "Connection error";
    echo "<h1>$msg1</h1>";
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
$msg = "Connected successfully";
//echo "<h1>$msg</h1>";
// -------------------------------------------------------------------------------- End Connection --------------------------
$cols = array(
    "browserid", "parent", "browser_bits", "platform",
    "platform_description",
    "platform_bits",
    "platform_maker",
    "javaapplets",
    "device_name",
    "device_maker",
    "device_code_name",
    "device_brand_name",
    "renderingengine_name",
    "renderingengine_version",
    "renderingengine_description",
    "renderingengine_maker",
    "comments",
    "browser",
    "browser_type",
    "browser_maker",
    "version",
    "majorver",
    "frames",
    "iframes",
    "tables",
    "cookies",
    "javascript",
    "ismobiledevice",
    "cssversion",
    "device_type",
    "device_pointing_method",
    "browser_modus",
    "minorver",
    "platform_version",
    "alpha",
    "beta",
    "win16",
    "win32",
    "win64",
    "backgroundsounds",
    "vbscript",
    "activexcontrols",
    "istablet",
    "issyndicationreader",
    "crawler",
    "isfake",
    "isanonymized",
    "ismodified",
    "privacy_score",
    "new_privacy_score"
);
$types = array(
    "i", "v", "i", "v", "v", "i", "v", "f", "v", "v", "v", "v",
    "v", "v", "v", "v", "v", "v", "v", "v", "v", "v", "f", "f", "f", "f", "f", "f",
    "i", "v", "v", "v", "v", "v", "f", "f", "f", "f", "f", "f", "f", "f", "f", "f", "f", "f",
    "f", "f", "i", "f"
);
// ------------------------------------------------------------------------------ Extract UserAgent from file ---------
$myfile = fopen("UAstring.txt", "r") or die("Unable to open UAstring file!");
// Output one line until end-of-file
$c = 0;
while (!feof($myfile)) {
    // echo fgets($myfile) . "<br>";
    $info = get_browser(fgets($myfile));
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
    $colnames = implode(", ", $cols);
    $colvalues = implode(", ", $colvals);
    // echo "colnames: <br>$colnames";
    // echo "<hr>";
    // echo "colvalues: <br>$colvalues";
    // echo "<hr>";
    // echo "[ colnames ] Type: " . gettype($colnames) . ", Size: " . count($cols);
    // echo "<br>[ colvals ] Type: " . gettype($colvals) . ", Size: " . count($colvals);
    // --------------------------------------------------------------------------------- Calculate new_privacy_score----------------
    $checkRow = "SELECT parent FROM new WHERE parent like '$colvals[1]' and browser_bits = $colvals[2] and platform like '$colvals[3]' and new_privacy_score=0";
    // echo $checkRow;
    $result = $conn->query($checkRow);
    $score = 0;
    $count = 0;
    if ($result->num_rows > 0) {
        // echo "This row already exists...";
    } else {
        // if a new User-agent does not exist,then calculat a score, and insert it into the database.
        // echo "<hr>";
        // print_r($colvals);
        $attributes = "SELECT * FROM attributes";
        $result_att = $conn->query($attributes);
        if ($result_att->num_rows > 0) {
            // output data of each row
            while ($row = $result_att->fetch_assoc()) {
                $count++;
                // echo $sj . "-" . $vj . "-" . $sofj . "-" . $rjovern . "<br>";
                if ($colvals[$count] == -1 || $colvals[$count] == "unknown") {
                    $score += 0;
                } else {
                    $sj = $row["sj"];
                    $vj = $row["vj"];
                    $sofj = $row["sofj"];
                    $rjovern = $row["rjovern"];
                    $score += ($sofj + $sj) * ((($rjovern) + ($colvals[48] / 47)) + $vj);
                }
            }
        }
        $colvals[49] = floatval(($score));
        $query = "INSERT into agentstrings (parent, browser_bits, platform, platform_description, platform_bits, platform_maker, 
    javaapplets, device_name, device_maker, device_code_name, device_brand_name, renderingengine_name, renderingengine_version, 
    renderingengine_description, renderingengine_maker, comments, browser, browser_type, browser_maker, `version`, majorver, frames, 
    iframes, tabless, cookies, javascript, ismobiledevice, cssversion, device_type, device_pointing_method, browser_modus, minorver, 
    platform_version, alpha, beta, win16, win32, win64, backgroundsounds, vbscript, activexcontrols, istablet, issyndicationreader, 
    crawler, isfake, isanonymized, ismodified, privacy_score, new_privacy_score) 
    values( '$colvals[1]','$colvals[2]','$colvals[3]','$colvals[4]','$colvals[5]','$colvals[6]','$colvals[7]','$colvals[8]','$colvals[9]',
    '$colvals[10]','$colvals[11]', '$colvals[12]','$colvals[13]','$colvals[14]','$colvals[15]','$colvals[16]','$colvals[17]','$colvals[18]',
    '$colvals[19]','$colvals[20]','$colvals[21]', '$colvals[22]','$colvals[23]','$colvals[24]','$colvals[25]','$colvals[26]','$colvals[27]',
    '$colvals[28]','$colvals[29]','$colvals[30]','$colvals[31]', '$colvals[32]','$colvals[33]','$colvals[34]','$colvals[35]','$colvals[36]',
    '$colvals[37]','$colvals[38]','$colvals[39]','$colvals[40]','$colvals[41]', '$colvals[42]','$colvals[43]','$colvals[44]','$colvals[45]',
    '$colvals[46]','$colvals[47]','$colvals[48]','$colvals[49]')";
        mysqli_query($conn, $query);
    }
    // echo $query;
    // echo "<hr>";
    // echo "<hr>$colvals[49]<br>";
    // echo "<hr>";
    // echo "<hr>$score<br>";
    // echo "<hr>";

    // ----------------------------------------
    $c++;
}
echo "has been inserted $c rows <br>";
fclose($myfile);
