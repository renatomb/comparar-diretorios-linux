<?php

$movedor = fopen('mover.sh', 'w') or die('Impossivel abrir arquivos de exportacao');
$apagar = fopen('apagar.sh', 'w') or die('Impossivel abrir arquivos de exportacao');
$handle = fopen("listagem_md5.txt", "r");
if ($handle) {
   $origem='/caminhoA/';
   $destino='/caminhoB/';
   while (($line = fgets($handle)) !== false) {
    	$line=trim($line);
      $md5=substr($line,0,32);
      $caminho=substr($line,36,strlen($line)-36);
      if (file_exists("$destino$caminho")) {
         echo ".";
         if (md5_file("$destino$caminho") == $md5) {
            fwrite($apagar,'rm -f "' . "$origem$caminho" . '"' . "\n");
            echo "I";
         }
         else {
            echo "X";
         }
      }
      else {
         $diretorio=dirname("$destino$caminho");
         fwrite($movedor,"mkdir -p \"$diretorio\"\n");
         fwrite($movedor,"mv \"$origem$caminho\" \"$destino$caminho\"\n");
      }
    }
    fclose($handle);
}
fclose($movedor);
fclose($apagar);
?>