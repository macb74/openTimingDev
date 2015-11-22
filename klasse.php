<?php
/*
 * Created on 22.11.2015
 *
 */

function klasse() {

	if (!isset($_GET['id'])) {
		showKlassen();
	} else {
		showKlasseEditForm();
	}

}	

function showKlassen() {

?>
	<h3>Klassen</h3>
	<a type="button" href="index.php?func=klasse&id=new" class="btn btn-success pull-right btn-new-top">neue Altersklassen</a>
	
		
	<div class="table-responsive">
		<table class="table table-striped table-vcenter">
			<thead>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
		<tbody>

<?php 
	
	$sql = "select * from klasse order by name asc;";
	$result = dbRequest($sql, 'SELECT');
	
	if($result[1] > 0) {
		foreach ($result[0] as $row) {

?>

			<tr>
				<td><?php echo $row['name']; ?></td>
				<td><a type="button" class="btn btn-default btn-small-border" href="<?php echo $_SERVER["REQUEST_URI"]."&id=".$row['ID']; ?>"><i class="fa fa-wrench"></i></a></td>
			</tr>					

<?php

		}
	}
	
?>

		</tbody>
	</table>

<?php 

}
	
function showKlasseEditForm() {

	if ($_GET['id'] != "new") {

		$sql = "select * from klasse where ID = ".$_GET['id'];
		$result = dbRequest($sql, 'SELECT');

		if($result[1] > 0) {
			foreach ($result[0] as $row) {
				$name = $row['name'];
				$kID = $row['ID'];
			}
		}
		
?>

	<h3>Klassen</h3>
	<a type="button" href="#" onclick="javascript:addKlasseZeile(<?php echo $_GET['id']; ?>); return true;" class="btn btn-success pull-right btn-new-top">neue Zeile</a>
	
	<div class="alert alert-danger hidden col-sm-offset-3 col-sm-6" role="alert"></div>
	<form role="form" class="form-horizontal" id="editKlasse" name="editKlasse">
		<div class="form-group">
			<input type="hidden" name="form" value="saveKlasse">
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		</div>
		
		<div class="form-group">
			<label for="name" class="col-sm-4 control-label">Name:</label>
			<div class="col-sm-5">
				<input name="name" maxlength="200" type="text" class="form-control" id="name" placeholder="Name" value="<?php echo $name; ?>">
			</div>
		</div>

		<div class="col-sm-offset-3 col-sm-7">
		<table class="table table-striped table-vcenter">
			<thead>
				<tr>
					<th>Name</th>
					<th>Geschlecht</th>
					<th>Alter von</th>
					<th>Alter bis</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			
<?php 

		$sql = "select * from klasse_data where kID = $kID order by name";
		$result = dbRequest($sql, 'SELECT');

		$i= 0;
		if($result[1] > 0) {
			foreach ($result[0] as $row) {

?>
				<tr>
					<td><input name="name<?php echo $i; ?>" type="text" class="form-control input-sm" id="name<?php echo $i; ?>" placeholder="Name" value="<?php echo $row['name']; ?>"></td>
					<td><input name="gender<?php echo $i; ?>" type="text" class="form-control input-sm" id="gender<?php echo $i; ?>" placeholder="Geschlecht" value="<?php echo $row['geschlecht']; ?>"></td>
					<td><input name="altervon<?php echo $i; ?>" type="text" class="form-control input-sm" id="altervon<?php echo $i; ?>" placeholder="von" value="<?php echo $row['altervon']; ?>"></td>
					<td><input name="alterbis<?php echo $i; ?>" type="text" class="form-control input-sm" id="alterbis<?php echo $i; ?>" placeholder="bis" value="<?php echo $row['alterbis']; ?>"></td>
					<td><a type="button" class="btn btn-default" onclick="javascript:deleteKlasse(<?php echo $row['ID'].", ".$_GET['id'];?>)" href="#"><i class="fa fa-trash"></i></a></td>
				</tr>

<?php
				$i++;
			}
		}

?>
			</tbody>
		</table>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-5">
			&nbsp;&nbsp;&nbsp;<button type="submit" id="submit" class="btn btn-success">save</button>
			<a type="button" class="btn btn-default" href="index.php?func=klasse">cancel</a>
		</div>
	</div>
	
	</form>	
<?php
		
	}
}

function saveKlasse() {

	
}

function addKlasse() {

	$name = " Name";
	$geschlecht = "X";
	$altervon = 0;
	$alterbis = 0;
	$sql = "insert into klasse_data " .
			"(kID, name, geschlecht, altervon, alterbis) " .
			"values ( ".$_GET['id'].", '$name', '$geschlecht', $altervon, $alterbis)";
	$result = dbRequest($sql, 'INSERT');
	
	if($result[2] == "") {
		echo 'ok';
	} else {
		echo $result[2];
	}

}

function deleteKlasse() {
	$sql = "delete from klasse_data where ID = ".$_GET['id'];
	$result = dbRequest($sql, 'DELETE');
	
	if($result[2] == "") {
		echo 'ok';
	} else {
		echo $result[2];
	}
}


// 		// Neuanlage einer Klasse
// 		if ($func[1] == "insert") {
// 			$sql = "insert into klasse (name) value ('neue Klasse')";
// 			$result = dbRequest($sql, 'INSERT');
// 			$kID = $result[3];
// 			//echo "KID: ".$kID;			
// 			$newKlass = true;
// 		}

// 		// update einer bestehenden Klasse
// 		if ( isset($_POST['submit']) && ($_POST['submit'] == "Speichern" || $_POST['submit'] == "neue Zeile")) {

// 			$sql = "update klasse set name = '".htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8')."' where ID = ".$_POST['kID'];
// 			$result = dbRequest($sql, 'UPDATE');

// 			// update der klasse_data
// 			$i = 0;
// 			$count = $_POST['count'] - 1;
// 			while ($i <= $count) {
// 				$kdID = 0; $n = 0; $g = ""; $v = 0; $b = 0;
				
// 				$kdID = $_POST['kdID'.$i];
// 				$n = $_POST['name'.$i];
// 				$g = strtoupper($_POST['gender'.$i]);
// 				$v = $_POST['altervon'.$i];
// 				$b = $_POST['alterbis'.$i];
// 				$sql = "update klasse_data set name = '$n', geschlecht = '$g', altervon = $v, alterbis = $b where ID = $kdID";
// 				$result = dbRequest($sql, 'UPDATE');
// 				if (!$result[0]) { die('update klasse_data - Invalid query: ' . $result[2]); }
// 				$i++;
// 			}
				
// 		}

// 		// einfügen einer neuen Zeile
// 		if( isset($_POST['submit']) && ($_POST['submit'] == "neue Zeile" || $newKlass == true)) {
// 			if( $newKlass == true ) { $kID = $kID; } else { $kID = $_POST['kID']; }

// 			$name = "Name";
// 			$geschlecht = "x";
// 			$altervon = 0;
// 			$alterbis = 0;

// 			$sql = "insert into klasse_data " .
// 					"(kID, name, geschlecht, altervon, alterbis) " .
// 					"values ( $kID, '$name', '$geschlecht', $altervon, $alterbis)";
// 			$result = dbRequest($sql, 'INSERT');
// 			if (!$result[0]) { echo $sql; die('Invalid query: ' . $result[2]); }
// 		}

// 		// Rücksprung bei neuer Zeile
// 		if(isset($_POST['submit']) && $_POST['submit'] == "neue Zeile") {
// 			$script = $_POST['nextUrl'];
// 			header('Location: '.$script);
// 			die;
// 		}

// 		// Rücksprung bei neuer Klasse
// 		if($newKlass == true) {
// 			$script = $_SERVER["SCRIPT_NAME"]."?func=".$func[0].".edit&ID=$kID";
// 			header('Location: '.$script);
// 			die;
// 		}



	

// 	# display Form
// 	if ($func[1] == "edit" || $func[1] == "insert") {

// 		if($func[1] == "edit") {
// 			$sql = "select * from klasse where ID = ".$_GET['ID'];
// 			$result = dbRequest($sql, 'SELECT');
			
// 			if($result[1] > 0) {
// 				foreach ($result[0] as $row) {
// 					$name = $row['name'];
// 					$kID = $row['ID'];
// 				}
// 			}
// 		}

// 		$nextUrl = $_SERVER['REQUEST_URI'];
// 		$html  ="<form name=\"editVeranstaltungen\" method=\"POST\" action=\"?func=klasse\">\n";
// 		$html .="<input name=\"func\" type=\"hidden\" value=\"$func[1]\">\n";
// 		$html .="<input name=\"kID\" type=\"hidden\" value=\"$kID\">\n";
// 		$html .="<input name=\"nextUrl\" type=\"hidden\" value=\"$nextUrl\">\n";
// 		$html .="<div class=\"vboxitem\" >\n";
// 		$html .="	<span class=\"description\" >\n";
// 		$html .="		Hier k&ouml;nnen Sie die Klassendefinition eingeben. Felder mit einem * sind Pflicht.\n";
// 		$html .="	</span>\n";
// 		$html .="</div>\n";
// 		#$html .="    <p class=\"vboxspacer\">&nbsp;</p>\n";
// 		$html .="<div class=\"vboxitem\" >\n";
// 		$html .="	<table class=\"grey-bg\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >\n";
// 		$html .="		<tr class=\"top-row\" >\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Name*:\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"rightcolumn\" >\n";
// 		$html .="				<input type=\"text\" name=\"name\" maxlength=\"200\" size=\"50\" value=\"$name\"/>\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"errorcolumn\" ></td>\n";
// 		$html .="		</tr>\n";

// 		$html .="		<tr class=\"whiteBg\">\n";
// 		$html .="			<td>\n";
// 		$html .="				&nbsp;\n";
// 		$html .="			</td>\n";
// 		$html .="			<td>\n";
// 		$html .="				&nbsp;\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"errorcolumn\" ></td>\n";
// 		$html .="		</tr>\n";
// 		$html .="	</table>\n";

// 		$html .="	<table class=\"grey-bg\" width=\"50%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >\n";

// 		$html .="		<tr class=\"top-row\" >\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Name*:\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Geschlecht*:\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Alter von*:\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Alter bis*:\n";
// 		$html .="			</td>\n";
// 		$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 		$html .="				Aktion:\n";
// 		$html .="			</td>\n";
// 		$html .="		</tr>\n";

// 		$sql = "select * from klasse_data where kID = $kID order by geschlecht, name";
// 		$result = dbRequest($sql, 'SELECT');
		
// 		$i= 0;
// 		if($result[1] > 0) {
// 			foreach ($result[0] as $row) {
// 				$html .="		<tr class=\"top-row\" >\n";
// 				$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 				$html .="				<input type=\"hidden\" name=\"kdID$i\" value=\"".$row['ID']."\"></input>\n";
// 				$html .="				<input type=\"text\" name=\"name$i\" value=\"".$row['name']."\"></input>\n";
// 				$html .="			</td>\n";
// 				$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 				$html .="				<input type=\"text\" name=\"gender$i\" value=\"".$row['geschlecht']."\"></input>\n";
// 				$html .="			</td>\n";
// 				$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 				$html .="				<input type=\"text\" name=\"altervon$i\" value=\"".$row['altervon']."\"></input>\n";
// 				$html .="			</td>\n";
// 				$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 				$html .="				<input type=\"text\" name=\"alterbis$i\" value=\"".$row['alterbis']."\"></input>\n";
// 				$html .="			</td>\n";
// 				$html .="			<td class=\"leftcolumn\" nowrap >\n";
// 				$html .="				<a href=\"".$_SERVER["SCRIPT_NAME"]."?func=klasse.delete.kldata&ID=".$row['ID']."&nextUrl=".base64_encode($_SERVER["REQUEST_URI"])."\">delete</a>";
// 				$html .="			</td>\n";
// 				$html .="		</tr>\n";
// 				$i++;
// 			}
// 		}

// 		$html .="	</table>\n";
// 		$html .="</div>\n";


// 		$html .="<div class=\"vboxitem\" >\n";
// 		$html .="	<div class=\"navigation-buttons\" >\n";
// 		$html .="		<input name=\"count\" type=\"hidden\" value=\"$i\">\n";
// 		$html .="		<input type=\"button\" name=\"cancel\" value=\"<< Zur&uuml;ck\" class=\"button\" ONCLICK=\"window.location.href='".$_SERVER["SCRIPT_NAME"]."?func=klasse'\">\n";
// 		$html .="		&nbsp;&nbsp;\n";
// 		$html .="		<input name=\"submit\" type=\"submit\" value=\"Speichern\" class=\"button\">\n";
// 		$html .="		&nbsp;&nbsp;\n";
// 		$html .="		<input name=\"submit\" type=\"submit\" value=\"neue Zeile\" class=\"button\">\n";
// 		$html .="	</div>\n";
// 		$html .="</div>\n";
// 		$html .="</form>\n";

// 	} else {
// 		# Display Rennen
// 		$html = "";
// 		$sql = "select * from klasse order by name asc;";
// 		$result = dbRequest($sql, 'SELECT');

// 		$html2 = "";
// 		$i=1;
// 		if($result[1] > 0) {
// 			foreach ($result[0] as $row) {
// 				if($i%2 == 0) { $html2 .= "<tr class=\"even\">\n"; } else { $html2 .= "<tr class=\"odd\">\n"; }
	
// 				$html2 .= "<td align=\"left\">".$row['name']."</td>\n";
// 				$html2 .= "<td align=\"left\">" .
// 						"<a href=\"".$_SERVER["REQUEST_URI"].".edit&ID=".$row['ID']."\">edit</a>" .
// 						"&nbsp;&nbsp;" .
// 						"</td>\n";
// 				$html2 .= "</tr>\n";
// 				$i++;
// 			}
// 		}

// 		$columns = array('Name', 'Aktion');
// 		$html .= tableList($columns, $html2, "common meetings");

// 		$html .="<br><div class=\"vboxitem\" >\n";
// 		$html .="	<div class=\"navigation-buttons\" >\n";
// 		$html .="		<input type=\"submit\" value=\"neue Klasse\" class=\"button\" ONCLICK=\"window.location.href='".$_SERVER["REQUEST_URI"].".insert'\">\n";
// 		$html .="	</div>\n";
// 		$html .="</div>\n";

// 	}
// }
?>
