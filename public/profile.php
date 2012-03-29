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

        

        ?>

        
    </head>
    <body>
        <div id="all">

            <?php

            $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <?php
                        if (!isset($_SESSION["nick"])) {
                            echo "<meta http-equiv='refresh' content='0; url=index.php'>";
                        }
                        else
                            $user->createSettings($_GET["nick"], false);

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