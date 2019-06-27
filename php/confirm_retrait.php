<?php
/*
**Projet SmS Banking
** Service de Retrait d'argent
** Réalisé par:  Brahim Elmoctar
** mail: brahimelmoctar@yahoo.fr
** site officiel: https://sabaykebremso.me
** github : https://github.com/Sabayke
*/
if(isset($_POST['confirmer'])){
include 'my_db.php';
// on démarre une session
	// récupérons les variables avec le tableau associatif POST

	$code    		= htmlspecialchars($_POST['code']);
	if(!empty($code)){
		// si le champs code n'est pas vide 
				//on verifie  le montant
				$verif_requete = $bdd->prepare("SELECT Num_Code FROM code_retrait WHERE Num_Code= '" . $code . "' LIMIT 1");
				$verif_requete->execute(array($code));
				$etat_code = $verif_requete->rowCount();
				// verifions si le code existe
				if($etat_code==1){
					// verification si le code est déjà utilisé
					$verif_requetes = $bdd->prepare("SELECT Etat FROM code_retrait WHERE Num_Code= '" . $code . "' LIMIT 1");
					$verif_requetes->execute(array($code));
						$utilisation = $verif_requetes->fetch()[0];
						//var_dump($utilisation);
						if($utilisation == 'inutilise')
						{
					/* gérer la validité du code */
							$requete_date = $bdd->prepare("SELECT Date_de_Creation FROM code_retrait WHERE Num_Code= '". $code ."' LIMIT 1");
							$requete_date->execute();
							$date_de_creation = $requete_date->fetch()[0]; //date de création du code
							// recupérons le code et stockons le dans la variable $date_de_creation
							$date_debut= strtotime($date_de_creation); //converting time with strtotime
							//convertissons la date qui est une chaine de caractére en date en utilisant 
							//la fonction strtotome (string to  time)
							$date_du_serveur = time(); // date du serveur
							//recupérons la date du serveur
							$date_limite = 2*24*60*60; // date d'éxpiration du code
							if($date_du_serveur-$date_debut>=$date_limite )
								{ // si la difference est <= 2jours
									// le code est invalide
									$expire= "expire";
									$mise_a_jour4 = $bdd->prepare("
										UPDATE code_retrait
											SET Etat = '". $expire ."' WHERE Num_Code= '". $code ."' LIMIT 1");
									$mise_a_jour4->execute(array($expire));
											$erreur= "Votre code n'est plus valide";
											
										}else{
												//le code est toujours valide mais il est utilisé
												$Etat= "utilise";
												$mise_a_jour1 = $bdd->prepare("
													UPDATE code_retrait
														SET Etat= '". $Etat ."' WHERE Num_Code = '" . $code. "' ");
													$mise_a_jour1->execute(array($code));	
													
																$success= "Félicitation le retrait à etait fait avec succés";
											}
					
	}else{
		$erreur= "le code est déjà utilisé";
	}
					
					}else{
						$erreur= "le code est incorrect";}
				
	}else {
$erreur= "Veuillez remplir tous les champs";}
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <div class="container"> <a class="navbar-brand" href="#">
                        <b> SMS Banking |</b> Espace gestionnaire
                    </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                        data-target="#navbar4">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar4">
                        <ul class="navbar-nav ml-auto">
                        </ul> 
                        <a class="btn navbar-btn ml-md-2 btn-light" >Deconnexion</a>
                    </div>
                </div>
            </nav>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center text-success"><u>Validation du retrait</u></h2>
               
				<form name="mon_formulaire" method="POST" action="confirm_retrait.php">
                    <div class="form-group">
							
                        <label for="code">code de retrait:</label>
                        <input type="text" class="form-control" id="code" name="code"
                            placeholder="code de retrait" required maxlength="4">
                    
					</div>
                    
                    <input class="btn btn-success" type="submit" name="confirmer" value="Valider">
					<?php
				//erreur
				echo "<p style='color:red'></p>";
				     if(isset($erreur)) {
					   echo '<font color="red">'.$erreur."</font>";}
				//succees
				echo "<p style='color:green'></p>";
				     if(isset($success)) {
					   echo '<font color="green">'.$success."</font>";
					    }
					   ?>
				</form>
            </div>
        </div>
    </div>
    
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>