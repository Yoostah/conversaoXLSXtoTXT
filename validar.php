
<table class= "table" border="1" cellpadding="5" style="border-collapse: collapse">


<?php

require_once __DIR__ . '/simplexlsx.class.php';

if ( $xlsx = SimpleXLSX::parse( $_FILES['file']['tmp_name'] ) ) {

	for ($aba = 1; $aba <= $xlsx->sheetsCount(); $aba++){
	?>
	<tr>
	<td valign="top">
		<h1 class="page-header" style="margin: 20px 0 20px !important">ABA - <?php echo $xlsx->sheetName($aba-1) ?></h1>
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
	$exp = 'TipoPC;Trecho;NúmeroPC;Nome;HorárioIdeal;DistânciaIdeal;Observação;'."\r\n";
	foreach ( $xlsx->rows( $abax ) as $r ) {
		$txt = array();

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
							$txt['numero'] = sprintf("%02d", trim($r[ $i ]));
							$txt['descricao'] = 'PC'.sprintf("%02d", trim($r[ $i ])).'-';
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;
					
					case '1': //TRECHO
						if (is_int($r[ $i ])){
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt['trecho'] = sprintf("%02d", trim($r[ $i ]));
							$txt['obs'] = 'T'.trim($r[ $i ]);
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;

					case '2': //METRAGEM
						if (is_int($r[ $i ])){
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt['metragem'] = trim($r[ $i ]);
							$txt['obs'] .= '-'.trim($r[ $i ]).'M';
						}elseif(trim($r[ $i ]) == '?'){ //Trecho Omitido por ser PC DE PUNIÇÃO
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt['metragem'] = 0;
							$txt['obs'] .= '-'.'PUNIÇÃO';
						}else{
							echo '<td class="danger">' . trim($r[ $i ]) . '</td>';
							$erros_planilha++;
							$erro_linha++;	
						}
						break;

					case '3': //TEMPO
						if( !empty($r[ $i ]) ){
							$tempo = explode(' ', $r[ $i ]);
							echo '<td>' .  $tempo[1] . '</td>';
							$fmt_hora = explode(':',trim($tempo[1]));
							$txt['hora'] = intval($fmt_hora[0]).','.$fmt_hora[1].$fmt_hora[2].'00';
						}
						else{
							echo '<td class="danger">' . $r[ $i ] . '</td>';
							$erros_planilha++;
							$erro_linha++;
						}
						break;				

					case '4': //TIPO
						if (trim(strtoupper($r[ $i ])) == 'VIRTUAL'){
							echo '<td>' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
							$txt['tipo'] = 'D';	
						}else{
							echo '<td>' . ( ( ! empty( $r[ $i ] ) ) ? trim($r[ $i ]) : '&nbsp;' ) . '</td>';
							$txt['tipo'] = 'H';
						}
						break;	

					case '5': //DESC
						if (strlen(trim($r[ $i ])) > $max_size){
							echo '<td class="warning">' . trim(substr($r[ $i ],0,$max_size)) . '</td>';
							$txt['descricao'] .= trim(substr($r[ $i ],0,$max_size));	
						}else{
							echo '<td>' . trim($r[ $i ]) . '</td>';
							$txt['descricao'] .= trim($r[ $i ]);
							
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
		$exp .= $txt['tipo'].';'.$txt['trecho'].';'.$txt['numero'].';'.$txt['descricao'].';'.$txt['hora'].';'.$txt['metragem'].';'.$txt['obs'].';'."\r\n";
		$linha++;
	}
	
	
	if ($erros_planilha == 0){

		$myfile = fopen("./generated/aba_".trim($xlsx->sheetName($aba-1)).".txt", "w") or die("Unable to open file!");
		fwrite($myfile, mb_convert_encoding($exp, "ISO-8859-1", mb_detect_encoding($exp, "UTF-8, ISO-8859-1, ISO-8859-15", true)));
		fclose($myfile);
		?>
		<tr><td colspan="6"><label>NENHUM ERRO ENCONTRADO</label></td>
		<td><input type="button" value="Exportar ABA para TXT" class="btn btn-danger" style="padding: 6px 20px" onclick="location.href='download.php?aba=<?php echo trim($xlsx->sheetName($aba-1)) ?>'"></td>
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