<html>
<head>
    <title>Calcola</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>

<?php
/*in questa parte del codice si
  da all'utente la possibilità di
  selezionare il secondo nodo scegliendo
  tra una rosa di possibili
  secondi nodi (ovvero un path fino a 1)*/
include("./config/dbconnect.inc.php");
ini_set('memory_limit', '-1');
$nome =$_POST['alberi'];
$vertex1=$_POST['nodouno'];   
$padri=array();
/*$_POST['alberi'] è il nome proprio dell'albero scelto,
  e viene preso dal calcola_inter insieme al 
  primo nodo. $padri servirà per calcolare il path
  completo (senza fare nessuna query se non quella
  per sapere split e depth)*/

echo "- Albero selezionato: " . $nome . "<br>";
echo "- Primo vertice: " . $vertex1 . "<br>";


$sqli = "SELECT * FROM Tree WHERE nome='$nome' ";
$result = $link->query($sqli);
//va sulla tabella Tree a prendere le informazioni per il calcolo del path
if ($result->num_rows > 0) {/*condizione per sapere se 
                              la query è andata a buon fine
                              o se l'albero che in calcola_inter
                              era disponibile è stato nel 
                              frattempo cancellato*/
        $row = $result->fetch_assoc();
        $split= $row["split"];
        $depth= $row["depth"];
        $Nattributiarchi=$row["Nattributiarchi"];
        //mi serve la split per calcolare il path
        echo " - Split: " . $row["split"]. "<br>" . " - Depth:  " . $row["depth"]."<br>";
    }


else {
    echo "0 results";
}
$geometrica = (pow($split,$depth+1)-1)/($split-1);
echo "- Numero totale di nodi: " . $geometrica . "<br><br><br><br>";
//calcolo il numero di nodi dell'albero


   if($vertex1 <= $geometrica){ /*nel caso sia stato scelto un nodo 
                                  troppo grande o non appartenente
                                  all'albero, il calcolo non si farà*/


/*per trovare tutti i possibili padri una una funzione
  già discussa nel template: parteinteradi(v+k-2)/k;
  usata ricorsivamente fa andare di padre in padre
  senza toccare la tabella nel database*/
        $i=1;
        $temp=$vertex1;
 //       echo "PATH: "."<br>";
        $padri[0] = $vertex1;
        while(($temp + $split - 2)>=$split ){
            $padri[$i]=intval(($temp + $split - 2)/$split); 
            $temp=$padri[$i];
            //echo $temp."<br>";
            $i++;     
        } 
        foreach($padri as $padre){
   //         echo $padre . "<br>";
        }
    }
    else {
        echo "nodo scelto non è presente troppo grande";
        die;
    }

?>

<!--manda tutto quando a calcolaeffettivamente, che esegue il calcolo-->
<form method="POST" action="calcolaeffettivamente.php">



    <input type="hidden" name="vertex1" value= <?php echo $vertex1 ?> />
    <input type="hidden" name="nome" value=<?php echo $nome ?> />
    <input type="hidden" name="split" value=<?php echo $split ?> />
    <input type="hidden" name="depth" value=<?php echo $depth ?> />
    <input type="hidden" name="Nattributiarchi" value=<?php echo $Nattributiarchi ?> />
<!--gli input sono hidden perché non sono stati scelti dall'utente
    ma ricavati con una query-->



   <fieldset>
      <legend>immetti secondo nodo</legend>
      <select name ="vertex2"> 

        immetti secondo nodo:          
<?php
        
    foreach($padri as $padre){  ?>                                        
    <option selected ="" > <?php echo $padre; ?>  </option> <?php } ?>
<!--mostra una rosa di possibili secondi nodi all'utente (path completo finoa 1-->
</select>

</fieldset><br>
          
          
<input type="submit" value="calcola il path">
<br><br><br><br><br><br><br><br>

<ul>
  <li><a href="build.html">Crea</a></li>
  <li><a href="delete_inter.php">Cancella</a></li>
  <li><a href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>   

</form>
</body>
</html>