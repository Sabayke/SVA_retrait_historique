<?php
/*
**Projet SmS Banking
** Service de Retrait d'argent
** Réalisé par:  Brahim Elmoctar
** mail: brahimelmoctar@yahoo.fr
** site officiel: https://brahimelmoctar.000webhostapp.com
** github : https://github.com/Sabayke
*/
//démarré une session et connexion à la base de donnée
include 'my_db.php';
/*recupération des variables */
		//html spécial chars c'est pour convertir les <> en &alt;
		$services     = htmlspecialchars($_POST['services']);
		$TypeUser	  = htmlspecialchars($_POST['TypeUser']);
		$login        = htmlspecialchars($_POST['login']);
		$mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
		$montant      = htmlspecialchars($_POST['montant']);
	// verification des champs
	if(empty($services) 
		AND empty($TypeUser) 
		AND empty($login) 
		AND empty($montant) 
		AND empty($mot_de_passe)){
		echo "ERREUR: Veuillez remplir tous les champs";
	}else
	{
		//si tout est bon
		$requete2 = $bdd->prepare("SELECT Type FROM user WHERE Type= '" . $_POST['TypeUser']. "' LIMIT 1");
		$requete2->execute(array($TypeUser));
		//verification si le client est prestataire ou pas
		$etat_type = $requete2->rowCount();
		//si oui on verifie si son login exist
		$requete1 = $bdd->prepare("SELECT Login_Id FROM user WHERE Login_Id= '" . $_POST['login']. "' LIMIT 1");
		$requete1->execute(array($login));
		$etat_login = $requete1->rowCount();
		if($etat_login==1)
		{
			if($etat_type==1)
			{
					//si oui est ce qu'il a assez d'argent dans son solde?
						$solde = $bdd->prepare("SELECT solde FROM compte WHERE Id_User_Login= '". $login ."' LIMIT 1");
						$solde->execute(array($login));
						$solde_user = $solde->fetch()[0];
						//si oui le montant doit être positive et le solde est >au montant
						if($montant>=1 && $solde_user>$montant)
						{
								// frais de rétrait =25Fcfa c'est pour tester 
								$frais1 =25;
								if($montant-$frais1>=100 AND $solde_user-$frais1>=100)
								{
									// si le montant doit être supérieur à 100Fcfa c'est-à-dire que le seuil ==100Fcfa
									$code_generator = rand();
									// rand une fonction aléatoire donc notre code est aléatoire
									//on affiche le code
									echo "votre code est : $code_generator";
									// on a fixé les ID pour simplifier notre travail
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
									// on fait une mise à jour à la table compte solde = solde -transaction-frais
									$mise_a_jour3 = $bdd->prepare("
									UPDATE compte
									SET Solde = Solde-'". $montant ."'-'". $frais1 ."' WHERE Id_User_Login= '". $login ."' LIMIT 1");
										$mise_a_jour3->execute(array($montant));
									//montant est une variable session pour faire une verification
									$_SESSION['montant']= $montant;
									//echo "Votre pouvez effectuer le retrait à n'importe quel point";
									/* gérer la validité du code
									$requete_date = $bdd->prepare("SELECT Date_de_creation FROM code_retrait WHERE Id_Client= '". $id_compte1 ."' LIMIT 1");
									$requete_date->execute();
									$date_de_creation = $requete_date->fetch()[0]; //date de création du code
									$date_debut= strtotime($date_de_creation); //converting time with strtotime
									$date_du_serveur = time(); // date du serveur
									$date_limite = 2*24*60*60; // date d'éxpiration du code
									if($date_du_serveur-$date_debut==$date_limite)
										{ // si la difference == 2jours
											// le code est invalide
											$expire= "expiré";
											$mise_a_jour4 = $bdd->prepare("
											UPDATE code_retrait
											SET Etat = '". $expire ."' WHERE Num_Code= '". $code_generator ."' LIMIT 1");
											$mise_a_jour4->execute(array($expire));
											echo "Votre code n'est plus valide";
											
										}else{
												//le code est toujours valide
											}*/
								}else{
									echo "Veuillez charger votre solde pour effectuer cette opération";
									}
						}else{
						    echo "Votre solde ne vous permez pas d'effectuer cette opération";
							}
			}else{
			echo "Erreur: Le type n'est pas associé à un login";
			}
		}else {
			echo "Mauvais login !!! avez vous un compte !";
			}
	}
?>