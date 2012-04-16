<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Plain & Clean
   
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20111024

-->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />

        <?php
        // Get Instance of Core 
        $view = View::getInstance();
        // Print Title
        echo "<title>" . $view->title . "</title>";
        // Print CSS
        echo $view->printCss();
        // Print Javascript
        echo $view->printJs();
        ?>

    </head>
    <body>
        <div id="wrapper">
            <div id="header" class="container">
                <?php $view->header(); ?>
            </div>
            <!-- end #header -->
            <div id="page" class="container">
                <div id="content">
                    <div class="post">
                        <?php
                        echo $view->msg(); // Prints various status (error/success msg etc.)
                        echo "<br />";
                        $view->mainContent();
                        ?>
                    </div>

                    <div style="clear: both;">&nbsp;</div>
                </div>
                <!-- end #content -->
                <div id="sidebar">
                    <ul>
                        <?php $view->sidebar("right"); ?>
                    </ul>
                </div>
                <!-- end #sidebar -->
                <div style="clear: both;">&nbsp;</div>
            </div>
            <!-- end #page -->
        </div>
        <div id="footer-content" class="container">
            <?php $view->footer(); ?>
        </div>
        <div id="footer">
            <p>Powered by Swadhin Framework &bull; Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
        </div>
        <!-- end #footer -->
    </body>
</html>
