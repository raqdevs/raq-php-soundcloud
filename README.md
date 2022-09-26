# raq-php-soundcloud

Biblioteca para extração de dados e stream de audio do [soundcloud.com](https://soundcloud.com/), extrai todas as informações disponiveis de uma faixa e gera uma url para a reprodução do audio

## Requisitos

- PHP 7.0+

## Métodos
- `$sc->request($url)` = Requisição inicial onde busca os dados da pagina solicitada (passada pelo parâmetro `$url`). ex de url: `https://soundcloud.com/chillplanetmusic/invisible-annick-bestia-stay-with-me-2`. Alimenta dois atributos importantes `html` e `dom` para realizar a mineração necessária posteriormente. 
- `$sc->getTrackId()` = Busca o ID da música que será necessário para fazer a requisição de informações da faixa.
- `$sc->getClientId()` = Busca o ID do usuário (mesmo sendo anônimo ou seja não é preciso ter conta no site). Em todas as requisições é necessário passar como parâmetro o ID do usuário. Caso não seja passado nenhuma url no método `request($url)` será buscado através da url base `soundcloud.com` para obter o `client_id`.
- `$sc->getTrackInformation()` = Busca as informações da faixa obtidas com `getTrackId()`.
- `$sc->getStream()` = Busca o link direto da faixa e guarda no atributo `$sc->stream`.
- `$sc->search()` = Busca uma faixa através do titulo.
- `$sc->getStreamProgressive()` = Obtem o link direto da faixa buscada pelo método `search()`

## Atributos
obs: cada atributo só pode ser obtido após o respectivo método ser chamado <br/>
ex: para obter `$sc->client_id` primeiro é necessário iniciar `$sc->getClientId()`

- `$sc->html` = Dados da pagina obtida (é o html obtido da primeira requisição).
- `$sc->dom` = As informações do `$sc->html` porém em formato de DOM (arvore utilizando a classe DomDocument do PHP) iterativa como no JavaScript.
- `$sc->client_id` = ID único temporário para as requisições, cada usuário possui um: `AH1HOCnlxQKahx111T3wjQlke9ceE2s9`.
- `$sc->track_id` = ID único da música, cada música possui um: `1348675264`.
- `$sc->track_information` = Objeto contendo todas as informações da música.
- `$sc->track` = Objeto contendo as informações filtradas da música.
- `$sc->stream` = Link direto de stream da música `https://cf-media.sndcdn.com/Qz1NlEcNOJlf.128.mp3?Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiKjovL2NmL...`.
- `$sc->search_result` = Resultado da busca utilizando o método `search()`.

## Utilização

### search.php
O arquivo `search.php` resume bem todo o projeto, pois é onde existe a busca e o streaming, podendo ter a opção de download das faixas.

### Busca simples utilizando o método search()

example_search.php
```php
<?php  

require "src/RaqSoundcloud.php";

$sc = new RaqSoundcloud;
$sc->getClientId();
$sc->search("martin garrix");

var_dump($sc->search_result);

?>
```
Existem dois formatos de streaming utilizado pelo [soundcloud.com](https://soundcloud.com/) o `hls` que utiliza m3u8 e o formato `progressive`, trabalharemos por enquanto com o formato `progressive`.
<br/><br/>
Um dos atributos obtivos dentro da busca é o `progressive` uma url que aponta para o link temporário de streaming, porém é preciso complementar com o ID do cliente para obter sucesso na requisição.

example_stream_progressive.php
```php
<?php  

require "src/RaqSoundcloud.php";

$progressive = 'https://api-v2.soundcloud.com/media/soundcloud:tracks:275869874/0412c3a6-e6a7-4399-b9bd-3a77f2c6be15/stream/progressive';

$sc = new RaqSoundcloud;
$sc->getClientId();
$sc->getStreamProgressive($progressive);

echo $sc->stream;

?>
```
O resultado é algo semelhante a isso: `https://cf-media.sndcdn.com/Qz1NlEcNOJlf.128.mp3?Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiKjovL2NmL....`

### Buscando através de url da faixa
example.php
```php
<?php

require "src/RaqSoundcloud.php";

$url = "https://soundcloud.com/chillplanetmusic/invisible-annick-bestia-stay-with-me-2";

$sc = new RaqSoundcloud;
$sc->request($url);
$sc->trackId();
$sc->clientId();
$sc->trackInformation();
$sc->getStream();

var_dump($sc);

?>
```

Logo abaixo para a vizualização em html

```html
<img src="<?= $sc->track->artwork_url_hd ?>">
<h2><?= $sc->track->title ?></h2>
<audio controls src="<?= $sc->stream_url ?>"></audio>
<hr>
<?= $sc->stream_url ?>
```
## Colaboradores
![https://avatars.githubusercontent.com/u/39655124?s=40&v=4](https://avatars.githubusercontent.com/u/39655124?s=40&v=4)
![https://avatars.githubusercontent.com/u/36286266?s=40&v=4](https://avatars.githubusercontent.com/u/36286266?s=40&v=4)
