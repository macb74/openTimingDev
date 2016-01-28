<?php

function ziel() {
	global $func;
	$html="";

	if( isset($func[1])) {
		if( $func[1] == 'analyse' ) {
			zielAnalyseHeader();
		}
		if( $func[1] == 'edit' ) {
			zielEditForm();
		}
	}
}

function zielAnalyseHeader() {

	# Display Rennen
	//$html = "";
	$veranstaltung = $_SESSION['vID'];
	$sql = "select * from lauf where vID = $veranstaltung order by start asc, titel;";
	$result = dbRequest($sql, 'SELECT');

	?>
	<h3>Zieleinlauf Analyse</h3>
	
	<div class="table-responsive">
		<table class="table table-striped table-vcenter">
			<thead>
				<tr>
					<th>ID</th>
					<th>Titel</th>
					<th>Untertitel</th>
					<th>Start</th>
					<th></th>
				</tr>
			</thead>
		<tbody>
			
	<?php
	
	
	if($result[1] > 0) {
		foreach ($result[0] as $row) {
			$sql = "select count(ID) as anz from teilnehmer where del = 0 and vID = $veranstaltung and lID = ".$row['ID'];
			$resultCount = dbRequest($sql, 'SELECT');
			foreach ($resultCount[0] as $rowCount) {
				$anzTeilnehmer = $rowCount['anz'];
			}
				
			$subtitle = "";
			if ($row['untertitel'] != "") { $subtitle = "<i>- ".$row['untertitel']."</i>"; }
			
?>
			<tr>
				<td><?php echo $row['ID']; ?></td>
				<td><?php echo $row['titel']." ".$subtitle." (".$anzTeilnehmer.")"; ?></td>
				<td><?php echo $row['untertitel']; ?></td>
				<td><?php echo $row['start']; ?></td>
				<td><span>Start: </span><input class="inputStartAnalyseTime" id='startAnalyseTime_<?php echo $row['ID']; ?>' value='<?php echo $row['start'] ?>'>
					<span>Dauer: </span><input class="inputLengthAnalyseTime" id='duration_<?php echo $row['ID']; ?>' value='01:00:00'>
					<a id="<?php echo $row['ID']; ?>" class="zielanalyse" href="#" onclick="javascript:showZielzeitAnalyse(this); return false" >start Analyse</a>
				</td>
			</tr>
<?php 
		}
	}

?>
		</table>
	</div>

<?php
}

function showZielAnalyse() {
	
	$rennen = $_GET['id'];
	$start = $_GET['start'];
	$duration = $_GET['duration'];
	
	$sourceFile = 'getZielAnalyseData.php?lID='.$rennen.'&start='.$start.'&duration='.$duration;

?>
    <link href="css/timeline.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="js/d3.v3.min.js"></script>
    <script type="text/javascript" src="js/timeline.js"></script>
    
	<div id="timeline"></div>

<script>

    /*  You need a domElement, a sourceFile and a timeline.

        The domElement will contain your timeline.
        Use the CSS convention for identifying elements,
        i.e. "div", "p", ".className", or "#id".

        The sourceFile will contain your data.
        If you prefer, you can also use tsv, xml, or json files
        and the corresponding d3 functions for your data.


        A timeline can have the following components:

        .band(bandName, sizeFactor
            bandName - string; the name of the band for references
            sizeFactor - percentage; height of the band relation to the total height
            Defines an area for timeline items.
            A timeline must have at least one band.
            Two bands are necessary, to change the selected time interval.
            Three and Bands are allowed.

        .xAxis(bandName)
            bandName - string; the name of the band the xAxis will be attached to
            Defines an xAxis for a band to show the range of the band.
            This is optional, but highly recommended.

        .labels(bandName)
            bandName - string; the name of the band the labels will be attached to
            Shows the start, length and end of the range of the band.
            This is optional.

        .tooltips(bandName)
            bandName - string; the name of the band the labels will be attached to
            Shows current start, length, and end of the selected interval of the band.
            This is optional.

        .brush(parentBand, targetBands]
            parentBand - string; the band that the brush will be attached to
            targetBands - array; the bands that are controlled by the brush
            Controls the time interval of the targetBand.
            Required, if you want to control/change the selected time interval
            of one of the other bands.

        .redraw()
            Shows the initial view of the timeline.
            This is required.

        To make yourself familiar with these components try to
        - comment out components and see what happens.
        - change the size factors (second arguments) of the bands.
        - rearrange the definitions of the components.
    */

    

    // Define domElement and sourceFile
    var domElement = "#timeline";
    var sourceFile = "<?php echo $sourceFile; ?>";

    // Read in the data and construct the timeline
    d3.csv(sourceFile, function(dataset) {

        timeline(domElement)
            .data(dataset)
            .band("mainBand", 0.6)
            .band("naviBand", 0.20)
            .xAxis("mainBand")
            .tooltips("mainBand")
            .xAxis("naviBand")
            .labels("mainBand")
            .labels("naviBand")
            .brush("naviBand", ["mainBand"])
    		.setColor()
            .redraw();

    });

</script>

<?php 
}

function zielEditForm() {
	$html = "";
	$html .="<div class=\"vboxitem grey-bg\" >\n";
	
	
	$html .="</div>\n";

	return $html;
}

function showZielEditTable() {
		
	$html = "";
	$sql = "SELECT * FROM zeit where vID = ".$_SESSION['vID'];
	$result = dbRequest($sql, 'SELECT');
	
	$html2 = "";
	$i=1;
	if($result[1] > 0) {
		foreach ($result[0] as $row) {
			while(strlen($row['millisecond']) < 3 ) { $row['millisecond'] = "0".$row['millisecond']; }
				
			if($i%2 == 0) { $html2 .= "<tr class=\"even highlight\">\n"; } else { $html2 .= "<tr class=\"odd highlight\">\n"; }
			$html2 .= "<td align=\"left\">".$row['vID']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['lID']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['nummer']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['zeit'].".".$row['millisecond']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['TIMESTAMP']."</td>\n";
			$html2 .= "<td align=\"left\">".$row['Reader']."</td>\n";
			$html2 .= "<td align=\"left\"><a class=\"\" id=\"".$row['ID']."\" href=\"#\" onclick=\"javascript:saveReaderTime('id=".$row['ID']."&action=del&values=none');\"><i class=\"fa fa-times fa-lg\"></i></a></span></td>\n";
			$html2 .= "</tr>\n";
			$i++;
		}
	}
	
	$columns = array('vID', 'lID', 'StNr.', 'Zeit', 'TIMESTAMP', 'Reader', 'Action');
	$html .= tableList($columns, $html2, "common");
	
	return $html;
}

function saveReaderTime($id, $action, $values) {
	if($action == 'del') {
		
	}

	if($action == 'save') {
	
	}

	return showZielEditTable();
}

?>