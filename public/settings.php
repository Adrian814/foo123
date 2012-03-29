<?php session_start(); ?>

<html>
    <head>
        <?php
        include "ini_set.inc.php";
        include "design.class.php";
        include "user.class.php";

        $user = new userclass();
        $user->checkCookie();

        $design = new designclass();
        $design->createHeader("Demo Kalender > Settings", "css/web_tech.css");

        // check if settings were changed in 'changeSettings.php'
        if (isset($_POST["submit"])) {

        // first check if picture was changed -> fill $picture with some value
            if($_FILES["userfile"]["error"] != 4) {
            // debug
            //echo print_r($_FILES["userfile"]) . "<p>";

                $name = $_FILES["userfile"]["name"];
                $temp_name = $_FILES["userfile"]["tmp_name"];
                $path = "pictures/profiles/";
                $final_name = substr($user->createPassword(), 0, 3) . "_".$name;
                $picture = $final_name;
                move_uploaded_file($temp_name, $path.$final_name);
            }
            else // save the old pic again
                $picture = $user->getField($_SESSION["nick"], "picture");

            // change settings + password
            if(isset($_POST["pw"])) {
                if(!$_POST["pw1"] || !$_POST["pw2"]) {
                    $msg ='Passwords cannot be empty!';
                }
                else if($_POST["pw1"] == $_POST["pw2"]) {
                        $change = $user->changeSettings($_SESSION["nick"],
                            $_POST["first"],
                            $_POST["last"],
                            $_POST["view"],
                            $_POST["hobbies"],
                            $_POST["email"],
                            $picture);

                        $user->setPassword($_SESSION["nick"], $_POST["pw1"]);
                        $msg = "Settings and password saved.";
                    }
                    else
                        $msg = "Passwords do not match!";
            }
            else {
                $change = $user->changeSettings($_SESSION["nick"],
                    $_POST["first"],
                    $_POST["last"],
                    $_POST["view"],
                    $_POST["hobbies"],
                    $_POST["email"],
                    $picture);
                $msg = "Settings saved.";
            }

        }

        ?>

        <script type="text/javascript" src="js/settings.js"> </script>
    </head>
    <body>
        <div id="all">

            <?php

            $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <?php
                    $user->createSettings($_SESSION["nick"], true);

                    if(isset($msg)) {
                        echo "<span id='error'>";
                        echo $msg;
                        echo "</span>";
                    }
                    ?>




                </div>
                <div id="calendar">
                    <?php include "cal.php"; ?>
                </div>
            </div>
        </div>
    </body>
</html>