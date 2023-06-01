<?php require './functions.php';
$error = ""; ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta property="og:author" content="White-Blue1">
    <meta property="og:title" content="Downloader de vídeos do YouTube">
    <meta name="description" content="Um simples downloader de vídeos e músicas do YouTube feito por mim(White Blue) apenas por diversão.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PHP YouTube downloader</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" integrity="sha512-t4GWSVZO1eC8BM339Xd7Uphw5s17a86tIZIj8qRxhnKub6WoyhnrxeCIMeAqBPgdZGlCcG2PrZjMc+Wr78+5Xg==" crossorigin="anonymous" referrerpolicy="no-referrer" />    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha512-VK2zcvntEufaimc+efOYi622VN5ZacdnufnmX7zIhCPmjhKnOi9ZDMtg1/ug5l183f19gG1/cBstPO4D8N/Img==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body class="body">
    <div class="root">
        <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top text-center">
  
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="sr-only">Alternar navegação</span>
        <i class="fa fa-bars"></i>
    </button>

	                    

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="./index.php">Início <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://github.com/White-Blue1/yt-downloader" target="_blank">Visite meu github</a>
      </li>
    </ul>
  </div>
</nav>
        </header>
    <div class="container align-item-center px-5 py-5 def-p">
        <form method="post" action="" class="form">
            <div class="row">
                <div class="col-lg-12">
                    <h7 class="text-align">Baixar</h7>
                </div>
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="video_link"
                            placeholder="Link do YouTube"
                            <?php if (isset($_POST['video_link'])) echo "value='" . $_POST['video_link'] . "'"; ?>>
                        <span class="input-group-btn">
                            
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" id="submit" class="btn btn-outline-primary my-5">Converter</button>

        </form>
</div>

        <?php if ($error) : ?>
        <div style="color:red;font-weight: bold;text-align: center"><?php print $error ?></div>
        <?php endif; ?>

        <?php if (isset($_POST['submit'])) : ?>


        <?php
            $video_link = $_POST['video_link'];
            // parse_str(parse_url($video_link, PHP_URL_QUERY), $parse_url);
            $video_id =  getYoutubeId($video_link);
            $video = json_decode(getVideoInfo($video_id));
            $formats = $video->streamingData->formats;
            $adaptiveFormats = $video->streamingData->adaptiveFormats;
            $thumbnails = $video->videoDetails->thumbnail->thumbnails;
            $title = $video->videoDetails->title;
            $short_description = $video->videoDetails->shortDescription;
            $thumbnail = end($thumbnails)->url;
            ?>


        <div class="row formSmall">
            <div class="col-lg-3">
                <img src="<?php echo $thumbnail; ?>" style="max-width:100%">
            </div>
            <div class="col-lg-9">
                <h2><?php echo $title; ?> </h2>
                <p><?php echo str_split($short_description, 100)[0]; ?></p>
            </div>
        </div>

        <?php if (!empty($formats)) : ?>
        <?php if (@$formats[0]->url == "") : ?>
        <div class="card formSmall">
            <div class="card-header">
                <strong>URL inválida!</strong>
                <small><?php
                                    $signature = "https://example.com?" . $formats[0]->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'] . "&sig=" . $parse_signature['s'];
                                    ?>
                </small>
            </div>
        </div>
        <?php
                    die();
                endif;
                ?>

        <div class="card formSmall">
            <div class="card-header">
                <strong>Com vídeo e som</strong>
            </div>

            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>URL</td>
                        <td>Tipo</td>
                        <td>Qualidade</td>
                        <td>Baixar</td>
                    </tr>
                    <?php foreach ($formats as $format) : ?>
                    <?php

                                if (@$format->url == "") {
                                    $signature = "https://example.com?" . $format->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'] . "&sig=" . $parse_signature['s'];
                                    //var_dump($parse_signature);
                                } else {
                                    $url = $format->url;
                                }




                                ?>
                    <tr>
                        <td><a href="<?php echo $url; ?>">Test</a></td>
                        <td>
                            <?php if ($format->mimeType) echo explode(";", explode("/", $format->mimeType)[1])[0];
                                        else echo "Unknown"; ?>
                        </td>
                        <td>
                            <?php if ($format->qualityLabel) echo $format->qualityLabel;
                                        else echo "Unknown"; ?>
                        </td>
                        <td>
                            <a
                                href="downloader.php?link=<?php echo urlencode($url) ?>&title=<?php echo urlencode($title) ?>&type=<?php if ($format->mimeType) echo explode(";", explode("/", $format->mimeType)[1])[0];
                                                                                                                                                else echo "mp4"; ?>">
                                Baixar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>


        <div class="card formSmall">
            <div class="card-header">
                <strong>Apenas vídeos com qualidade de vídeo suportada ou áudio com qualidade de áudio suportada</strong>
            </div>
            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>Tipo</td>
                        <td>Qualidade</td>
                        <td>Baixar</td>
                    </tr>
                    <?php foreach ($adaptiveFormats as $video) : ?>
                    <?php
                                try {
                                    $url = $video->url;
                                } catch (Exception $e) {
                                    $signature = $video->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'];
                                }

                                ?>
                    <tr>
                        <td><?php if (@$video->mimeType) echo explode(";", explode("/", $video->mimeType)[1])[0];
                                        else echo "Unknown"; ?></td>
                        <td><?php if (@$video->qualityLabel) echo $video->qualityLabel;
                                        else echo "Unknown"; ?></td>
                        <td><a
                                href="downloader.php?link=<?php print urlencode($url) ?>&title=<?php print urlencode($title) ?>&type=<?php if ($video->mimeType) echo explode(";", explode("/", $video->mimeType)[1])[0];
                                                                                                                                                else echo "mp4"; ?>">Download</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>


        <?php endif; ?>

    </div>

    <footer class="d-flex flex-wrap justify-content-between align-items-center my-4 mp-0 footer-bottom">
    <div class="col-md-4 d-flex align-items-center">
      <span class="mb-3 mb-md-0 text-body-secondary">PHP YouTube Downloader</span>
    </div>

    <ul class="list-unstyled justify-content-end d-flex">
    <li class="ms-3"><a class="text-center text-decoration-none text-dark" href="https://github.com/White-Blue1" target="_blank"><i title="Visite meu Github" class="fab fa-github icon-size"></i></a></li>

    </ul>
  </footer>
</body>

</html>
