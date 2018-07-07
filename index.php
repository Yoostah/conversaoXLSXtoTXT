<html>
	<head>
		<style type="text/css">
		.jumbotron h1.page-header {
			font-weight: bold;
		}
		</style>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
		function reset(){
			location.reload();
		}

		$(document).ready( function(){
			$('td.ok').parent('tr').toggle();	
		});
			
		</script>
	</head>
	<body>
		<div class="jumbotron">
	        <div class="container text-center">
	             <h1 class="page-header" style="border-bottom: 1px solid #999 !important">CONVERSÃO DE PLANILHA</h1>
	        <p class="lead">Planilha MTK (.XLSX) para CORMED Apuracão (.TXT)</p>
	        </div>
	       <p style="text-align: center">       	
		     <input type="submit" name="resetar" onclick="reset()" value="Recarregar Planilha" class="btn-lg btn-primary">
	       </p>
	       <div class="container">
			<ul style="list-style: square outside">
				<li> <label style="color: red">REMOVER FORMATAÇÃO DO ARQUIVO</label></li>
				<li> <label style="color: red">SE FOR PC DE PUNIÇÃO, NO CAMPO METRAGEM O VALOR DEVERÁ SER PREENCHIDO COM ?</label></li>
				<li> <label style="color: red">SE FOR PC DE PUNIÇÃO, NO CAMPO TEMPO DEVERÁ SER PREENCHIDO</label></li>
			</ul>
							
		   </div>
		    			   
		</div>
		
		<div class='container'>
			
		<?php
		
		//print_r($_FILES['file']);
		if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {			
				//Seta o tamanho máximo do campo descrição
				$max_size = 45;

				include 'validar.php';
			
		}else{
			?>
			<form method="post" enctype="multipart/form-data">
			  <div class="form-group">		
			  	<p class="lead">Carregue a Planilha abaixo: </p>
			    <label for="file">Somente arquivos no formato *.XLSX</label>
			    <input type="file" class="form-control-file" id="file" name="file">
			    <input type="submit" value="Validar" class="btn btn-success" style="margin-top: 10px; padding: 6px 20px">
			  </div>
			</form>
			<?php			
		}

		?>

		</div>
	</body>

</html>
