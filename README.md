# comparar-diretorios-linux

Este projeto visa automatizar o processo de verificação de arquivos duplicados em um cenário onde duas pastas sincronizadas perderam a sincronia.
O cliente possui uma pastas `/caminhoA` com arquivos que podem já existir em `/caminhoB`, ao fim da execução deste projeto teremos os resultados possíveis:

### Arquivo existe em A mas não existe em B

O arquivo será movido de `/caminhoA` para `/caminhoB` a partir de um script bash `mover.sh` que será criado.

### O mesmo arquivo existe em A e B

O arquivo será apagado do `/caminhoA`.

### Existem arquivos com o mesmo nome mas são diferentes

Não será tratado, esses casos devem ser tratados manualmente.

## Pré-Requisitos

Este script foi projetado para ser executado em Linux, no cenário em questão foi utilizado o CentOS Linux 8. Parte do código está em PHP portanto é necessário possuir o PHP-CLI instalado, no CentOS8 o mesmo pode ser instalado através do comando a seguir:

```bash
sudo dnf -y install php-cli
```

## Preparação do ambiente

Primeiramente foram excluídos as pastas vazias, os arquivos de tamanho 0 bytes e os arquivos DS_store do mac e Thumbs.db do windows pois esses arquivos não são relevantes no caso em questão. Essas remoções foram realizadas executando os comandos a seguir:

```bash
cd /caminhoA
find . -name '.DS_Store' -type f -delete
find . -name '._.DS_Store' -type f -delete
find . -name 'Thumbs.db' -type f -delete
find . -name '._*' -type f -delete
find . -name '~*' -type f -delete
find ./ -type f -empty -delete
find ./ -type d -empty -delete
```

Após a remoção dos arquivos que não são relevantes e das pastas vazias, foi gerada uma lista de todos os arquivos do `/caminhoA` e seus respectivos hashes MD5.

```bash
find ./ -type f -exec md5sum {} + | sort -k 2 > listagem_md5.txt
```

## Execução

Após a preparação do ambiente, agora dispomos de uma lista contendo MD5 e o caminho dos arquivos de `/caminhoA` agora vamos executar o script `compare.php` a fim de gerar os arquivos que serão usados para automatização da tarefa.

```bash
php compare.php
```

Após a execução do script `compare.php` resultarão dois arquivos gerados pelo script:

### mover.sh

Este arquivo possui as linhas de comando necessárias para mover os arquivos ausentes que existem em `/caminhoA` mas não existem em `/caminhoB`. Analise o conteúdo do arquivo para certificar que está tudo correto antes de executar.

```bash
chmod +x mover.sh
./mover.sh
```

### apagar.sh

Este arquivo possui as linhas de comando necessárias para apagar os arquivos existentes em `/caminhoA` e que estão duplicados em `/caminhoB`. Considerando arquivo duplicado através da comparação do hash md5 do arquivo. A execução segue o mesmo formato do anterior.

```bash
chmod +x apagar.sh
./apagar.sh
```