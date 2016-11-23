<?php

include_once "koneksi.php";

$dbs = $pdo->query( 'SHOW DATABASES' );
while( ( $db = $dbs->fetchColumn( 0 ) ) !== false ){
    echo "<img src ='image/data.png'> <a href=show.php?database_name=$db>$db<br></a>";
}

?>