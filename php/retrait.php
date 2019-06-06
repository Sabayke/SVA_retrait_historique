<?php
include 'my_db.php';
/*code true = on l'a pas encore utilisé
le code par defaut est true */
		$services     = htmlspecialchars($_POST['services']);
		$TypeUser	  = htmlspecialchars($_POST['TypeUser']);
		$login        = htmlspecialchars($_POST['login']);
		$mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
		$montant      = htmlspecialchars($_POST['montant']);
	//controle
	if($services=''){echo "veuillez choisir le service";}
	if($TypeUser=''){echo "Qu'êtes vous ?";}
	if($login=''){echo "veuillez rensigner votre login";}
	if($mot_de_passe=''){echo "veuillez ajouter votre mot de passe";}
	if($montant=''){echo "veuillez ajouter le montant à retirait";}
	// a changé
	if(empty($services) 
		AND empty($TypeUser) 
		AND empty($login) 
		AND empty($montant) 
		AND empty($mot_de_passe)){
		echo "ERREUR: Veuillez remplir tous les champs";
	}
	else {

		$requete2 = $bdd->prepare("SELECT Type FROM user WHERE Type= '" . $_POST['TypeUser']. "' LIMIT 1");
		$requete2->execute(array($TypeUser));
		$etat_type = $requete2->rowCount();
		$requete1 = $bdd->prepare("SELECT Login_Id FROM user WHERE Login_Id= '" . $_POST['login']. "' LIMIT 1");
		$requete1->execute(array($login));
		$etat_login = $requete1->rowCount();
		if($etat_login==1){
			if($etat_type==1){
			//le login est exact mais est ce qu'il a assez d'argent?
				$solde = $bdd->prepare("SELECT solde FROM compte WHERE Id_User_Login= '". $login ."' LIMIT 1");
				$solde->execute(array($login));
				$solde_user = $solde->fetch()[0];
				if($montant>=1 && $solde_user>$montant){
					$frais1 =25;
					if($montant-$frais1>=100 AND $solde_user-$frais1>=100)
					{
						$code_generator = rand();
						echo "votre code est : $code_generator";
						// pour tester seulement
						$id_compte1= "sabayke";
						$id_compte2= "bremso";
						// on insére les données dans la table transactions
						$transaction_retrait= $bdd->prepare("INSERT INTO transaction 
						(Id_Compte1,Id_compte2,Type_Transaction,frais_Transaction,montant)
							VALUES( '". $id_compte1 ."', '". $id_compte2 ."','". $services ."', '". $frais1 ."', '". $montant ."' )");
						$transaction_retrait->execute(array($id_compte1,$id_compte2,$services,$frais1,$montant));
						$Etat= "inutilisé";
						// on insére les données dans la table code_retrait
						$insertclient = $bdd->prepare("INSERT INTO code_retrait(Num_Code, Id_Client, Etat, Montat) VALUES('". $code_generator ."', '". $id_compte1 ."', '". $Etat ."', '". $montant ."')");
						$insertclient->execute(array($code_generator, $id_compte1, $Etat, $montant));
						// on fait update à la table compte solde = solde -transaction-frais
						$mise_a_jour3 = $bdd->prepare("
						UPDATE compte
						SET Solde = Solde-'". $montant ."'-'". $frais1 ."' WHERE Id_User_Login= '". $login ."' LIMIT 1");
							$mise_a_jour3->execute(array($montant));
						$_SESSION['montant']= $montant;
						header("Location: ../Test_Js/confirl_retrait.html");
						exit();
						//echo "Votre pouvez effectuer le retrait à n'importe quel point";
					}else{
							echo "Veuillez charger votre solde pour effectuer cette opération";
							}
					}else{
					echo "Votre solde ne vous permez pas d'effectuer cette opération";}
	
				}else echo "Erreur: Le type n'est pas associé à un login";
		}else echo "Mauvais login !!! avez vous un compte !";
			}
?>