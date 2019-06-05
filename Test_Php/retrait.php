<?php
include 'my_db.php';
/*code true = on l'a pas encore utilisé
le code par defaut est true */
		$services     = $_POST['services'];
		$login        = $_POST['login'];
		$mot_de_passe = $_POST['mot_de_passe'];
		$montant      = $_POST['montant'];
	// a changé
	if(empty($services) AND empty($login) AND empty($montant) AND empty($mot_de_passe)){
		echo "ERREUR: Veuillez remplir tous les champs";
	}
	else {

		$requete1 = $bdd->prepare("SELECT Login_Id FROM user WHERE Login_Id= '" . $_POST['login']. "' LIMIT 1");
		
		$requete1->execute(array($login));
		$etat_login = $requete1->rowCount();
		
		if($etat_login==1){
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
						$transaction_retrait= $bdd->prepare("INSERT INTO transaction 
						(Id_Compte1,Id_compte2,Type_Transaction,frais_Transaction,montant)
							VALUES( '". $id_compte1 ."', '". $id_compte2 ."','". $services ."', '". $frais1 ."', '". $montant ."' )");
						$transaction_retrait->execute(array($id_compte1,$id_compte2,$services,$frais1,$montant));
						$Etat= "inutilisé";
						
						$insertclient = $bdd->prepare("INSERT INTO code_retrait(Num_Code, Id_Client, Etat, Montat) VALUES('". $code_generator ."', '". $id_compte1 ."', '". $Etat ."', '". $montant ."')");
						$insertclient->execute(array($code_generator, $id_compte1, $Etat, $montant));
						$_SESSION['montant']= $montant;
						//echo "Votre pouvez effectuer le retrait à n'importe quel point";
					}else{
							echo "Votre solde ne vous permez pas d'effectuer cette opération";
							}
					}else{
					echo "Veuillez charger votre solde";}
	
				}else echo "vous n'avez pas de compte";
			}
?>