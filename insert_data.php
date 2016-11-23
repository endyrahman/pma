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
			@$database_name = $_GET['database_name'];//popoji
			@$table_name=$_GET['table_name'];//menu

			$menu = "<a href=index.php><img src ='image/pma.png'> 127.0.0.0</a> . ";
			
			if(strlen($database_name)> 0 ){
				$menu .= " <a href=show.php?database_name=$database_name><img src ='image/data.png'> $database_name</a>";
			}
			if(strlen($table_name)> 0 ){
			$menu .= " . <a href=show-tables.php?database_name=$database_name&table_name=$table_name> <img src ='image/tbl.png'> $table_name</a>";
			}
			echo "$menu";
			?>
			<br/><hr/>
			<?php
			//show table
			//--start--
			@$table_name=$_GET['table_name'];
			@$dt = $_GET['data'];
			@$todo=$_GET['todo'];
			if(isset($_GET['table_name']) && isset($_GET['database_name'])){
				$pdo->query("use `$database_name`");
				if($todo=='insert'){
					echo "<br/><hr/><table>";
					echo "<tr><th>Name</th><th>Type</th><th>Key</th><th>Is Null</th><th>Default</th></tr>";
					// mengambil informasi dari $table_name dan $table_schema sesuai url
					$select = $pdo->prepare("SELECT COLUMN_NAME,COLUMN_TYPE, COLUMN_KEY, IS_NULLABLE, COLUMN_DEFAULT
					  FROM INFORMATION_SCHEMA.COLUMNS
					  WHERE table_name = '$table_name' AND table_schema = '$database_name'");
					$select->execute();
					// menampilkan data informasi 1 colom
					while($result=$select->fetch(PDO::FETCH_NUM)){
						echo "<tr>";
					//---
					//menampilkan data informasi 1 table
					for($j=0;$j<5;$j++){
						echo "<td>$result[$j]</td> ";
					}
					//---
						echo "</tr>";
					}
					echo "</table>";
				}
			} else {
				header('Location : index.php');
			}
			?>
		</div>
	</div>
</body>
</html>
