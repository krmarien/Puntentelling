<?php
require_once('php/config.php');
require_once('php/Teams.php');
require_once('php/Team.php');
require_once('php/Score.php');

pg_connect('host=localhost dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

$teams = Teams::findAll();
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
                            <li class="active"><a href="insertscore.php">Punten invoeren</a></li>
                            <li><a href="viewscore.php">Punten bekijken</a></li>
                            <li><a href="endscore.php">Eindstand</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <header class="subhead">
                <h1>Puntentelling <?= Config::$ORGANISATION; ?></h1>
            </header>

            <ul class="nav nav-tabs" id="tab">
                <?php if (Config::$HASSEPARATION): ?>
                    <li><a href="#separation" data-toggle="tab">Schifting</a></li>
                <?php endif; ?>

                <?php foreach(Config::$ROUNDS as $num => $round): ?>
                    <li><a href="#round<?= $num; ?>" data-toggle="tab"><?= $round; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content" id="tab-content">
                <?php if (Config::$HASSEPARATION): ?>
                    <div id="separation" class="tab-pane fade in form-horizontal">
                        <?php foreach($teams as $team): ?>
                            <div class="control-group">
                                <label class="control-label" for="name"><?= $team->getName(); ?></label>
                                <div class="controls">
                                    <input type="text" data-team="<?= $team->getId(); ?>" data-round="separation" class="input-xlarge roundScore" value="<?= $team->getScore('separation'); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php foreach(Config::$ROUNDS as $num => $round): ?>
                    <div id="round<?= $num; ?>" class="tab-pane fade in form-horizontal">
                        <?php foreach($teams as $team): ?>
                            <div class="control-group">
                                <label class="control-label" for="name"><?= $team->getName(); ?></label>
                                <div class="controls">
                                    <input type="text" data-team="<?= $team->getId(); ?>" data-round="<?= $num; ?>" class="input-xlarge roundScore" value="<?= $team->getScore($num); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script src="js/jquery-1.7.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#tab li:first').addClass('active');
                $('#tab-content .tab-pane:first').addClass('active');

                $('.roundScore').keyup(function (e) {
                    if (e.keyCode == 13) {
                        var next = $(this).parent().parent().next().find('.roundScore');
                        next.focus().select();
                        if (next.find('.roundScore').length == 0)
                            $(this).blur();
                    }
                }).blur(function () {
                    var field = $(this);
                    $.ajax({
                        url: 'php/savescore.php',
                        type: 'post',
                        data: {team: $(this).data('team'), round: $(this).data('round'), score: $(this).val()},
                        dataType: 'json',
                        success: function (data) {
                            field.parent().parent().addClass('success');
                        },
                        error: function () {
                            field.parent().parent().addClass('error');
                        }
                    });
                });
            });
        </script>
    </body>
</html>