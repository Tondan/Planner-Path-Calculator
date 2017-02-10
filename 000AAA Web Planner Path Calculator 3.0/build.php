<html>
    <head>
        <title>Risultato</title>
        <link rel="stylesheet" type="text/css" href="mystyle.css">
    </head>
<body>
<!--Si arriva qui dal Build.html, dove 
    l'utente ha specificato i parametri 
    che vuole siano dell'albero-->

<?php
//getmicrotime servirà per sapere il tempo di esecuzione
    function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
    
include("./config/dbconnect.inc.php");
ini_set('memory_limit', '-1');
//Connessione al Database

$Treename=$_POST["treeName"];
$split=$_POST["split"];
$depth=$_POST["depth"];

$myInputsAtt=$_POST["myInputs"];
$myInputsMin=$_POST["myInputsMin"]; 
$myInputsMax=$_POST["myInputsMax"];
//passaggio attributi nodo

$myInputsAttArco=$_POST["myInputsArco"];
$myInputsMinArco=$_POST["myInputsMinArco"];
$myInputsMaxArco=$_POST["myInputsMaxArco"];
//passaggio attributi arco

$Nattributiarchi=count($myInputsAttArco);
$Nattributinodo =count($myInputsAtt);
$Nattributi=$Nattributinodo+$Nattributiarchi; 
$troppogrande= ((pow($split,$depth+1)-1)/($split-1)) * ($Nattributi+1);
if($troppogrande > 22000000){
    die ("Hai richiesto di creare un albero più grande del previsto");
}
/*2MLN nodi * 7 attributi = 14MLN di informazioni
  quindi abbiamo dato 22MLN al massimo per i 
  casi eccezionali. Se è troppo grande, non 
  viene inserito nel db*/

    $time_start = getmicrotime();  
//Tempo di inizio effettivo dell'esecuzione
$arrayinputs=array($myInputsAtt,$myInputsAttArco,$myInputsMin,$myInputsMinArco,$myInputsMax,$myInputsMaxArco);


$arrayinputs2=array();
$contatore=0;
foreach ($arrayinputs as $eachInput){
    foreach($eachInput as $eachInput1){
        
        $arrayinputs2[$contatore]=$eachInput1;
    // echo $eachInput1.",";
    $contatore+=1;
    }
}
$arrayinputs=$arrayinputs2;


/*
la struttura dati ottenuta in ingresso dalla pagina web, è un array di informazioni la cui trama è di questo tipo (elenco di nomi attributi):   att1
                                                att2
                                                att3
(elenco di valori minimias sociato all'attributo):min1
                                                min2
                                                min3
(elenco di valori massimi associato all'attributo):max1
                                                    max2
                                                    max3


nel passaggio qui sotto la modelliamo per essere usata piu facilmente dal foreach

trasformando i valori in ingressi in :[att 1, min 1, max 1]...[att n, min n, max n]
*/
$list = array();
$asd= count($arrayinputs);
//echo "<br>".$asd."<br>";
$asd= $asd/3; 
	$x1=0;
    $x2=$asd;
    $x3=$asd*2;
    //echo $x1, $x2, $x3."<br>";
For($i=0;$i<$asd;$i++){
    
    $list[$i]=$arrayinputs[$x1]. ",".$arrayinputs[$x2].",".$arrayinputs[$x3];
    $x1++;
    $x2++;
    $x3++;
    //echo $list[$i]."<br>";
        


}  

$nome=$Treename; 

$ap = array();
$attributi=array();
$MinMax=array();

/*
        si è dovuto in fine separare il tutto per agevolare ulteriolmente il foreach per la costruzione 
     poteva essere omessa questa parte o fatta in'unaltro modo, ma nelle versioni precedenti
    del programma i dati in ingresso erano piu confusionari e questa parte di codice sistemava
    tutto , ora è quasi ridontante ma abbiamo preferito lasciarla .
*/
foreach ($list as $line)
  {
        $ap = explode(',',$line);
                /*
	           foreach ( $ap as $string){
	                   echo $string;
		              echo "<br>";
                }
                */
        array_push($attributi,$ap[0]); //array di nomi attributi poteva essere 
        array_push($MinMax,$ap[1],$ap[2]); //array che va di due in due min e max
	}


//INIZIO CREAZIONE TABELLA/ALBERO
//$attributi contiene tutti nomi degli attributi
/*creeremo un'interrogazione che
  creerà una tabella vuota, che verrà riempita
  col CSV*/ 
$aggiungere="";
foreach($attributi as $string){
    $aggiungere=$aggiungere .$string. " FLOAT(12,2), "  ;
}
    //questo passagio è dovuto al fatto che bisognava cancellare la ", " finale
$aggiungere=substr($aggiungere,0,(strlen($aggiungere)-2));


$Ctable ="CREATE TABLE ".$nome." (id INT(12) UNSIGNED, ".$aggiungere.") ENGINE=MYISAM";
//creazione della tabella nel dbs
if($link->query($Ctable) === TRUE) {
    echo "Table ".$nome." created successfully"."<br>";
} else {
    echo "Error creating table: " . $link->error;
    die();
}

//fine creazione






//inizio CSV
//inizio creazione CSV
/*prima crei un file di testo .csv
  poi crei righe di valore randomico
  compresi tra i valori minimo e massimo
  indicati dall'utente.
  Queste righe vengono inserite nel CSV
  insieme agli opportuni separatori.
  All'inizio di ogni riga c'è un intero
  crescente, che è l'ID del nodo*/
$NomeCsvFile=$nome.".csv";
//echo "<br><br><br>".$NomeCsvFile."<br><br><br>";
$file = fopen($NomeCsvFile,"w");

$k=count($attributi);
$array=array();
$geometrica=(pow($split,$depth+1)-1)/($split-1);
    for($Q=0;$Q<$geometrica;$Q++){ 
           
        $asd=0;
        $nodo[0]=$Q+1;
        for($i=1; $i<$k+1; $i++ ){ // genera la stringa che inseria nel     
        $nodo[$i]= strval(rand(intval($MinMax[$asd]),intval($MinMax[$asd+1]))) .".". rand(0,99); 
        $asd +=2;
        //echo "<br>".$nodo."<br>"; 
            }
      //  $nodo.="\n";
       $array[$Q]=$nodo; 
     }//scrittura nel CSV
foreach ($array as $fields) {
    fputcsv($file, $fields);
}

fclose($file);
//FINE CREAZIONE CSV




//INIZIO INSERIMENTO CSV
$insert= "LOAD DATA LOCAL INFILE '$NomeCsvFile' 
          INTO TABLE $nome
          FIELDS TERMINATED BY ',' 
          LINES TERMINATED BY '\n'";

if($link->query("$insert") === TRUE){
    echo "insert eseguito con successo"."<br>";
    unlink($NomeCsvFile);
//cancellazione dal server, una volta che ha compiuto il suo dovere
}
    else{
     echo "Insert non eseguito " . $insert . "<br>" . $link->error . "<br>";
}
//FINE INSERIMENTO CSV


/*inseriamo l'albero nella nostra tabella Tree,
  comune a tutti gli alberi che conterrà:
  - nome albero per poterlo ritrovare; 
  - split e depth per effettuare il calcolo
  - Numero degli attributi degli archi, per 
    non sommarli alla fine*/
$sql = "INSERT INTO Tree (nome, split, depth,Nattributiarchi)
VALUES ('$nome','$split ','$depth','$Nattributiarchi')";

if ($link->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

//restituzione del tempo impiegato in creazione    
    $time_end = getmicrotime();
    $time = $time_end - $time_start;
    header("refresh:4;url=index.html"); 
    echo "albero creato con successo" . "<br>";
    
    echo "Sono trascorsi $time secondi per l'esecuzione del codice.";
    
mysqli_close($link);


?>
<br><br><br><br><br><br><br><br>


<ul>
  <li><a href="build.html">Crea</a></li>
  <li><a href="delete_inter.php">Cancella</a></li>
  <li><a href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>   
    
    </body>
</html>


