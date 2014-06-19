<html>
	
	<head>

		<title>Servidor GCM</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
			<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="includes/css/bootstrap-glyphicons.css" rel="stylesheet">
			
			<link rel="stylesheet" href="includes/css/styles.css">
			
			<link rel="stylesheet" href="css/main.css">
			
			<script src="includes/js/modernizr-2.6.2.min.js"></script>	

			<link href="http://fonts.googleapis.com/css?family=Cabin" rel="stylesheet"></link>
	</head>
	
	<body>
	
		<div class="center-block">
		<div class="container text-center">
			<h1 id="titulo">
				Painel de Mensagens
			</h1>
		</div>
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
			
			}
		?>
	
		<div class="container text-center">
			<form method="post" action="gcm_index.php">				
				<textarea name="message" class="text" cols=40 rows=6 placeholder="Escreva sua Mensagem"></textarea> 
				<input type="submit" name="submit" class="btn btn-default" value="Enviar" />
			</form>
		</div>
		</div>
		
	</body>
	
</html>