<html>
	
	<head>
		<title>Servidor GCM</title>
	</head>
	
	<body>
	 <?php
	 
		if(isset($_POST['submit'])){
		$con = mysql_connect("localhost", "root","");
		if(!$con){
			die('MySQL connection failed');
		}
		
		// Banco de dados no MySQL chamado "gcm"
		$db = mysql_select_db("gcm");
		if(!$db){
			die('Erro ao selecionar banco de dados');
		}
		
		$registatoin_ids = array();
		// Tabela no MySQL chamada "reg_id"
		$sql = "SELECT * FROM reg_id";
		$result = mysql_query($sql, $con);
		while($row = mysql_fetch_assoc($result)){
			array_push($registatoin_ids, $row['reg_id']);
		}
		 
		// Variáveis para execução do POST
		$url = 'https://android.googleapis.com/gcm/send';
		$message = array("title" => $_POST['message']);
		$fields = array(
			 'registration_ids' => $registatoin_ids,
			 'data' => $message,
		);
		
		$headers = array(
			// Chave para o projeto no Google
			 'Authorization: key=AIzaSyBQgfgQpj_CnClMeI9FeU1-AL9QOpKzxYY',
			// JSON como padrão para o envio dos dados
			 'Content-Type: application/json'
		);
		// Abre uma conexão
		$ch = curl_init();
		
		// Atribuição das variávieis para o POST
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		// Envia para o servidor GCM, por meio do POST
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Erro: ' . curl_error($ch));
		}
		
		// Fecha conexão
		curl_close($ch);
		echo $result;
		}
	?>
	
	<form method="post" action="gcm_index.php">
		<label>Insert Message: </label><input type="text" name="message" />		 
		<input type="submit" name="submit" value="Send" />
	</form>
		
	</body>
	
</html>