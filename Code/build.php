<?php

//Connessione al Database
include("./config/dbconnect.inc.php");

$Treename=$_POST["treeName"];
$split=$_POST["split"];
$depth=$_POST["depth"];

$myInputsAtt=$_POST["myInputs"];
$myInputsMin=$_POST["myInputsMin"];
$myInputsMax=$_POST["myInputsMax"];

$temp=array($myInputsAtt,$myInputsMin,$myInputsMax);
//una lista di attributi tipo ( ("ciao"),("pino"),("fino"),("2,4"),("2,6"),("4,5"))

$temp1=array();                         
$count=0;                               
                                           
foreach ($temp as $eachInput){
    
	foreach($eachInput as $eachInput1){
        
        $temp1[$count]=$eachInput1;
     echo $eachInput1.",";
    $count+=1;
    }
}
$temp=$temp1;
    
    
    
 
    
 /*    $temp = array 
(
"P1","P2","P3","P4","11","12","12","13","201","202","203","204");*/


$list = array();
$asd= count($temp);
echo "<br>".$asd."<br>";
$asd= $asd/3; 
	$x1=0;
    $x2=$asd;
    $x3=$asd*2;
    echo $x1, $x2, $x3."<br>";
For($i=0;$i<$asd;$i++){
    
    $list[$i]=$temp[$x1]. ",".$temp[$x2].",".$temp[$x3];
    $x1++;
    $x2++;
    $x3++;
    echo $list[$i]."<br>";
        


}  

$nome=$Treename; /// nome db 


$ap = array();
$attributi=array();
$MinMax=array();


foreach ($list as $line)
  {
        $ap = explode(',',$line);
                /*
	           foreach ( $ap as $string){
	                   echo $string;
		              echo "<br>";
                }
                */
        array_push($attributi,$ap[0]); //array di nomi attributi 
        array_push($MinMax,$ap[1],$ap[2]); //array che va di due in due min e max
	}
/////////////////////////////////CREATE TABLE 
/////grazie a $attributi che contiene tutti nomi delle proprieta 
$aggiungere="";
foreach($attributi as $string){
    $aggiungere=$aggiungere .$string. " FLOAT(12,2), "  ;
}
$aggiungere=substr($aggiungere,0,(strlen($aggiungere)-2));

$Ctable ="CREATE TABLE ".$nome." (id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ".$aggiungere.") ENGINE=MYISAM";


if($link->query($Ctable) === TRUE) {
    echo "Table".$nome."created successfully";
} else {
    echo "Error creating table: " . $link->error;
}

    ///////fine creazione
/////////inizio CSV /////
//esso verra caricato con i dati degli attributi del albero
$file = fopen("$nome.csv","w");

$k=count($attributi);

$geometrica=(pow($split,$depth+1)-1)/($split-1);// funsione per calcolare il numero di nodi del albero 
                        for($Q=0;$Q<$geometrica;$Q++){ 
                            $asd=0;
                            $nodo="," . strval($Q) . ",";
                            for($i=0; $i<$k; $i++ ){ // crea i valori random da inserire nel file csv   
                            $nodo.= "!," . strval(rand(intval($MinMax[$asd]),intval($MinMax[$asd+1]))) .".". rand(0,99) . ","; 
                            $asd +=2;
				            }
				        fwrite($file, $nodo);
                         }//scrittura nel CSV

fclose($file);

$insert="LOAD DATA INFILE '$nome.csv'
         INTO  TABLE".$name.
        "FIELDS  TERMINATED BY ','
         OPTIONALLY ENCLOSED BY '!' 
         ESCAPED BY ','
         LINES TERMINATED BY ',,,\\r\n'";

if($link->query($insert) === TRUE){
    echo "insert eseguito con successo";
}
    else{
     echo "Error: " . $insert . "<br>" . $link->error;
}

//////////////////////////inseriamo l'albero nella nostra lista di Tree
// la tabella tree rappresenta tutti gli alberi prenseti nell sistema////
$sql = "INSERT INTO Tree (nome, split, depth)
VALUES (".$nome.",".$split.",".$depth.")";

if ($link->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

mysqli_close($link);
?>