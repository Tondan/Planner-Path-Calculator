<html>
<head>
<title>Cancella Tree</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
    <h1>Cancella Tree</h1>
    
<?php
    
    include("./config/dbconnect.inc.php");
    ini_set('memory_limit', '-1');
    
   $query="SELECT nome FROM Tree";
   $result = $link->query($query); 

   ?> 
    
    
<form method="POST" action="delete.php">
   <fieldset>
    <legend>Cancella</legend>
<br>    
seleziona nome albero:<br>
<select name ="alberi"> 


            
<?php
        
        while($row=mysqli_fetch_array($result)){  ?>                                        
        <option selected = > <?php echo $row[0]; ?>  </option> <?php } ?>

    ?>
  
</select>
       </fieldset><br>
   
<input type="submit" value="Cancella" name="invia"> 

<ul>
  <li><a  href="build.html">Crea</a></li>
  <li><a class="active" href="delete_inter.php">Cancella</a></li>
  <li><a  href="calcola_inter.php">Calcola</a></li>
  <li style="float:right"><a href="index.html">Home</a></li>
</ul>      

</form>
</body>
</html>