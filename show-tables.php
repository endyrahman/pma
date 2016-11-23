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
				$dbs = $pdo->query("SHOW COLUMNS from `$table_name`");
				?>				
				<big><?php echo "<br/>Tabel : $table_name<br/><br/>";?></big>
				<?php
				echo "<a href= show-tables.php?database_name=$database_name&table_name=$table_name&todo=insert>Insert Data</a><br/><br/>";
				if($todo=='insert'){
					// mengambil informasi dari $table_name dan $table_schema sesuai url
					$select = $pdo->prepare("SELECT COLUMN_NAME,COLUMN_TYPE, COLUMN_KEY, IS_NULLABLE, COLUMN_DEFAULT
					  FROM INFORMATION_SCHEMA.COLUMNS
					  WHERE table_name = '$table_name' AND table_schema = '$database_name'");
					$select->execute();

					$select2 = $pdo->prepare("SELECT COLUMN_TYPE
					  FROM INFORMATION_SCHEMA.COLUMNS
					  WHERE table_name = '$table_name' AND table_schema = '$database_name'");
					$select2->execute();

					$select3 = $pdo->prepare("SELECT COLUMN_NAME
					  FROM INFORMATION_SCHEMA.COLUMNS
					  WHERE table_name = '$table_name' AND table_schema = '$database_name'");
					$select3->execute();

					$rowCount = $select->rowCount();
					// menampilkan data informasi 1 colom	
					echo("<table>");
					while($result=$select->fetch(PDO::FETCH_NUM)){
						echo "<th>$result[0]</th>";
					}
					echo("<tr>");
					while($result2=$select2->fetch(PDO::FETCH_NUM)){
						echo "<td>$result2[0]</td>";
					}
					echo("</tr>");
					echo("<tr>");
					echo "<form method='POST'>";
					for ($i=0; $i <$rowCount; $i++) { 
							$addText = "<input type='text' name='name[$i]' size='12'>"; 	
							echo("<td>$addText</td>");
					}
					echo "<tr><td><input type='submit' name='submit'></form></td></tr>";
					echo("</tr>");
					echo "</table>";

					if (isset($_POST['submit'])) {
						$name = $_POST['name'];

					$conn = mysql_connect("localhost", "root", "")
					
						or die("Could not connect.");
			
	  					$rs = mysql_select_db($database_name, $conn)or die("Could not select database.");

						$num_columns = count($name);
						$colom = $select3->rowCount();

						$sql = "insert into $table_name(";
						$x=0;
						while($result3=$select3->fetch(PDO::FETCH_NUM)){
							$sql .= "$result3[0]";
							$x=$x+1;
							if ($x<$colom) {
								$sql .=",";							
							}			
						}
						$sql .= ") VALUES (";
						for ($i = 0; $i < $num_columns; $i++) 
					  	{
					    	$sql .= "'$name[$i]'";
					    	if(($i+1) != $num_columns){ $sql.=","; }
					  	}

						$sql .= ")";

						echo("SQL COMMAND: $sql <hr>");
					
						$resu = mysql_query($sql,$conn)
						or die("Could not execute SQL query");
					}
				}

				echo "<table><tr>";			
				while ($row = $dbs->fetch(PDO::FETCH_NUM)) {
					echo "<th>$row[0]</th>";
				}
				echo "<th>Tindakan</th></tr>";
				//hapus data//
				if($todo=='delete'){

					$sql = $pdo->query("DESCRIBE `$table_name`;");
					$result12 = $sql->fetch(PDO::FETCH_NUM);

					$counter=$pdo->prepare("select * from `$table_name`");
					$counter->execute();

					$countcol = $counter->columnCount();
					
					$dbs = $pdo->prepare("delete from $table_name where $result12[0] = $dt");
					$dbs->execute();
					
					$dbs = $pdo->query("SHOW COLUMNS from `$table_name`");

					$count=$pdo->prepare("select * from `$table_name`");
					$count->execute();
					$no_of_columns=$count->columnCount();

					$data=$pdo->prepare("select *  from `$table_name`");
					$data->execute();	
				}	

				$count=$pdo->prepare("select * from `$table_name`");
				$count->execute();

				@$no_of_columns=$count->columnCount();
 				
				$data=$pdo->prepare("select *  from `$table_name`");
				$data->execute();

				while($dbs = $data->fetch(PDO::FETCH_NUM)){
					echo "<tr>";
					for($j=0;$j<$no_of_columns;$j++){
						echo "<td>$dbs[$j]</td>";			
					}
				    echo " 	<td><a href= show-tables.php?database_name=$database_name&table_name=$table_name&data=$dbs[0]&todo=delete>Hapus</a></td>";
					echo "</tr>"; 
				}
				echo "</table>";
			}
			 else {
				header('Location : index.php');
			}
			?>
		</div>
	</div>
</body>
</html>
