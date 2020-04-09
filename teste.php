<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hcode Store</title>

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/res/site/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/res/site/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/res/site/css/owl.carousel.css">
    <link rel="stylesheet" href="/res/site/css/style.css">
    <link rel="stylesheet" href="/res/site/css/responsive.css">
    <body>


<?php
require_once('/home/andre/pass.php');

class Sql {

	const HOSTNAME = "127.0.0.1";
	const USERNAME = "aandre100";
	const PASSWORD = PASS;
	const DBNAME = "spzn_bd";
  const CHARSET = "utf8";

	private $conn;

	public function __construct()
	{

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME.";charset=".Sql::CHARSET,
			Sql::USERNAME,
			Sql::PASSWORD
		);

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {

			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}
function getOrders(){
  $sql = new Sql();
  $results = $sql->select("
  SELECT * FROM empresas_empresa WHERE actividade_id=7
  ");
  return $results;
}
function _convert($content) {
    if(!mb_check_encoding($content, 'UTF-8')
        OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {

        $content = mb_convert_encoding($content, 'UTF-8');

        if (mb_check_encoding($content, 'UTF-8')) {
            // log('Converted to UTF-8');
        } else {
            // log('Could not converted to UTF-8');
        }
    }
    return $content;
}
$cena = getOrders(); ?>


<?php
foreach($cena as $key => $value){ ?>

  <?php echo $value['titulo']; ?><br>
  <?php echo strip_tags($value['telefone']); ?><br>
  <?php echo strip_tags($value['morada']); ?><br>
  <?php echo strip_tags($value['cod_postal']); ?><br>
  <?php echo strip_tags($value['localidade']); ?><br>
  <?php echo strip_tags($value['descricao_actividade']); ?><br>
  <p>&nbsp;</p>


<?php
 }

 ?>
</body>
</html>
