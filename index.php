<?php
// IS RECEIVED SHORTCUT
if(isset($_GET['q'])){

    // VARIABLE
    $shortcut = htmlspecialchars($_GET['q']);

    // IS A SHORTCUT ?
    $bdd = new PDO('mysql:host=*);
    $req =$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()){

        if($result['x'] != 1){
            header('location: ../?error=true&message=Adresse url non connue');
            exit();
        }

    }

    // REDIRECTION
    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()){

        header('location: '.$result['url']);
        exit();

    }

}
// verif envoie url
if (isset($_POST['url']))
{
  //variable
  $url = $_POST['url'];
  //verification
if (!filter_var($url, FILTER_VALIDATE_URL)) {
  //s'il c'est pas un lien
   header('location: ../?error=true&message=Adresse url non valide');
   exit();
}
  //création du racouris

  $shortcut = crypt($url, rand());

  //verifier si l'url à déjà eté envoyé dans le passé (optionel)
  $user = 'cole7746';
  $pass = 'bvCTubExjRfs';
  $bdd = new PDO('mysql:host=dibodev.com;dbname=cole7746_bitly;charset=utf8',$user, $pass);
  $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
  $req->execute(array($url));
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  while ($result = $req->fetch()) {
     if ($result['0'] != 0) {
       header('location: ../?error=true&message=Adresse déjà raccourcie');
       exit();
     }
  }

  // SEND
  $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES (?, ?)');
  $req->execute(array($url, $shortcut));

  header('location: ../?short='.$shortcut);
  exit();
}


?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Raccourcisseur d'url</title>
    <link rel="stylesheet" type="text/css" href="Style/style.css">
    <link rel="icon" href="Images/favico.png" />
    <link rel="icon" type="image/png" href="Images/favico.png" />
  </head>
  <body>
       <section id="hello">
              <header>
                  <img id="logo" src="Images/logo.png" alt="logo">

              </header>
              <div class="text-container">
                <h1>Une url longue racourcissez-là ?</h1>
                <h2>Largement meilleur et plus court que les autres.</h2>
              </div>
              <form class="url-form" method="post">

                <input type="url" name="url" placeholder="Collez un lien à raccourcir">
                <input type="submit" value="Raccourcir">

              </form>
              <?php

              // afficher sur le client url non valide
              if (isset($_GET['error']) && isset ($_GET['message'])) { ?>
                 <div class="center">
                    <div id="result">
                       <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                    </div>
                 </div>
             <?php }
             else if (isset($_GET['short'])) { ?>
               <div class="center">
                  <div id="result">
                     <b>URL RACCOURCIE : http://bitly.dibodev.com/?q=<?php

                          echo htmlspecialchars($_GET['short']);

                     ?></b>
                  </div>
               </div>
             <?php } ?>


       </section>
       <section id="brand">
            <div class="container">
                <h3>Ces marques nous font confiance</h3>
                <img class="picture" src="Images/1.png" alt="logo entreprise de entreprise magazines">
                <img class="picture" src="Images/2.png" alt="logo entreprise de kaiser permanente">
                <img class="picture" src="Images/3.png" alt="logo entreprise de pbs">
                <img class="picture" src="Images/4.png" alt="logo entreprise de montage">
            </div>
       </section>
       <footer id="end">
         <div class="container">
              <img id="Orange_logo" class="picture" src="Images/logo2.png" alt="logo orange bitly">
              <p>2018 © Bitly</p>
          <div class="link">
            <a href="#">
              <p>Contact</p>
            </a>
            <a href="#">
              <p>À propos</p>
            </a>
           </div>
         </div>
       </footer>
  </body>
</html>
