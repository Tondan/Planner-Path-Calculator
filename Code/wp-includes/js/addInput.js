var counter = 1;
var limit = 1000;
function addInput(divName){
     if (counter == limit)  {
          alert("You have reached the limit of adding " + counter + " inputs");
     }
     else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = "Attr" + (counter + 1) + ":<input type='text' name='myInputs[]'>" + "Val min" + (counter + 1) +":<input type='text' name='myInputsMin[]'>" + "Val max" + (counter + 1) +":<input type='text' name='myInputsMax[]'>"
         
          document.getElementById(divName).appendChild(newdiv);
          counter++;
     }
}