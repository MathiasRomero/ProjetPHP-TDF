<?php
/*
Vérificiation de la session + include des fonctions pdo + créations des vues quand on est sur le menu
*/

include("fonctionOutil/verifsession.php");
include("fonctionOutil/pdo_oracle.php");
include("fonctionOutil/creationVues.php");


include "menu.htm";
 ?>