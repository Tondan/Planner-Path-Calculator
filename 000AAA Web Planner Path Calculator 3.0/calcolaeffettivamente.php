<html>
<head>
    <title>Calcola</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>


<?php
    function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

include("./config/dbconnect.inc.php");
ini_set('memory_limit', '-1');
echo "- Albero selezionato : " . $_POST['nome']. "<br>";
echo "- Primo vertice: " . $_POST['vertex1'] . "<br>";
echo "- Secondo vertice: " . $_POST['vertex2']. "<br>";
echo "- Split: " . $_POST['split']. "<br>";
echo "- Depth: " . $_POST['depth']. "<br>";

  
$nome = $_POST['nome'];
$vertex1 = $_POST['vertex1'];
$vertex2 = $_POST['vertex2'];
$depth = $_POST['depth'];
$split = $_POST['split'];
$Nattributiarchi = $_POST['Nattributiarchi'];
$geometrica = (pow($split,$depth+1)-1)/($split-1);
echo "- Numero totale di nodi: " . $geometrica . "<br><br><br><br>";

/*stampa le informazioni base
  dell'albero, il primo vertice
  e il secondo. Non stampa
  invece $Nattributiarchi perché
  serve a noi implementatori, non 
  all'utente; poi mette le variabili
  in altre variabili più significative
  per la facilità di scrittura del codice*/

    $time_start = getmicrotime();  
    //istante in cui inizia l'esecuzione vera e propria



//INIZIO RITROVAMENTO NODI DEL PATH
/*salvo in $padri tutti i nodi sui quali 
  dopo farò la query, e stampo il path che
  mi sto accingendo a effettuare*/
		$i=1;
        $temp=$vertex1;
        echo "PATH: "."<br>";
        $padri[0] = $vertex1;
        while(($temp + $split - 2) > $vertex2 +1 ){ 
            $padri[$i]=intval(($temp + $split - 2)/$split); 
            $temp=$padri[$i];
            $i++;     
        } 
        foreach($padri as $padre){
            echo $padre . "<br>";
        }
//FINE RITROVAMENTO NODI PATH


//STAMPA DISTANZA TRA I DUE VERTICI
                $i=0;
            while($padri[$i] != $vertex2){ //distanza tra primo e secondo vertice(+1)
                $i++;
                if (i>99){die;} //l'ho messo per paura di un ciclo infinito... brutti ricordi
            }
echo "la distanza tra nodo di partenza e di arrivo è ".$i."<br>";
//il nome è significativo: stampa la distanza tra i due vertici di partenza e arrivo
//FINE STAMPA DISTANZA




//INIZIO CREAZIONE QUERY
/*Visto che la query è una stringa, noi
  la trattiamo come tale, poi si farà
  la query con essa*/
/*vengono trovati tutti gli attributi
  di tutti i nodi e di tuttii gli archi
  che fanno parte del path TRANNE
  archi e nodi dell'ultimo, su cui 
  faremo una query specifica per 
  sommare solo gli attributi dei nodi*/
    $txt="id=".$vertex1." OR ";
            for ($j=1;$j<$i;$j++){
                 $txt.= "id=".$padri[$j]." OR ";    //genera stringa sql (WHERE)
            }
$txt=substr($txt,0,(strlen($txt)-3));

$sqli = "SELECT * FROM $nome WHERE $txt";
//FINE CREAZIONE QUERY
echo "<br><br><br>";


//QUERY VERA E PROPRIA (tranne ultimo vertice)
$result = $link->query($sqli);
 if ($result->num_rows > 0) {
            $campi=array();
            $i=0;
            while ($finfo = $result->fetch_field()) {
                
                $campi[$i]=$finfo->name;
                $i++;
                }
 }
else {
    echo "0 results";
    die;
}
//FINE QUERY VERA E PROPRIA



//SOMMA DI TUTTI GLI ATTRIBUTI (TRANNE ULTIMO VERTICE)     
     $somma=array();
    // output data of each row
    while($row = $result->fetch_assoc()) {
      for($i=1;$i<count($campi);$i++){ // parte da i=1 perche a 0 c'è ID e fare la somma di id non ha senso 
      $somma[$i-1]+=$row[ $campi[$i] ]; //in somma [0] c'è il primo campo in somma [1] il secondo 
          
      }
    }

/*echo "id nonserve____";
for($i=0;$i<count($campi);$i++){
   echo $somma[$i]."____"; 
}*/
// FINE PRIMA SOMMA
$prova=count($campi)-$Nattributiarchi;




//SOMMA DELL'ULTIMO VERTICE
$sql = "SELECT * FROM $nome WHERE ID=$vertex2";
 $result = $link->query($sql);
    if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {
        
        for($i=1;$i<(count($campi))-$Nattributiarchi;$i++){ // somma solo gli attributi "nodi"
            $somma[$i-1]+=$row[ $campi[$i] ]; //in somma [0] c'è il primo campo in somma [1] il secondo 
            }   
           }  
        } else {
    echo "error";}
//FINE SOMMA ULTIMO VERTICE

        $time_end = getmicrotime();
    $time = $time_end - $time_start;
    echo "Sono trascorsi $time secondi per l'esecuzione del codice.";
//stampa il tempo di esecuzione

/*echo "id nonserve____";
for($i=0;$i<count($campi);$i++){
   echo $somma[$i]."____"; 
}*/

echo"<style>

table {
    border-collapse: collapse;
    width: 60%;
}

th, td {
    text-align: left;
    padding: 10px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #00bfff;
    color: white;
}
</style>
</head>
<body>
</style>";

echo "<br><br><h1>Somma finale</h1> <br>";
echo "<table>"; 
echo "<tr>";
        foreach($campi as $value){   
                echo "<th>".$value."</th>"; 
                } 
echo "</tr>";
echo "<tr>";
echo "<td>***</td>";
foreach($somma as $value) {    
echo "<td>".$value."</td>";      
}   
echo "</tr>";
echo "</table>";
echo "<br><br><br><br><br><br><br><br>";
//la tabella sara fatta da $campi[1].....[n]
// e i risultati saranno da $somma[0]....[n-1]

?>
<ul>
  <li><a href="build.html">Crea</a></li>
  <li><a href="delete_inter.php">Cancella</a></li>
  <li><a href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>   