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
		$menu = "<a href=index.php><img src ='image/pma.png'> 127.0.0.0</a> . ";
		echo "$menu";
		echo "<br/><hr/>";
		?>
		<form method="POST" action="create_db.php">
		    <?php if(isset($error)) { ?>

				<medium style = "color:#aa0000;"><?php echo $error; ?></medium><br><br>
			<?php } ?>
		        <input type="text" name="dbname">
		        <input type="submit" name="submit" value="Create">
	    </form>
		</div>

	</div>

</body>
</html>
