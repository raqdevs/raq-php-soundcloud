# raq-php-soundcloud

Biblioteca para extração de dados e stream de audio do [soundcloud.com](https://soundcloud.com/), extrai todas as informações disponiveis de uma faixa e gera uma url para a reprodução do audio

## Requisitos

- PHP 7.0+

## Atributos

- `$sc->html` = Dados da pagina obtida (é o html obtido da primeira requisição).
- `$sc->dom` = As informações do `$sc->html` porém em formato de DOM (arvore utilizando a classe DomDocument do PHP) iterativa como no JavaScript.
- `$sc->client_id` = Id único temporário para as requisições, cada usuário possui um:`client_id=AH1HOCnlxQKahx111T3wjQlke9ceE2s9`.
- `$sc->track_id` = Id único da música, cada usuário possui um: `1348675264`.
- `$sc->track_information` = Objeto contendo todas as informações da música.
- `$sc->track` = Objeto contendo as informações filtradas da música.
- `$sc->stream` = Link direto de stream da música `https://cf-media.sndcdn.com/Qz1NlEcNOJlf.128.mp3?Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiKjovL2NmL...`.


obs: cada atributo só pode ser obtido após o respectivo método ser chamado <br/>
ex: para obter `$sc->client_id` primeiro é necessário iniciar `$sc->clientId()`

## Utilização

```php
require "src/RaqSoundcloud.php";

$url = "https://soundcloud.com/chillplanetmusic/invisible-annick-bestia-stay-with-me-2";

$sc = new RaqSoundcloud;
$sc->request($url);
$sc->trackId();
$sc->clientId();
$sc->trackInformation();
$sc->getStream();

var_dump($sc);
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
