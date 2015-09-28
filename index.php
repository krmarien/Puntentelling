<?php
require_once('php/config.php');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Puntentelling <?= Config::$ORGANISATION; ?></title>
        <link rel="stylesheet" href="stylesheet/bootstrap.min.css">
        <link rel="stylesheet" href="stylesheet/style.css">
    </head>
    <body>
        <div class="container">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="/">Puntentelling</a>
                        <ul class="nav">
                            <li><a href="team.php">Ploegen beheren</a></li>
                            <li><a href="insertscore.php">Punten invoeren</a></li>
                            <li><a href="viewscore.php">Punten bekijken</a></li>
                            <li><a href="endscore.php">Eindstand</a></li>
                            <li><a href="export.php">Export</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <header class="subhead">
                <h1>Puntentelling <?= Config::$ORGANISATION; ?></h1>
            </header>
            <div class="subnav">
                <ul class="nav nav-pills">
                    <li><a href="team.php">Ploegen beheren</a></li>
                    <li><a href="insertscore.php">Punten invoeren</a></li>
                    <li><a href="viewscore.php">Punten bekijken</a></li>
                    <li><a href="endscore.php">Eindstand</a></li>
                    <li><a href="export.php">Export</a></li>
                </ul>
            </div>
        </div>
    </body>
</html>
