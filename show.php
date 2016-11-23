<?php
	include_once "koneksi.php";
?>

<html>
<head>
    <title>
        
    </title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="wrapper">
		<div id="sidebarDB">
			<center>
			<h2>phpMyAdmin</h2>
			<a href="index.php"><img src="image/home.png"></a>
			</center><br/>
			<?php
				include "show-database.php";
			?>
		</div>
		<div id="content">
			<?php
			@$database_name = $_GET['database_name'];
			
			$menu = "<a href=index.php><img src ='image/pma.png'> 127.0.0.0</a> . ";
			
			if(strlen($database_name)> 0 ){
				$menu .= " <a href=show.php?database_name=$database_name><img src ='image/data.png'> $database_name</a>";
			}
			echo "$menu";?>
			<br/><hr/>
			<h3>Create Table</h3>
<?php

//create table----

$fields = null;
$table = null;
$name = null;
$type = null;
$size = null;
$db = $database_name;

if(isset($_POST['field_submit'])) {
	$fields = $_POST['fields']; // 2
    $table =  $_POST['table']; // = login
} else if(isset($_POST['db_submit'])) {
	$name =   $_POST['name']; // user kkk
  	$table =  $_POST['table'];
  	$type =   $_POST['type'];
 	$size =   $_POST['size'];
  	$key = $_POST['key'];
  	$null = $_POST['null'];
}

if( !$fields and !$name and !$type) {
	$form ="<form method=\"post\">";
	$form.="How many fields are needed in the new table?<br>";

	$form.="Database:     <input type=\"text\" name=\"db\" value=\"$db\"><br>";
	$form.="Table Name:  <input type=\"text\" name=\"table\" size=\"10\"><br> "; // login
	$form.="Field: <input type=\"text\" name=\"fields\" size=\"5\">"; // 2
	$form.="<input type=\"submit\" name=\"field_submit\" value=\"Submit\">";
	echo($form);
}

//create colom ----
else if( !$name and !$type) { 
	echo "$database_name";

  	$form ="<form method=\"post\">";
	$form.="Table Name:  <input type=\"text\" name=\"table\" size=\"10\" value=\"$table\"><br> ";
	for ($i = 0 ; $i <$fields; $i++) {
	    $form.="Column Name:<input type=\"text\" name=\"name[$i]\" size=\"10\"> ";
	    $form.="Type: <select name=\"type[$i]\">";
	    $form.="<option value=\"char\">char</option>";	
	    $form.="<option value=\"varchar\">varchar</option>";
	    $form.="<option value=\"int\">int</option>";
	    $form.="<option value=\"float\">float</option>";
	    $form.="<option value=\"timestamp\">timestamp</option>";
	    $form.="</select> ";
	    $form.="Type: <select name=\"key[$i]\">";
	    $form.="<option value=\"\">-----</option>";
	    $form.="<option value=\"primary key\">primary key</option>";
	    $form.="</select> ";
	    $form.="Type: <select name=\"null[$i]\">";
	    $form.="<option value=\"\">-----</option>";
	    $form.="<option value=\"NOT NULL\">NOT NULL</option>";	
	    $form.="<option value=\"NULL\">NULL</option>";
	    $form.="</select> ";
	    $form.="Size:<input type=\"text\" name=\"size[$i]\" size=\"5\"><br>";
	}
		$form.=" <input type=\"submit\" name=\"db_submit\" value=\"Submit\"></form>";
	echo($form);
} else {
	$conn = mysql_connect("localhost", "root", "") or die("Could not connect.");
	echo "$database_name";
  	$rs = mysql_select_db($db, $conn) or die("Could not select database.");
	$num_columns = count($name);
	
	$sql = "create table $table (";
  	for ($i = 0; $i < $num_columns; $i++) {
    $sql .= "$name[$i] $type[$i]";
    if(($type[$i] =="char") or ($type[$i] =="varchar")) {
		if($size[$i] !="" ){ $sql.= "($size[$i])"; }
    } else if(($type[$i])=="int") {
    	if($size[$i] =""){ $sql.="($size[$i]=11)"; }
    }	//size 111 primary
    $sql .=" $key[$i] $null[$i]";
	if(($i+1) != $num_columns){ $sql.=","; }
	}
  	$sql .= ")";
	echo("SQL COMMAND: $sql <hr>");
	$result = mysql_query($sql,$conn) or die("Could not execute SQL query");
}

	if(isset($_GET['database_name'])) {
		$pdo->query("use `$database_name`") or header('Location: index.php');

			$dbs = $pdo->query("SHOW TABLES from `$database_name`");
			@$table_name=$_GET['table_name'];

			@$todo=$_GET['todo'];

			echo "<table>";
			echo "<tr><th>Tables</th><th colspan=2>Tindakan</th><th>Baris</th><th>Action</th></tr>";
					
					// hapus start
				if($todo=='hapus'){
					$re=$pdo->prepare("DROP TABLE IF EXISTS `$table_name`");
					$re->execute();

					$dbs = $pdo->query("SHOW TABLES from `$database_name`");

					while(($table = $dbs->fetchColumn( 0 ) ) !== false ) {	
						$numb = $pdo->query("select count(*) from $table")->fetchColumn();
						echo "<tr><td><a href=show-tables.php?database_name=$database_name&table_name=$table>$table</a></td>";
						echo "<td><a href=show.php?database_name=$database_name&table_name=$table&todo=kosongkan>Kosongkan</a></td>
				    		  <td><a href=show.php?database_name=$database_name&table_name=$table&todo=hapus>Hapus</a></td>";
				    	echo "<td>$numb</td>
				    	<td><a href=struktur.php?database_name=&database_name&table_name=$table>Struktur</a></td></tr>";
					}
				}

				while(($table = $dbs->fetchColumn( 0 ) ) !== false ) {
					echo "<tr><td><a href=show-tables.php?database_name=$database_name&table_name=$table>$table</a></td>";

					$pdo->query("use $database_name");
					// kosongkan start
					if($todo=='kosongkan'){
						$re=$pdo->prepare("truncate table $table_name");
						$re->execute();
					}
				    $numb = $pdo->query("select count(*) from $table")->fetchColumn();

				    echo "<td><a href=show.php?database_name=$database_name&table_name=$table&todo=kosongkan>Kosongkan</a></td>
				    	<td><a href=show.php?database_name=$database_name&table_name=$table&todo=hapus>Hapus</a></td>
					    <td>$numb</td>
				    	<td><a href=struktur.php?database_name=$database_name&table_name=$table>Struktur</a></td></tr>";
				}
				echo"</table>";					
			} else {
				header('Location : index.php');
			}
			?>		
		</div>
	</div>
</body>
</html>
