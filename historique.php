<?php
    include '../Test_Php/my_db.php';
// historique de table retrait 
$fromRetrait = $bdd->query('SELECT * FROM code_retrait');
$fromCompte = $bdd->query('SELECT * FROM compte ');
$fromTransaction = $bdd->query('SELECT * FROM transaction ');
$fromUser= $bdd->query('SELECT * FROM user ');

// On affiche chaque entrée une à une
while ($retrait = $fromRetrait->fetch() AND $compte = $fromCompte->fetch() AND $transaction = $fromTransaction->fetch() AND $user = $fromUser->fetch());
{
?>


    <center><h1>L'historique des transaction </h1></center>

		<h2>Utilisateurs</h2>
		<ul>
		  <li>Prenom : <?php echo $user['Prenom']; ?></li>
		  <li>Nom    : <?php echo $user['Nom']; ?></li>
		  <li>Login  : <?php echo $user['Login_Id']; ?></li>
		  <li>Numero : <?php echo $user['NumTel']; ?></li>
		  <li>Numero : <?php echo $user['Etat_Type']; ?></li>
		</ul>


		<h2>Les information de son comte</h2>
		<ul> 
		  <li>Login : <?php echo $compte['Id_User_Login'];  ?></li>
		  <li>Solde : <?php echo $compte['Solde']; ?></li>
		  <li>Type  : <?php echo $compte['Type_Compte']; ?></li>
		  <li>Code  : <?php echo $compte['code'];?></li>
		</ul>


       <h2>Les activites du coompte</h2>
		<ul>
		  <li>Num_code        : <?php echo $retrait['Num_Code']; ?></li>
		  <li>Identifiant     : <?php echo $retrait['Id_Client']; ?></li>
		  <li>Etat            : <?php echo  $retrait['Etat'];?></li>
		  <li>Le montant      : <?php echo $retrait['Montant'] ?></li>
		  <li>Ldate_Creation  : <?php echo  $retrait['Date_de_Creation']?></li>
		</ul>


     
		<h2>Les transactions</h2>
		<ul>
		  <li>Compte N 1  : <?php echo $transaction['Id_Compte1']; ?></li>
		  <li>Compte N 2  : <?php echo $transaction['Id_compte2']; ?></li>
		  <li>type_compte : <?php echo $transaction['Type_Transaction']; ?></li>
		  <li>frait_transactions  : <?php echo $transaction['frais_Transaction']; ?></li>
		  <li>Le montant  : <?php echo $transaction['montant']; ?></li>
		   <li>La date    : <?php echo $transaction['date_de_transaction']; ?></li>
		</ul>

<?php
$fromRetrait->closeCursor(); // Termine le traitement de la requête
$fromCompte->closeCursor();
$fromTransaction->closeCursor();
$fromUser->closeCursor();
}
 ?>