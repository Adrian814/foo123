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
            $design->createHeader("Demo Kalender > Forget Password", "css/web_tech.css");


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
                        // check if form has already been sent.
                        if (isset($_POST["submitRequestEmail"])) {
                           
                            $user->createRequestPassword();
                            echo "<p><span id='error'>";
                            echo $user->sendMail($_POST["email"]);
                            echo "</span></p>";
                        }
                        else {
                            $user->createRequestPassword();
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