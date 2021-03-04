<?php
define('LOGIN','PPHP2A_02');
define('PASSWORD','SUPPRIMER');
 if(!empty($_POST)) 
  {
    // Les identifiants sont transmis ?
    if(!empty($_POST['login']) && !empty($_POST['password'])) 
    {
      // Sont-ils les mêmes que les constantes ?
      if($_POST['login'] !== LOGIN) 
      {
      	header('Location: https://dev-pphp2a02.users.info.unicaen.fr/index.html');
      }
        elseif($_POST['password'] !== PASSWORD) 
      {  
      	header('Location: https://dev-pphp2a02.users.info.unicaen.fr/index.html');
      }
        else
      {
        // On ouvre la session
        session_start();
        // On enregistre le login en session
        $_SESSION['login'] = LOGIN;
        // On redirige vers le fichier menu.htm
        header('Location: https://dev-pphp2a02.users.info.unicaen.fr/menu.php');
        exit();
      }
    }
      else
    {
       header('Location: https://dev-pphp2a02.users.info.unicaen.fr/index.html');
    }
  }
?>
