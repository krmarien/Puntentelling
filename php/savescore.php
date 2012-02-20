<?php
require_once('config.php');
require_once('Teams.php');
require_once('Team.php');
require_once('Score.php');

pg_connect('dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

$team = Teams::findOneById($_POST['team']);

if ($_POST['round'] == 'separation') {
	$round = 'separation';
} else {
	$round = 'round' . $_POST['round'];
}

if (!is_numeric($_POST['score']) || $_POST['score'] == '')
	throw Exception();

pg_query('UPDATE ' . Config::$TBL . ' SET ' . $round . '=' . $_POST['score'] . ' WHERE id = ' . $team->getId());