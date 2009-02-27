<?php
/* -------------------------------------------------- MODEL -------------------------------------------------- */
/* prelucrare date */
class db_mysql {}
class db_postges {}
class auth {}
class file_handler {}
class debug {}
class caching {}
/* -------------------------------------------------- VIEW -------------------------------------------------- */
/* vizualizare date */
class html {}
class smarty {}
class xml {}
class pdf {}
/* -------------------------------------------------- CONTROLLER -------------------------------------------------- */

/*
obtine datele de care are nevoie viewerul folosind functii din model
prelucreaza cererile
- index.php/model/posts/action/view_thread
-index.php/model/homepage/action/index
- care este modelul
- care este actiunea (functie intr-o clasa)
trimite datele la viewer

*/
/* -------------------------------------------------- TEST -------------------------------------------------- */

/*
fiecare sectiune = un model caruia ii corespunde un controller



iModel
iController
iView


model extends iModel
-conexiunea la db
- autentificare
setari:
- motor db
- tabela db
- bool authentication



app/
app/controllers
app/models
app/views
app/skins

framework/
framework/abstraction
framework/models
framework/controllers
framework/views
framework/alte_clase(filterinput, filehandling)

config/

3rdparty/

-----------------------------------------------------
dasoftf/
dasoftf/include (app models)
dasoftf/languages
dasoftf/skins (view)
dasoftf/3rdparty
rudweb/lib ?
rudweb/languages ?
rudweb/skin ?


*/

?>