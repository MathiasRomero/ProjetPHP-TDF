<?php
/*Code à include dans certains fichiers php pour verifier la session*/
session_start();
if(empty($_SESSION['login'])) 
{
  // Si inexistante ou nulle, on redirige vers le formulaire de login
  header('Location: https://dev-pphp2a02.users.info.unicaen.fr/index.html');
  exit();
}
?>