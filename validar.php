
<table class= "table" border="1" cellpadding="5" style="border-collapse: collapse">


<?php

require_once __DIR__ . '/simplexlsx.class.php';

if ( $xlsx = SimpleXLSX::parse( $_FILES['file']['tmp_name'] ) ) {


	for ($aba = 1; $aba <= $abas; $aba++){
	?>
	<tr>
	<td valign="top">
		<h1 class="page-header" style="margin: 20px 0 20px !important">ABA - <?php echo $aba ?></h1>
			<table class= "table table-bordered" id="<?php echo 'planilha'.$aba ?>">
				<tr class="info">
					<th>Nº PC</th>
					<th>TRECHO</th>
					<th>METRAGEM</th>
					<th>TEMPO</th>
					<th>TIPO</th>
					<th>DESCRIÇÃO</th>
					<th>ERRO</th>
				</tr>
	<?php			
	// leitura por aba
	list( $num_cols, $num_rows ) = $xlsx->dimension();
	$erros_planilha = 0;
	$abax = $aba - 1;
	$linha = 1;
	$txt = '';
	foreach ( $xlsx->rows( $abax ) as $r ) {		
		$erro_linha = 0;
		echo '<tr>';
		for ( $i = 0; $i < $num_cols; $i ++ ) {
			/*if ($i == 23){
				var_dump($r);
			}else{
				break;
			}*/


				switch ($i) {
					case '0': //Nº DO PC
						if (is_int($r[ $i ])){
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt .= $r[ $i ].';';
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;
					
					case '1': //TRECHO
						if (is_int($r[ $i ])){
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt .= $r[ $i ].';';
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;

					case '2': //METRAGEM
						if (is_int($r[ $i ])){
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt .= $r[ $i ].';';
						}elseif(trim($r[ $i ]) == '?'){ //Trecho Omitido por ser PC DE PUNIÇÃO
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt .= $r[ $i ].';';
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;

					case '3': //TEMPO						
						if(is_numeric($r[ $i ])){
							echo '<td>' .  $r[ $i ] . '</td>';
							$txt .= $r[ $i ].';';
						}
						else{
							echo '<td class="danger">' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
							$erros_planilha++;
							$erro_linha++;
						}
						break;				

					case '4': //TIPO
						if (trim(strtoupper($r[ $i ])) == 'VIRTUAL'){
							echo '<td>' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
							$txt .= $r[ $i ].';';	
						}else{
							echo '<td>' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
							$txt .= $r[ $i ].';';
						}
						break;	

					case '5': //DESC
						if (strlen(trim($r[ $i ])) > $max_size){
							echo '<td class="warning">' . trim(substr($r[ $i ],0,$max_size)) . '</td>';
							$txt .= $r[ $i ].';'."\r\n";	
						}else{
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt .= $r[ $i ].';'."\r\n";
							
						}
						
						break;	

					default:
						echo '<td>' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
						break;
				}

			

			
		}
		if ($erro_linha != 0){
					echo '<td class="erro">ERRO LINHA ['.$linha.']</td>';
				}else{
					echo '<td class="ok">OK</td>';
				}
		echo '</tr>';
		$linha++;
	}
	
	
			if ($erros_planilha == 0){

				$myfile = fopen("./generated/aba_".$aba.".txt", "w") or die("Unable to open file!");
				fwrite($myfile, $txt);
				fclose($myfile);
				?>
				<tr><td colspan="6"><label>NENHUM ERRO ENCONTRADO</label></td>
				<td><input type="button" value="Exportar ABA para TXT" class="btn btn-danger" style="padding: 6px 20px" onclick="location.href='download.php?aba=<?php echo $aba ?>'"></td>
				<?php
			}else{
				echo '<tr><td colspan="6"><label>TOTAL DE ERROS ENCONTRADOS: '.$erros_planilha.'</label></td>';
				echo '<td><label style="color:red"><= CORRIJA OS ERROS PARA EXPORTAR PARA TXT</label></td>';
			}
	

	echo '</tr></table>';

	echo '</td>';
	echo '</tr>';
	
	}
	


} else {
	echo SimpleXLSX::parse_error();
}
?>
</table>