<?php
session_start();
?>

<html>
    <head>
        <?php
            include "ini_set.inc.php";
            include "design.class.php";
            include "user.class.php";

            $user = new userclass();
            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender", "css/web_tech.css");

            
        ?>

    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <p><span id="error">Thanks for informing us!</span></p>
                    <?php
                        if(isset($_GET["id"]))
                        {
                            echo "<span id='error'>" . $user->reportEvent($_GET["id"]) . "</span>";
                        }
                    ?>
                </div>
                <div id="calendar">
                    <?php

                        include "cal.php";

                    ?>
                </div>
            </div>
        </div>
    </body>
</html>