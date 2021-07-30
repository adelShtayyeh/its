<?php
$useragent = $_SERVER["HTTP_USER_AGENT"];
$originalUA = $useragent;
$isInputUseragent = false;
if (isset($_REQUEST["useragent"]) && $_REQUEST["useragent"] != "") {
    $useragent = $_REQUEST["useragent"];
    $isInputUseragent = true;
}
// echo "<pre><small>" . $useragent . "</small></pre>";
