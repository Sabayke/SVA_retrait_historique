<?php
include 'my_db.php';
	$montant_saisie = $_POST['montant_saisie'];
	$montant		= $_SESSION['montant'];
	$code    		= $_POST['code'];
	if(!empty($montant_saisie) AND !empty($code)){
			if($montant_saisie==$montant){
				$verif_requete = $bdd->prepare("SELECT * FROM code_retrait WHERE Num_code= '" . $_POST['code']. "' LIMIT 1");
				$verif_requete->execute(array($code));
				$etat_code = $verif_requete->rowCount();
				if($etat_code==1){
					$Etat= "utilisé";
					$mise_a_jour1 = $bdd->prepare("
						UPDATE code_retrait
						SET Etat= '". $Etat ."' WHERE Num_code = '" . $code. "' ");
							$mise_a_jour1->execute(array($code));
	
					$mise_a_jour2 = $bdd->prepare("
						UPDATE code_retrait
						SET Montat = '". $montant . "' WHERE Num_code = '". $code ."' ");
							$mise_a_jour2->execute(array($code));
					echo "Félicitation le retrait à etait fait avec succés";
					}else{
						echo "le code est incorrect";}
				}else{
					echo "le deux montant ne sont pas identique";
				}
	}else
	echo "Veuillez remplir tous les champs";
?>
