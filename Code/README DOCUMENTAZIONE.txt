L'utente fa girare il programma e si trova davanti un'interfaccia che gli 
permette di decidere se passare all'interfaccia di creazione, cancellazione
o esecuzione del calcolo


BUILD
L'utente si trova davanti a un'interfaccia che gli permette
di inserire il nome di un attributo, il suo lower bound,
il suo upperbound e di decidere se è l'attributo di un nodo o di un arco.
Può inserire un altro attributo se clicca su "aggiungi atributo".
può inserire split e depth dell'albero.
Infine dà un nome all'albero che vuole creare e clicca su "crea albero".

L'utente vede questo, il resto lo fa l'engine.

L'engine si ritrova a disposizione nome dell'albero, degli attributi,
eccetera, tutto passato dall'utente. 
L'engine crea una nuova tabella con storage engine =myISAM, 
attributo ID intero di default e tanti attributi quanti sono gli attributi
voluti dall'utente (coi loro nodi ovviamente)


NOTA BENE: ogni nodo ha un solo padre. Quando si calcola il path
il nodo figlio NON può decidere quale nodo padre sia il suo nodo padre,
ma solo se vuole che il suo nodo padre faccia parte del path o meno,
quindi attributi di nodi e archi saranno nella stessa tupla.

L'engine fa un calcolo della serie geometrica con k=split,
n=size, così trova il numero di elementi che deve creare.
L'engine crea un file csv con dentro seriegeometrica(k,n) righe
e un intero crescente che indica l'ID del nodo, e ovviamente 
gli attributi di ogni colonna.
L'engine importa i valori nel csv dentro al database.
L'engine crea una nuova tupla nella tabella "infoalberi"
con dentro il nome dell'albero, il suo split, la sua size e 
il numero di attributi appartenenti al nodo (che tornerà utile
in fase di calcolo per evitare di sommare gli attributi dell'arco
dell'ultimo nodo).
L'engine cancella il csv.

osservazioni: nel database esisteranno tante tabelle quanti alberi
				e in più una tabella in comune che conterrà le
				informazioni sugli alberi


CALCOLO
L'utente si ritrova davanti agli occhi un'interfaccia che gli permette di 
selezionare un albero. L'utente seleziona l'albero e inserisce il primo nodo.
L'engine cerca il MAX(ID) dell'albero e vede se è minore o maggiore 
del numero fornito dall''utente. se è minore, l'utente si è spinto dove
il nostro povero albero non può più seguirlo.
L'engine cerca nella tabella infoalbero la split, la size e il numero di 
attributi per arco.
L'engine, ricorsivamente fino a 1, esegue il calcolo (IDNODO+SPLIT-2)/SPLIT 
e di questo risultato prende la parte intera per sapere di volta in volta
il nodo padre e il nodo padre.
Spara a video questi ID
L'utente seleziona quale tra questi possibili vuole che sia il secondo nodo.
L'engine esegue una query per estrarre dal database le informazioni dei nodi 
tra quello di partenza e quello d'arrivo. 
L'engine esegue la somma attributo per atributo.
Quando si riitrova davanti all'ultimo nodo, esegue un semplice calcolo
e fa la somma solo dei primi attributi, quelli associati al nodo, ma non
quelli associati all'arco. L'informazione di quanti sono gli attributi del
nodo l'avrà presa dalla tabella infonodi.
spara il risultato.
L'utente è contento.
I progettisti sono contenti.
Il sistema è contento.
Tutti sono contenti.
Tranne IH-OH di winnie the pooh. Lui non è mai contento. 