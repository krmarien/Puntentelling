<?php
require_once('php/config.php');
require_once('php/Teams.php');
require_once('php/Team.php');
require_once('php/Score.php');

pg_connect('host=localhost dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

$teams = Teams::findAllByRank(Config::$ENDSCOREORDER);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Puntentelling <?= Config::$ORGANISATION; ?></title>
        <link rel="stylesheet" href="stylesheet/bootstrap.min.css">
        <link rel="stylesheet" href="stylesheet/style.css">
        <style type="text/css">
            .table {
                font-size: 30px;
                width: 700px;
                margin: 0 auto;
            }
            .table th, .table td {
                line-height: 35px;
            }
            .strong {
                font-weight: bold;
                font-size: 40px;
            }
            thead.fixedHeader {
                display: block;
            }
            tbody.scrollContent {
                overflow: auto;
                display: block;
            }
            table .rank {
                width: 150px;
            }
            table .total {
                width: 150px;
            }
        </style>
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
                            <li class="active"><a href="endscore.php">Eindstand</a></li>
                            <li><a href="export.php">Export</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <header class="subhead">
                <h1>Puntentelling <?= Config::$ORGANISATION; ?></h1>
            </header>
            <div style="float: right;margin-bottom: 5px;">
                <button id="fullScreen" class="btn btn-primary">Full Screen</button>
            </div>
            <div id="content" style="clear: both; padding: 5px;">
                <table id="ranking" class="table table-striped table-bordered">
                    <thead class="fixedHeader">
                        <tr class="header">
                            <th class="rank">Ranking</th>
                            <th class="teamName">Ploegnaam</th>
                            <th class="total">Totaal</th>
                        </tr>
                    </thead>
                    <tbody class="scrollContent">
                        <?php
                        $rank = Config::$ENDSCOREORDER == 'DESC' ? 1 : sizeof($teams);
                        foreach($teams as $team): ?>
                            <tr class="team" style="display: none;">
                                <td class="rank"><?= $rank; ?></td>
                                <td class="teamName"><?= $team->getName(); ?></td>
                                <td class="total"><?= $team->getTotalScore(); ?></td>
                            </tr>
                            <?php $rank += Config::$ENDSCOREORDER == 'DESC' ? 1 : -1; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="js/jquery-1.7.1.min.js"></script>
        <script src="js/jquery.fullscreen.js"></script>
        <script src="js/autoScroll.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                if($.support.fullscreen){
                    $('#fullScreen').click(function(e){
                        $('#content').fullScreen({
                            background: '#fff',
                            callback: function (isFullScreen) {
                                if (!isFullScreen) {
                                    applySizes();
                                } else {
                                    applySizes(true);
                                }
                            }
                        });
                    });
                }
                applySizes();
                $('#ranking').autoScroll({method: 'alwaysDown', pauzed: false});
            });
            $(document).keyup(function (e) {
                if (e.keyCode == 37 || e.keyCode == 38) {
                    $('#ranking .team:visible:last').hide();
                    e.preventDefault();
                } else if (e.keyCode == 39 || e.keyCode == 40) {
                    $('#ranking .team:visible:last').removeClass('strong');
                    $('#ranking .team:hidden:first').addClass('strong').show();
                    $('#ranking tbody').change();
                    e.preventDefault();
                }
            });

            function applySizes(isFullScreen) {
                isFullScreen = isFullScreen == undefined ? false : true;

                $('#ranking .teamName').width($('#ranking').width() - $('#ranking .rank:first').width() - $('#ranking .rank:first').css('padding-left').replace("px", "") * 2 - $('#ranking .total:first').width() - $('#ranking .total:first').css('padding-left').replace("px", "") * 2);
                if (isFullScreen)
                    $('#ranking tbody').css('max-height', $('#content').parent().height() - $('#ranking thead').height() - $('#content').css('padding-top').replace("px", "") * 2 - $('#ranking').css('margin-bottom').replace("px", "") - 2);
                else
                    $('#ranking tbody').css('max-height', $(window).height() - $('#ranking').offset().top - $('#ranking thead').height() - $('#ranking').css('margin-bottom').replace("px", "") - 7);
            }
        </script>
    </body>
</html>
