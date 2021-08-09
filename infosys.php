<!DOCTYPE html>
<html>

<head>
    <?php
//     require "./connectionDB.php";
    require "./agentstrings.php";
    require "./arrays.php";
    require "./extract_useragents_from_browser.php";
    ?>
    <meta charset="utf-8">
    <title>Safe Browsing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585Ach69TLBQObG" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./newstyle.css">

</head>

<body>
    <ul style="margin-bottom: 10px; text-align: center;">
        <a class="nav-link active" aria-current="page" href="./index.php">
            <img src="./logo1.png" alt="Logo">
        </a>
    </ul>

    <div class="container" id="div1_top">
        <h3 style="text-align: center;">Information About Your System</h3>
        <div class="container-fluid">
            <h5>Your User-Agent is:</h5>
            <div class="container-fluid" style="text-align: center;">
                <p style="font-size: large; background-color: #faeded;">
                    <span>
                        <?php
                        echo $useragent;
                        ?>
                    </span>
                </p>
            </div>
            <div class="container-fluid">
                <p>Now you've seen your web browser's basic settings about JavaScript and Cookies,
                    here is a list of more technical information about your web browser and computer.
                    Most of these aren't really settings that you can change,
                    they're general bits of information about the computer you're using to access the internet.
                </p>
                <div class="container-fluid">
                    <table>
                        <?php
                        // echo "<tr><td colspan='2'><h5>Information About Your System</h5></td></tr>";
                        for ($i = 0; $i < (sizeof($colvals)); $i++) {
                            if ($colvals[$i] != -1 && $colvals[$i] != "unknown" && $colvals[$i] != 0 && $colvals[$i] != 3 && $colvals[$i] != 1 && $cols[$i] != "privacy_score") {
                                echo "<tr><td><b>" . str_replace("_", " ", $cols[$i]) . "</b></td><td>$colvals[$i]</td></tr>";
                            }
                        }
                        ?>
                    </table>
                    <form method="post" action="./index.php" enctype="multipart/form-data" style=" text-align: center; margin: 10px;">
                        <input style="background-color:#198754; color:black;" type="submit" name="back" class="btn btn-secondary btn-lg" value="Go Back" />
                    </form>
                    <hr>
                </div>
            </div>
        </div>

    </div>
    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3" id="footer">
            © 2021 Copyright:
            <a class="text-dark" href="./index.php">Graduation Project</a>
        </div>
    </footer>

</body>

</html>
