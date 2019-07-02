<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/font.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <?php
  include 'my_db.php';
  $numtel = 773244176;
  //La requete sql
  $req_2 = $bdd->prepare("SELECT t.Id_Transaction, t.Type_Transaction, t.Frais_Transaction, t.Montant, t.Date_Transaction, c.Solde FROM transaction t INNER JOIN compte c INNER JOIN utilisateur u on t.Id_Compte_1 = c.Id_Compte OR t.Id_Compte_2 = c.Id_Compte WHERE u.Num_Tel='".$numtel."' AND c.Type_Compte = 'client' ORDER BY Date_Transaction LIMIT 5 ");
  $req_2->execute(array($numtel));
  // Verifier si le requete n'est pas vide
  $reqExist = $req_2->rowCount();
  ?>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container"> <a class="navbar-brand" href="#">
        <b> SMS Banking |</b> Espace Client/Prestataire </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar4">
        <ul class="navbar-nav ml-auto">
        </ul>
        <a class="btn navbar-btn ml-md-2 btn-light">Deconnexion</a>
      </div>
    </div>
  </nav>
  <div class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
                <h2 class="text-center text-success"><u>Historique des transactions</u></h2>

      <?php   if ($reqExist > 0) { ?>
          <div class="table-responsive">
            <table class="table table-bordered ">
              <thead class="thead-success">
                <tr>
                  <th>Id Transaction</th>
                  <th>Date de transaction </th>
                  <th>Montant</th>
                  <th>Type de Transaction</th>
                  <th>Frait</th>
                  <th>Solde_restant</th>
                </tr>
              </thead>
              <tbody>
            <?php
              while( $transaction = $req_2->fetch()){
	//			  var_dump($transaction);
            // On affiche chaque entrée une à une
             ?>
                <tr>
                  <td><?php echo $transaction['Id_Transaction']; ?></td>
                  <td><?php echo $transaction['Date_Transaction']; ?></td>
                  <td><?php echo $transaction['Montant']; ?></td>
                  <td><?php echo $transaction['Type_Transaction'];?></td>
                  <td><?php echo $transaction['Frais_Transaction'];?></td>
                  <td><?php echo $transaction['Solde']; ?></td>

                </tr>
              <?php
                  }
                }
               // si la requete est vide
                else {
                  echo "Vous n'avez pas encore fait de transaction";
                }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.3.1.slim.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

</body>

</html>
