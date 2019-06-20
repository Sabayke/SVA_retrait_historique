<?php
/*
**Projet SmS Banking
** Service de Retrait d'argent
** Réalisé par:  Brahim Elmoctar
** mail: brahimelmoctar@yahoo.fr
** site officiel: https://brahimelmoctar.000webhostapp.com
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
						if($utilisation == 'inutilisé')
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
									$expire= "expiré";
									$mise_a_jour4 = $bdd->prepare("
										UPDATE code_retrait
											SET Etat = '". $expire ."' WHERE Num_Code= '". $code ."' LIMIT 1");
									$mise_a_jour4->execute(array($expire));
											echo "Votre code n'est plus valide";
											
										}else{
												//le code est toujours valide mais il est utilisé
												$Etat= "utilisé";
												$mise_a_jour1 = $bdd->prepare("
													UPDATE code_retrait
														SET Etat= '". $Etat ."' WHERE Num_Code = '" . $code. "' ");
													$mise_a_jour1->execute(array($code));	
													$mise_a_jour2 = $bdd->prepare("
														UPDATE code_retrait
															SET Montat = '". $montant . "' WHERE Num_Code = '". $code ."' ");
																$mise_a_jour2->execute(array($code));
																echo "Félicitation le retrait à etait fait avec succés";
											}
					
	}else{
		echo "le code est déjà utilisé";
	}
					
					}else{
						echo "le code est incorrect";}
				
	}else {
echo "Veuillez remplir tous les champs";}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head> 
	 <meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css"></link>
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"></link>
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap-grid.css"></link>
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap-grid.min.css"></link>
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap-reboot.css"></link>
	 <link rel="stylesheet" type="text/css" href="../css/bootstrap-reboot.min.css"></link>
	 <title> Retrait d'argent</title>

</head>
<body>
  <center>
		<h4> Service de retrait d'argent </h4>
		<form name="mon_formulaire" method="POST" action="confirm_retrait.php">
		<table>
			<tr>
				<td>
					<label> Veuillez saisir le code :</label>
				</td>
				<td>
					<input class="form-control" type="text" name="code" id="code" maxlength="4">
				</td>
			</tr>
			<tr>
				<td>
					<input class="btn btn-success" type="submit" name="confirmer" value="Valider">
				</td>
			</tr>
		</table>
		</form>
</center>
</body>
</html>
