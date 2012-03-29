<?php session_start();

?>

<html>
    <head>
        <?php
            //include "ini_set.inc.php";
            include "design.class.php";
            include "user.class.php";

            $user = new userclass();
            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender > Change Settings", "css/web_tech.css");



        ?>

    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <h2>Change settings for <?php echo $_SESSION["nick"]; ?> </h2>
                    <?php
                        $user->createChangeSettings($_SESSION["nick"]);
                    ?>
                </div>
                <div id="calendar">
                    <?php include "cal.php"; ?>
                </div>
            </div>
        </div>
    </body>
</html>