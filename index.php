<?php

use Nolikein\Api\Derpibooru;

require __DIR__.'/system/autoloader.php';

    $Dapi = new Derpibooru();
    $tag = 'Fluttershy';
    $quantity = 9;
    $nPage = 1;

    $mediaList = $Dapi->getRandomImageList($tag, $quantity);
    
ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            body {
                background-color: LightCoral;
            }
            .container {
                max-width: 1000px;
                margin: auto;
                padding: 1em;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                background-color: indianred;
            }

            a {
                display: inline-block;
                width: 33%;
                height: 300px;
                
            }

            img {
                width: 100%;
                height: 300px;
                object-fit: scale-down;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php
            if (is_array($mediaList)) {
                foreach ($mediaList as $media) {
                    if ($media->getType() == 'image') {
                        ?>
                        <a href="<?= $media->getUrl() ?>"><img src="<?= $media->getUrl() ?>" alt="Not found"></a>
                        <?php
                    } else {
                        ?>
                        <a href="<?= $media->getUrl() ?>"><img src="/system/derpibooru/assets/movie-icon.png" alt="Not found"></a>
                        <?php
                    }
                }
            } else {
                echo '<a href="'.$mediaList->getUrl().'"><img src="'.$mediaList->getUrl().'" alt="Not found"></a>';
            }
            ?>
        </div>
    </body>
</html>
<?php $content = ob_get_clean();

echo $content;
