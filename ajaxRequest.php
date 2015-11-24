<?php

session_start();
include "function.php";
$link = connectDB();
$_GET = filterParameters($_GET);
$_POST = filterParameters($_POST);

if(isset($_GET['func'])) {
	if($_GET['func'] == 'selectVeranstaltung')		{ selectVeranstaltung($_GET['id']); }
	if($_GET['func'] == 'lockRace')           		{ lockRace($_GET['lid']); }
	if($_GET['func'] == 'addKlasse')           		{ addKlasse($_GET['id']); }
	if($_GET['func'] == 'deleteKlasse')           	{ deleteKlasse($_GET['id']); }
	if($_GET['func'] == 'deleteFullKlasse')			{ deleteFullKlasse(); }
	if($_GET['func'] == 'getVerein')				{ getVerein(); }
}

if(isset($_POST['form'])) {
	if($_POST['form'] == 'saveVeranstaltung')		{ saveVeranstaltung(); }
	if($_POST['form'] == 'saveRennen')				{ saveRennen(); }
	if($_POST['form'] == 'saveKlasse')				{ saveKlasse(); }
	if($_POST['form'] == 'saveTeilnehmer')			{ saveTeilnehmer(); }
}


//phpinfo(32);

/*
if($_GET['func'] == 'showStartList')      { $html = showStartResult($_GET['lid']); echo $html;}
if($_GET['func'] == 'showStartWithoutKl') { $html = showStartWithoutKl($_GET['lid']); echo $html;}
if($_GET['func'] == 'showResult')         { $html = showResult($_GET['lid']); echo $html;}
if($_GET['func'] == 'showResultM')        { $html = showResultM($_GET['lid']); echo $html;}
if($_GET['func'] == 'showWithowtTime')    { $html = showWithowtTime($_GET['lid']); echo $html;}
if($_GET['func'] == 'showEinlaufListe')   { $html = showEinlaufListe($_GET['lid'], $_GET['action']); echo $html;}
if($_GET['func'] == 'saveManZielzeit')    { $html = saveManZielzeit($_GET['id'], $_GET['action'], $_GET['time']); echo $html;}
if($_GET['func'] == 'getKlasse')          { $html = getKlasse($_GET['jg'], $_GET['sex'], $_GET['lid'], 1); echo $html;}
if($_GET['func'] == 'showZielAnalyse')    { $html = showZielAnalyse($_GET['lid'], $_GET['start'], $_GET['duration']); echo $html;}
if($_GET['func'] == 'saveReaderTime')     { $html = saveReaderTime($_GET['id'], $_GET['action'], $_GET['values']); echo $html;}
*/

$link->close();
exit;

function selectVeranstaltung( $id ) {
	$_SESSION['vID'] = $id;
	$_SESSION['rID'] = "";
	
	$sql = "select * from veranstaltung where id = $id";
	$result = dbRequest($sql, 'SELECT');

	foreach ($result[0] as $row) {
		$_SESSION['vTitel']      = $row['titel'];
		$_SESSION['vUntertitel'] = $row['untertitel'];
		$_SESSION['vDatum']      = $row['datum'];
	}
	
	echo $_SESSION['vTitel'];
}

function getVerein() {
	$link = connectDB();
	$term = htmlspecialchars($_GET['term'], ENT_QUOTES, 'UTF-8');
	$term = trim(strip_tags($_GET['term']));
	$a_json = array();
	
	$sql = "select distinct verein from teilnehmer where verein LIKE '$term%'";
	$result = dbRequest($sql, 'SELECT');
	
	if($result[1] > 0) {
		$i = 0;
		foreach ($result[0] as $row) {
			$verein = stripslashes($row['verein']);
			$a_json[$i] = $verein;
			$i++;
		}
	
		echo json_encode($a_json);
		flush();
	}
	$link->close();
}
?>