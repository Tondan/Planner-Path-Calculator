var counterArco=1;
var limit = 100;

function addInputArco(divName){
     if (counterArco == limit)  {
          alert("You have reached the limit of adding " + counterArco + " inputs");
     }
    
     else {
          var newdivArco = document.createElement('div');
         
          newdivArco.innerHTML = "AttrArco" + (counterArco + 1) + ":<input type='text' name='myInputsArco[]'>" + "Val min arco" + (counterArco + 1) +":<input type='text' name='myInputsMinArco[]'>" + "Val max arco" + (counterArco + 1) +":<input type='text' name='myInputsMaxArco[]'>"
         
          document.getElementById(divName).appendChild(newdivArco);
          counterArco++;
     }
}
