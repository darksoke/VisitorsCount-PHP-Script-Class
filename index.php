<?php include __DIR__ . "/library/class_lib.php" ?>

<html>
    <head>
        <title>Visitors Count by Darksoke</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" title="Default Styles" media="screen">
    </head>
    <body>
    <div id="centered">
        <div id="title">PHP Visitors Counter Class by Darksoke</div>
        <ul>
            <li><span>Total Visitors</span> <label><?php echo $visitors->totalVisitors(); ?></label></li>
            <li><span>Unique Visitors</span> <label><?php echo $visitors->uniqueVisitors(); ?></label></li>
            <li><span>Total Visitors Past</span> <label><?php echo $visitors->start_time(15); ?></label> <span>minutes</span> <label><?php echo $visitors->latestVisitors(); ?></label></li>
        </ul>
        <div id="credits">You may not, directly or indirectly: (a) sell, modify, translate, copy, publish, transmit, distribute or otherwise disseminate the content without asking for proper permission.</div>
    </div>
    </body>
</html>
