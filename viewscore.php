<?php 
require_once('php/config.php');
require_once('php/Teams.php');
require_once('php/Team.php');
require_once('php/Score.php');

pg_connect('dbname=' . Config::$DATABASE . ' user=' . Config::$USER . ' password=' . Config::$PASSWORD . '');

if (isset($_GET['untill'])) {
	$untill = $_GET['untill'];
} else {
	$untill = key(array_slice(Config::$ROUNDS, -1, 1, TRUE));
}

$teams = Teams::findAllByRank('DESC', $untill);
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Puntentelling <?= Config::$ORGANISATION; ?></title>
		<link rel="stylesheet" href="stylesheet/bootstrap.min.css">
		<link rel="stylesheet" href="stylesheet/style.css">
		<style type="text/css">
			thead.fixedHeader {
				display: block;
			}
			tbody.scrollContent {
				overflow: auto;
				display: block;
			}
			table .rank {
				width: 50px;
			}
			table .name {
				width: 140px;
				overflow: hidden;
			}
			table .round {
				width: 20px;
				text-align: center;
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
							<li class="active"><a href="viewscore.php">Punten bekijken</a></li>
							<li><a href="endscore.php">Eindstand</a></li>
						</ul>
					</div>
				</div>
			</div>
			<header class="subhead">
				<h1>Puntentelling <?= Config::$ORGANISATION; ?></h1>
			</header>
			<div style="float: left;margin-bottom: 5px;">
				<select id="showUntill">
					<option value="0">---</option>
					<?php foreach(Config::$ROUNDS as $num => $round): ?>
						<option value="<?= $num; ?>" <?= $untill == $num ? 'selected' : ''; ?>><?= $round; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div style="float: right;margin-bottom: 5px;">
				<button id="fullScreen" class="btn btn-primary">Full Screen</button>
			</div>
			<div id="content" style="clear: both; padding: 5px;">
				<table id="ranking" class="table table-striped table-bordered">
					<thead class="fixedHeader">
						<tr>
							<th class="rank">Ranking</th>
							<th class="name">Ploegnaam</th>
							<?php foreach(Config::$ROUNDS as $num => $round): ?>
								<th class="round"><?= $round; ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody class="scrollContent">
						<?php 
						$rank = 1;
						foreach($teams as $team): ?>
							<tr>
								<td class="rank"><?= ($rank++); ?></td>
								<td class="name"><?= $team->getName(); ?></td>
								<?php foreach(Config::$ROUNDS as $num => $round): ?>
									<td class="round"><?= $team->getScore($num); ?></td>
								<?php endforeach; ?>
							</tr>
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
				$('#showUntill').change(function () {
					window.location.href = 'viewscore.php?untill=' + $(this).val();
				});
				
				$('#ranking tr').each(function () {
					$(this).find('td:lt(' + (<?= $untill + 2; ?>) + '), th:lt(' + (<?= $untill + 2; ?>) + ')').show();
					$(this).find('td:gt(' + (<?= $untill + 1; ?>) + '), th:gt(' + (<?= $untill + 1; ?>) + ')').hide();
				});
				
				if($.support.fullscreen){
					$('#fullScreen').click(function(e){
						$('#content').fullScreen({
							background: '#fff',
							callback: function (isFullScreen) {
								if (isFullScreen) {
									setRoundWidth();
									$('#ranking tbody').css('max-height', $('#content').parent().height() - $('#ranking thead').height() - $('#content').css('padding-top').replace("px", "") * 2 - $('#ranking').css('margin-bottom').replace("px", "") - 2);
									$('#ranking').autoScroll();
								} else {
									setRoundWidth();
									$('#ranking tbody').css('max-height', '');
									$('#ranking').autoScroll('destroy');
								}
							}
						});
					});
				} else {
					$('#fullScreen').hide();
				}
				setRoundWidth();
			});
			
			function setRoundWidth() {
				$('#ranking .round').width(
						($('#ranking').width() 
							- $('#ranking .rank:first').width() - $('#ranking .rank:first').css('padding-left').replace("px", "") * 2
							- $('#ranking .name:first').width() - $('#ranking .name:first').css('padding-left').replace("px", "") * 2
						) / <?= $untill; ?> - $('#ranking .round:first').css('padding-left').replace("px", "") * 2
					);
			}
		</script>
	</body>
</html>