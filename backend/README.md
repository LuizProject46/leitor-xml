# Backend leitor XML

#

Para executar , basta rodar os comandos :

> docker build -f ./Dockerfile -t openswoole-php .  
>  docker run --rm -p 9501:9501 -v $(pwd):/app -w /app openswoole-php server.php -t