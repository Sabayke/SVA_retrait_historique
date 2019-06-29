<?php
/*
**Projet SmS Banking
** Service de Retrait d'argent
** Réalisé par:  Brahim Elmoctar
** mail: brahimelmoctar@yahoo.fr
** site officiel: https://sabaykebremso.me
** github : https://github.com/Sabayke
*/
include "../Messages/SMSApi.php";
use Messages\SMSApi;
//démarré une session et connexion à la base de donnée
include 'my_db.php';
/*recupération des variables */
		//html spécial chars c'est pour convertir les <> en &alt;
		$numtel = $_POST['numtel'];
		$montant = $_POST['montant'];
	// verification des champs
	if(empty($numtel) AND empty($montant)){
		echo "ERREUR: Veuillez remplir tous les champs";
	}else
	{
		//si oui on verifie si le numéro exist ou pas 
		$requete1 = $bdd->prepare("SELECT Num_Tel FROM utilisateur WHERE Num_Tel= '" . $numtel . "' LIMIT 1");
		$requete1->execute(array($numtel));
		//var_dump($requete1);
		$etat_num = $requete1->rowCount();
		if($etat_num==1)
		{
			//le numéro exist
						//vérification du solde
						
						$solde = $bdd->prepare("SELECT `Solde` FROM `compte` INNER JOIN `utilisateur` on `compte`.`Id_User`=`utilisateur`.`Login` WHERE `Num_Tel`='".$numtel."' LIMIT 1");
						$solde->execute(array($numtel));
						$solde_user = $solde->fetch()[0];
						//var_dump();
		
						//si oui le montant doit être positive et le solde est supérieur au montant rétiré
						if(!empty($montant)){
						if($montant>=1 && $solde_user>$montant)
						{
								// frais de rétrait =25Fcfa c'est pour tester 
								$frais1 =25;
								if($montant-$frais1>=100 AND $solde_user-$frais1>=100)
								{
									// le montant doit être supérieur à 100Fcfa c'est-à-dire que le seuil ==100Fcfa
									$code_generator = random_int(1000, 9999);
									// random_int() une fonction qui génére un code aléatoire et unique
									//on affiche le code avec echo 
									// on a fixé les ID pour simplifier notre travail
									/* les clés etrangers sont id_compte1 et id_compte2
									SELECT `Solde` FROM `compte` INNER JOIN `transaction` on `compte`.`Id_Compte`=`compte`.`Id_Compte` WHERE `Id_Compte_1`='1' LIMIT 1;*/
									$compte= $bdd->prepare("SELECT `Id_Compte` FROM `compte` WHERE `Solde`= '".$solde_user."' LIMIT 1");
											$compte->execute(array($numtel));
												$id_compte = $compte->fetch()[0];
									$services=   "retrait";
									// on insére les données dans la table transactions
									//$id_transaction = random_int(1, 100);
									$transaction_retrait= $bdd->prepare("INSERT INTO transaction 
									(Id_Compte_1,Id_Compte_2,Type_Transaction,Frais_Transaction,Montant)
										VALUES('". $id_compte ."', '". $id_compte ."','". $services ."', '". $frais1 ."', '". $montant ."' )");

									$transaction_retrait->execute(array($id_compte,$id_compte,$services,$frais1,$montant));
									$Etat= "inutilise";
									// on insére les données dans la table code_retrait
									/* Id_client clé etrangers*/
									/*$id_client="sabayke";*/
									$id_client_num = $bdd->prepare("SELECT Login FROM Utilisateur WHERE Num_Tel= '".$numtel."' LIMIT 1");
									$id_client_num->execute(array($numtel));
									$Id_client_login=$id_client_num->fetch()[0];
									$id=random_int(1, 30);
									$insertclient = $bdd->prepare("INSERT INTO code_retrait(Id, Num_Code, Id_Client, Etat, Montat) VALUES('".$id."', '". $code_generator ."', '". $Id_client_login ."', '". $Etat ."', '". $montant ."')");
									$insertclient->execute(array($id,$code_generator, $Id_client_login, $Etat, $montant));
									// on fait une mise à jour à la table compte solde = solde -transaction-frais
									$mise_a_jour3 = $bdd->prepare("
									UPDATE compte
									SET Solde = Solde-'". $montant ."'-'". $frais1 ."' WHERE Id_User= '". $Id_client_login ."' LIMIT 1");
										$mise_a_jour3->execute(array($montant));
									//on ajoute les frais dans notre compte "admin"
									//on ajoute les frais dans le compte systeme
									$admin="admin";
									$mise_a_jour4 = $bdd->prepare("
									UPDATE compte
									SET Solde = Solde +'". $frais1 ."' WHERE Id_User= '". $admin ."' ");
										$mise_a_jour4->execute(array($frais1));
									echo "votre code est : $code_generator";
									sendSMS($numtel, "votre code est :$code_generator");
									$_SESSION['numtel'] = $numtel;
									
									/* 
									@param envoyé le numéro de téléphone à la page historique
									*/
						}else{
						    echo "Votre solde ne vous permez pas d'effectuer cette opération";
							}
								}else{
									echo "Veuillez charger votre solde pour effectuer cette opération";
									}
								}else{echo "Veuillez renseigner le montant";} 
		}else {
			echo "Mauvais numéro !!! avez vous un compte !";
			}
	}
function sendSMS($numDst, $message){
    //$config = [];
    //$sms = new SMSApi($config);
    $sms = new SMSApi();
    $senderAddress = "tel:+221770000000";
    $sms->sendSMS($senderAddress,"tel:+221".$numDst,$message,"TESTSMS");
    return $sms->getSMSBalance();
}
?>