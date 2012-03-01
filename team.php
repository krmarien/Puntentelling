<?php 
require_once('php/config.php');
require_once('php/Teams.php');
require_once('php/Team.php');
require_once('php/Score.php');

pg_connect('host=localhost dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

$activeTab = 'overview';

if (isset($_POST['add']) || isset($_POST['addrepeat'])) {
	Teams::add(new Team(0, $_POST['number'], $_POST['name']));
	
	if (isset($_POST['addrepeat']))
		$activeTab = 'add';
} elseif (isset($_POST['remove'])) {
	Teams::remove(Teams::findOneById($_POST['id']));
} elseif (isset($_POST['save'])) {
	$team = Teams::findOneById($_POST['id']);
	$team->setName($_POST['name'])
		->setNumber($_POST['number']);
	Teams::save($team);
}

$teams = Teams::findAll();
if (sizeof($teams) > 0)
	$lastTeam = end($teams);
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
							<li class="active"><a href="team.php">Ploegen beheren</a></li>
							<li><a href="insertscore.php">Punten invoeren</a></li>
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
				<li <?= $activeTab == 'overview' ? 'class="active"' : ''; ?>><a href="#overview" data-toggle="tab">Overzicht</a></li>
				<li <?= $activeTab == 'add' ? 'class="active"' : ''; ?>><a href="#addTeam" data-toggle="tab">Ploeg toevoegen</a></li>
			</ul>
			<div class="tab-content">
				<div id="overview" class="tab-pane fade in <?= $activeTab == 'overview' ? 'active' : ''; ?>">
					<table class="table table-striped table-bordered">
						<tr>
							<th>Ploegnummer</th>
							<th>Ploegnaam</th>
							<th>Acties</th>
						</tr>
						<?php foreach($teams as $team): ?>
							<tr>
								<td><?= $team->getNumber(); ?></td>
								<td><?= $team->getName(); ?></td>
								<td>
									<button data-name="<?= $team->getName(); ?>" data-num="<?= $team->getNumber(); ?>" data-id="<?= $team->getId(); ?>" class="btn btn-primary edit">Bewerk</button>
									<button data-name="<?= $team->getName(); ?>" data-id="<?= $team->getId(); ?>" class="btn btn-danger remove">Verwijder</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div id="addTeam" class="tab-pane fade in <?= $activeTab == 'add' ? 'active' : ''; ?>">
					<form action="" method="post" class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="name">Ploeg naam</label>
							<div class="controls">
								<input type="text" class="input-xlarge" name="name" id="name">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="number">Ploeg nummer</label>
							<div class="controls">
								<input type="text" class="input-xlarge" name="number" id="number" value="<?= !isset($lastTeam) ? 1 : $lastTeam->getNumber()+1 ?>">
							</div>
						</div>
						<div class="form-actions">
							<input type="submit" name="add" class="btn btn-primary" value="Toevoegen">
							<input type="submit" name="addrepeat" class="btn btn-primary" value="Toevoegen en herhaal">
							<button class="btn">Annuleer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="removeTeam">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Verwijder ploeg</h3>
			</div>
			<div class="modal-body">
				<p>Ben je zeker dat je <i><span class="teamName"></span></i> wil verwijderen?</p>
			</div>
			<form action="" method="post">
				<input type="hidden" value="" name="id" class="teamId">
				<div class="modal-footer">
					<input type="submit" name="remove" class="btn btn-primary" value="Verwijder">
					<a href="#" class="btn cancel">Annuleer</a>
				</div>
			</form>
		</div>
		
		<div class="modal fade" id="editTeam">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Bewerk ploeg</h3>
			</div>
			<form action="" method="post" class="form-horizontal">
				<div class="modal-body">
					<p>
						<div class="control-group">
							<label class="control-label" for="name">Ploeg naam</label>
							<div class="controls">
								<input type="text" class="input-xlarge teamName" name="name">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="number">Ploeg nummer</label>
							<div class="controls">
								<input type="text" class="input-xlarge teamNumber" name="number">
							</div>
						</div>
					</p>
				</div>
				<input type="hidden" value="" name="id" class="teamId">
				<div class="modal-footer">
					<input type="submit" name="save" class="btn btn-primary" value="Opslaan">
					<a href="#" class="btn cancel">Annuleer</a>
				</div>
			</form>
		</div>
		
		<script src="js/jquery-1.7.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function () {
				$('#tab').tab();
				
				$('#overview .remove').click(function () {
					var removeTeam = $('#removeTeam');
					removeTeam.find('.teamName').html($(this).data('name'));
					removeTeam.find('.teamId').val($(this).data('id'));
					removeTeam.find('.cancel').click(function () {
						removeTeam.modal('hide');
					});
					removeTeam.modal();
				});
				
				$('#overview .edit').click(function () {
					var editTeam = $('#editTeam');
					editTeam.find('.teamName').val($(this).data('name'));
					editTeam.find('.teamNumber').val($(this).data('num'));
					editTeam.find('.teamId').val($(this).data('id'));
					editTeam.find('.cancel').click(function () {
						editTeam.modal('hide');
					});
					editTeam.modal();
				});
			});
		</script>
	</body>
</html>