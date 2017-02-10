<html>
   <head>
       <title>Risultato operazione</title>
        <link rel="stylesheet" type="text/css" href="mystyle.css">
   </head>
    <body>
   

<?php 
    
include("./config/dbconnect.inc.php"); 
ini_set('memory_limit', '-1'); 


//arriva dal delete.html, dove gli è stato detto che albero cacellare
$alberodacancellare = $_POST['alberi']; 

//cancellazione della tabella albero
echo $alberodacancellare . "<br>";
	$query1="DROP TABLE ".$alberodacancellare; 
echo $query1;
$result1 = $link->query($query1); 
if(!$result1){ 
      die("Tree non presente". mysqli_error());  
    }
echo "tabella cancellata con successo" . "<br>";




//cancellazione tupla dell'albero selezionato da Tree
$query2= "DELETE FROM Tree WHERE nome='$alberodacancellare'";
$result2 = $link->query($query2);
if(!$result2){
     die("dove stara mai lerrore?". mysql_error());
	}

//ritorna all'index
header("refresh:3;url=index.html"); 
echo "tupla cancellata con successo" . "<br>";

?>

<ul>
  <li><a  href="build.html">Crea</a></li>
  <li><a href="delete_inter.php">Cancella</a></li>
  <li><a href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>   


    </body>
</html>