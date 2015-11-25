<?php

function showErgebnisse() {

	$rennen = $_GET['id'];
	$rd = getRennenData($rennen);
	$sqlAddOn = "";
	if ($rd['rundenrennen'] == 1) { $sqlAddOn = "runden desc, "; }
	
	$html = "<br>";
	$html = "<p><a href=\"#\" onClick=\"clearDiv()\">clear</a></p>";
	$sql = "SELECT t.*, l.titel FROM `teilnehmer` as t INNER JOIN lauf as l ON t.lID = l.ID ".
		"where t.vID = ".$_SESSION['vID']." ".
			"and t.lid = $rennen and del= 0 and disq = 0 and platz > 0 ".
			"order by $sqlAddOn zeit, platz asc;";
	$result = dbRequest($sql, 'SELECT');
		
	$html2 = "";
	$i=1;
	$dataSetBefore['zeit'] = 'none';
	$dataSetBefore['klasse'] = 'none';
	
	$sameTimeAsBefore ='';

?>
	
	<h3 class="sub-header">Ergebnisliste&nbsp;&nbsp;<a class="btn btn-default btn-small-border"><i class="fa fa-eraser"></i></a></h3>
	<div class="table-responsive">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Rng.</th>
					<th>Stnr.</th>
					<th>Name</th>
					<th>Verein</th>
					<?php if ($rd['rundenrennen'] == 0) { echo "<th>JG</th>"; } ?>
					<?php if ($rd['rundenrennen'] == 0) { echo "<th>G</th>"; } ?>
					<th>Klasse</th>
					<th>Rennen</th>
					<?php if ($rd['rundenrennen'] != 0) { echo "<th>Runden</th>"; } ?>
					<th>Zeit</th>
					<th>Platz</th>
					<th>AK</th>
					<th>Urkunde</th>
				</tr>
			</thead>
			<tbody>

<?php
	
	
	if($result[1] > 0) {
		foreach ($result[0] as $row) {
			$sameTimeAsBefore = '';
			
			if($row['useManTime'] == 1 ) { $umt = '*'; } else { $umt = ''; }
			if($row['man_runden'] != 0 ) { $mr = '*'; } else { $mr = ''; }
			
// 			if($i%2 == 0) { $html2 .= "<tr class=\"even\">\n"; } else { $html2 .= "<tr class=\"odd\">\n"; }
// 			$html2 .= "<td align=\"left\">".$i."</td>\n";
// 			$html2 .= "<td align=\"left\">".$row['stnr']."</td>\n";
// 			$html2 .= "<td align=\"left\"><a href=\"".$_SERVER["SCRIPT_NAME"]."?func=teilnehmer.edit&ID=".$row['ID']."&nextUrl=".base64_encode($_SERVER["SCRIPT_NAME"]."?func=ergebnis")."\">".$row['nachname'].", ".$row['vorname']."</a></td>\n";
// 			$html2 .= "<td align=\"left\">".$row['verein']."</td>\n";
// 			if ($rd['rundenrennen'] == 0) { $html2 .= "<td align=\"left\">".$row['jahrgang']."</td>\n"; }
// 			if ($rd['rundenrennen'] == 0) { $html2 .= "<td align=\"left\">".$row['geschlecht']."</td>\n"; }
// 			$html2 .= "<td align=\"left\">".$row['klasse']."</td>\n";
// 			$html2 .= "<td align=\"left\">".$row['titel']."</td>\n";
// 			if ($rd['rundenrennen'] != 0) { $html2 .= "<td align=\"left\">".$row['runden'].$mr."</td>\n"; }
// 			if (($dataSetBefore['zeit'] == $row['zeit']) && ($dataSetBefore['klasse'] == $row['klasse'])) { $sameTimeAsBefore = 'style="font-weight:bold"'; } else { $sameTimeAsBefore = ''; }
// 			$html2 .= "<td align=\"left\" $sameTimeAsBefore >".$row['zeit'].$umt."</td>\n";
// 			$html2 .= "<td align=\"left\">".$row['platz']."</td>\n";
// 			$html2 .= "<td align=\"left\">".$row['akplatz']."</td>\n";
// 			$html2 .= "<td align=\"left\"><a href=\"urkundenPDF.php?action=einzel&tid=".$row['ID']."\" target=\"_new\">Urkunde</a></td>\n";
	
			if (($dataSetBefore['zeit'] == $row['zeit']) && ($dataSetBefore['klasse'] == $row['klasse'])) { 
				$sameTimeAsBefore = 'style="font-weight:bold"';
			} 

?>
			
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['stnr']; ?></td>
					<td><a href="index.php?func=teilnehmer&id=<?php echo $row['ID'] ?>"><?php echo $row['nachname'].", ".$row['vorname']; ?></a></td>
					<td><?php echo $row['verein']; ?></td>
					<?php if ($rd['rundenrennen'] == 0) { echo "<td>".$row['jahrgang']."</td>"; } ?>
					<?php if ($rd['rundenrennen'] == 0) { echo "<td>".$row['geschlecht']."</td>"; } ?>
					<td><?php echo $row['klasse']; ?></td>
					<td><?php echo $row['titel']; ?></td>
					<?php if ($rd['rundenrennen'] != 0) { echo "<td>".$row['runden'].$mr."</td>"; } ?>
					<td <?php echo $sameTimeAsBefore; ?>><?php echo $row['zeit'].$umt; ?></td>
					<td><?php echo $row['platz']; ?></td>
					<td><?php echo $row['akplatz']; ?></td>
					<td><a href="urkundenPDF.php?action=einzel&tid=<?php echo $row['ID']; ?>" target="_new">Urkunde</a></td>
				</tr>
			
<?php	
			
			
			$dataSetBefore['zeit'] = $row['zeit'];		
			$dataSetBefore['klasse'] = $row['klasse'];
			
			$html2 .= "</tr>\n";
			$i++;
		}
	}
	
?>

			</tbody>
		</table>
	</div>
	
<?php 
}

function showErgebnisseM() {
	
	$rennen = $_GET['id'];
	$html = "<br>";
	$html = "<p><a href=\"#\" onClick=\"clearDiv()\">clear</a></p>";
	$sql = "SELECT t.verein, t.vnummer, t.vtime, t.vplatz, t.vklasse FROM `teilnehmer` as t ".
		"where t.vID = ".$_SESSION['vID']." ".
			"and t.lid = $rennen and del= 0 and disq = 0 and vplatz > 0 ".
			"group by vnummer order by vtime asc, vnummer";

	$result = dbRequest($sql, 'SELECT');
		
	$html2 = "";
	$i=1;
	
	if($result[1] > 0) {
		foreach ($result[0] as $row) {
	
			if($i%2 == 0) { $html2 .= "<tr class=\"even\">\n"; } else { $html2 .= "<tr class=\"odd\">\n"; }
	
			$vnummer = $row['vnummer'];
			$sql2 = "SELECT nachname, vorname, zeit from teilnehmer " .
					"where lid = $rennen and del= 0 and disq = 0 and vnummer = '$vnummer' order by zeit";
			$res2 = dbRequest($sql2, 'SELECT');
			
			$html2 .= "<td align=\"left\">".$i."</td>\n";
			$html2 .= "<td align=\"left\">".$row['verein']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['vtime']."</td>\n";
			$html2 .= "<td align=\"left\"><table border='0' cellspacing='0' >";
			foreach ($res2[0] as $row2) {
				$html2 .= "<tr><td width='200'>".$row2['nachname'].", ".$row2['vorname']."</td><td>".$row2['zeit']."</td></tr>";
			}
			$html2 .= "</table></td>\n";
			$html2 .= "<td align=\"left\">".$row['vklasse']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['vplatz']."</td>\n";
			$html2 .= "</tr>\n";
	
			$i++;
		}
	}
	$columns = array('Platz', 'Verein', 'Zeit', 'Name', 'Klasse', 'AK');
	$html .= tableList($columns, $html2, "common");
	
	echo $html;
}
