<!DOCTYPE html>
<html>

<head>
    <?php require "connectionDB.php"; ?>
    <meta charset="utf-8">
    <title>demo GUI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585Ach69TLBQObG" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

    <script>
        function loadAtt() {
            var a = document.getElementById("resultAtt").innerText;
            if (a == null || a == "") {
                var x = document.getElementById("loadAtt");
                x.style.display = "block";
            } else {
                var x = document.getElementById("loadAtt");
                x.style.display = "none";

            }
        }

        function loadCVSS() {
            var a = document.getElementById("resultCVSS").innerText;
            if (a == null || a == "") {
                var x = document.getElementById("loadCVSS");
                x.style.display = "block";
            } else {
                var x = document.getElementById("loadCVSS");
                x.style.display = "none";

            }
        }

        function loadAdd() {
            var a = document.getElementById("addUA").innerText;
            if (a == null || a == "") {
                var x = document.getElementById("loadAdd");
                x.style.display = "block";
            } else {
                var x = document.getElementById("loadAdd");
                x.style.display = "none";
            }
        }
    </script>

</head>
<style>
    body {
        background-color: #d7e3eb;
        margin-top: 2px;
        font-family: monospace;
        font-size: 1.1em;

    }

    .container {
        max-width: 100%;
        padding: 2px;
    }

    #nav {
        background-color: #04597d8c;
        padding: 5px;
        max-width: 100%;
        height: 35px;
        border-left: 1px solid;
        border-right: 1px solid;
        border-top: 0.5px solid;
        border-bottom: 0.5px solid;
        text-align: center;
    }

    #nav_id {
        border-left: 1px solid;
        border-right: 1px solid;
        border-top: 0.5px solid;
        border-bottom: 0.5px solid;
        margin: auto;
        width: 450px;
    }

    #attribute,
    #cvss,
    #UA {
        max-width: 100%;
        border-left: 1px solid;
        border-right: 1px solid;
        border-top: 0.5px solid;
        border-bottom: 0.5px solid;
        text-align: center;
        padding: 40px;

    }

    #footer1 {
        border-left: 1px solid;
        border-right: 1px solid;
        border-top: 0.5px solid;
        border-bottom: 0.5px solid;
    }

    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 100px;
        height: 100px;
        -webkit-animation: spin 5s linear infinite;
        /* Safari */
        animation: spin 5s linear infinite;
        margin: auto;
        display: none;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="container">
        <div id="nav">
            <h6><a class="text-dark" href="adminGUI.php">Home: Admin</a></h6>
        </div>
        <div id="attribute">
            <div id="lastUpdate">
                <?php
                $sqllastUp_privacy = "select time_privacy_score from agentstrings";
                $resultlastUp = $conn->query($sqllastUp_privacy);
                if ($resultlastUp->num_rows > 0) {
                    if ($lastUp = $resultlastUp->fetch_assoc()) {
                        if ($lastUp['time_privacy_score'] != null) {
                            echo "<h5>Last Update in " . $lastUp['time_privacy_score'] . "</h5>";
                        }
                    }
                }
                ?>
            </div>
            <form method="post" enctype="multipart/form-data">
                <input id="nav_id" onclick="loadAtt()" name="update" type="submit" class="btn btn-secondary btn-lg" value="Update Relative Scores" />
            </form><br>
            <div class="loader" id="loadAtt"></div>
            <div id="resultAtt">
                <?php
                if (isset($_POST['update'])) {
                    require "attributeCalculation.php";
                    echo "<br><h4>Updating successfully was done for <span style='color:red;'>$totalRows</span> rows, in $time_privacy_score</h4>";

                }
                ?>
            </div>

        </div>
        <div id="cvss">
            <div id="lastUpdate">
                <?php
                $sqllastUp_cvss = "select time_cvss_score from agentstrings";
                $resultlastUp = $conn->query($sqllastUp_cvss);
                if ($resultlastUp->num_rows > 0) {
                    if ($lastUp = $resultlastUp->fetch_assoc()) {
                        if ($lastUp['time_cvss_score'] != null) {
                            echo "<h5>Last Update in " . $lastUp['time_cvss_score'] . "</h5>";
                        }
                    }
                }
                ?>
            </div>
            <!-- <form method="post" enctype="multipart/form-data">
                <input id="nav_id" onclick="loadCVSS()" type="submit" name="CVSS" class="btn btn-secondary btn-lg" value="Check If All CVSS Score Is Calculated" />
            </form> -->
            <br>
            <form method="post" enctype="multipart/form-data">
                <input id="nav_id" onclick="loadCVSS()" type="submit" name="UpCVSS" class="btn btn-secondary btn-lg" value="Update CVSS Scores" />
            </form>
            <br>
            <div class="loader" id="loadCVSS"></div>
            <div id="resultCVSS">
                <?php
                // if (isset($_POST["CVSS"])) {
                //     require "cvss_scoreCalculation.php";
                // }
                if (isset($_POST["UpCVSS"])) {
                    require "Upcvss_scoreCalculation.php";
                }
                ?>
            </div>
        </div>
        <div id="UA">
            <h5>Add New AgentStrings:</h5>
            <div id="resultAdd">
                <form method="post" enctype="multipart/form-data">
                    <h6>Select .txt File:</h6>
                    <input type="file" name="file" />
                    <br><br>
                    <input id="nav_id" type="submit" onclick="loadAdd()" name="UA" value="Import To DataBase" class="btn btn-secondary btn-lg" />
                    <br><br>
                    <div class="loader" id="loadAdd"></div>
                </form>
                <div id="addUA">
                    <?php
                    if (isset($_POST["UA"])) {
                        require "add_New_UA_toDB.php";
                    }
                    ?>
                </div>
            </div>

        </div>
        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start" id="footer1">
            <div class="text-center p-3" style="background-color: #d7e3eb;">
                © 2021 Copyright:
                <a class="text-dark" href="adminGUI.php">Graduation Project</a>
            </div>
        </footer>
        <!-- Footer -->
    </div>
</body>

</html>