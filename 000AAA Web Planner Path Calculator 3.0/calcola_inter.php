<html>
<head>
    <title>Calcola</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<h1>Calcolo Path</h1>
<?php 
/*questa operazione fa scegliere 
  l'albero su cui fare il calcola
  e il primo nodo, poi manderà
  la scelta del secondo a calcola slim */
    include("./config/dbconnect.inc.php");
    ini_set('memory_limit', '-1');
//faccio una query per sapere tutti gli alberi presenti nel DB
//ricordando che ogni albero ha una contropare nella tabella comune "tree"
    $sqli = "SELECT nome FROM Tree";
    $result = $link->query($sqli); 
?>

<form method="POST" action="calcolaslim.php">
  
   <fieldset>
      <legend>Calcolo</legend>
      
      seleziona nome albero:<br>
      <select name ="alberi"> <br>

                
<?php
   //restituisce tutti gli alberi presenti e li rende selezionabili     
     while($row=mysqli_fetch_array($result)){  ?>                                        
     <option selected ="" > <?php echo $row[0]; ?>  </option> <?php } ?>

?>  
</select>
<br>      

<legend>Immetti primo nodo</legend>
<input type="number" name="nodouno" min = 1><br>
<!--fa immettere il primo nodo del path,
    per decidere il secondo bisogna andare
    su calcola slim tramite il bottone--> 

</fieldset><br>
          
          
<input type="submit" value="calcola parziale">
<br><br><br><br><br><br><br><br>

    
 <ul>
  <li><a  href="build.html">Crea</a></li>
  <li><a  href="delete_inter.php">Cancella</a></li>
  <li><a class="active" href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>      
    
    
    
    
       
</form>
</body>
</html>