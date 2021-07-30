<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>UserInterface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585Ach69TLBQObG" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./stylesheet.css">
    <script>
        function loadCalc() {
            var a = document.getElementById("resultCalc").innerText;
            if (a == null || a == "") {
                var x = document.getElementById("loadCalc");
                x.style.display = "block";
            } else {
                var x = document.getElementById("loadCalc");
                x.style.display = "none";
            }

        }
    </script>

</head>

<body>
</body>
<div class="main" id="main1">
    <div class="container" id="div1">
        <?php
        require "connectionDB.php";
        require "agentstrings.php";
        require "arrays.php";
        require "extract_useragents_from_browser.php";
        echo "<pre><small>" . $useragent . "</small></pre>";
        ?>
    </div>
    <div class="container" id="div2">
        <?php
        echo "<p id='nameBrowser'>" . $colvals[17] . "\t" . $colvals[20] . "\tOn " . $colvals[4] . "\t" . $colvals[2] . "bit</p>";
        ?>
    </div>
    <hr id="split">
    <div class="col-sm" id="div3">
        <table>
            <?php
            echo "<tr><td colspan='2'><h5>Information About Your System</h5></td></tr>";
            for ($i = 0; $i < (sizeof($colvals)); $i++) {
                if ($colvals[$i] != -1 && $colvals[$i] != "unknown" && $colvals[$i] != 0 && $colvals[$i] != 3 && $colvals[$i] != 1 && $cols[$i] != "privacy_score") {
                    echo "<tr><td><b>" . str_replace("_", " ", $cols[$i]) . "</b></td><td>$colvals[$i]</td></tr>";
                }
            }
            ?>
        </table>
    </div>

    <br>
    <div class="container" id="main2">
        <div class="container" id="div5">
            <br>
            <form method="post" enctype="multipart/form-data">
                <input type="submit" onclick="loadCalc()" name="calcScore" class="btn btn-secondary btn-lg" value="Calculate Browser Score" />
            </form>
            <br>
            <div class="loader" id="loadCalc"></div>
            <div id="resultCalc">
                <?php
                if (isset($_POST['calcScore'])) {
                    require "calculate.php";
                }
                ?>
            </div>
            <div id="alternative">
                <?php
                if (isset($_POST['calcScore'])) {
                    require "select_alternative_browser.php";
                    // sleep(1);
                    $auto = 10;
                }
                ?>
            </div>
        </div>
    </div>
    <br>
    <div class="container" id="main3">
        <div class="container" id="div7">
            <p>
                - This score determines the extent to which your privacy is exposed to the violation,
                by means of our equation that depends on the largest site in the field of leaked vulnerabilities is
                CVEs by taking CVSS scores in addition to the percentage of leakage by the user agent.
                From which we get a total of 20 scores. We present it to you and suggest you the best options.
            </p>
        </div>
    </div>
</div><br>
<hr id="split">
<footer class="bg-light text-center text-lg-start">
    <div class="text-center p-3" id="footer">
        © 2021 Copyright:
        <a class="text-dark" href="userGUI.php">Graduation Project</a>
    </div>
    <?php $conn->close(); ?>
</footer>
</div>

</html>