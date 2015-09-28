<?php
require_once('php/config.php');

require_once('php/Teams.php');
require_once('php/Team.php');
require_once('php/Score.php');

pg_connect('host=localhost dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

$teams = Teams::findAllByRank(Config::$ENDSCOREORDER);

$filename = 'puntentelling_' . str_replace(' ', '_', strtolower(Config::$ORGANISATION));

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename . '.csv');

$output = fopen('php://output', 'w');

$header = array('#', 'Naam');
foreach(Config::$ROUNDS as $num => $round) {
    $header[] = $round;
}

fputcsv($output, $header, ';');

$rank = 1;
foreach($teams as $team) {
    $data = array(
        $rank++,
        $team->getName()
    );

    foreach(Config::$ROUNDS as $num => $round) {
        $data[] = $team->getScore($num);
    }

    fputcsv($output, $data, ';');
}
?>
