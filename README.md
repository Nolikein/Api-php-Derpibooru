# Api-php-Derpibooru [DEPRECIED]

Look at [this repository](https://github.com/Nolikein/Api-Derpibooru-Facade)

## How to install ?

You need to include the autoloader and the namespace to call the class :

    use \Api\Derpibooru;
    require __DIR__.'/system/autoloader.php';

You need php with the version 7.0 <-> 7.3.11

## How to use ?

Create an Derpibooru objet and use it methods :
    $Dapi = new Derpibooru();
    $images = $Dapi->getRandomImageList(9, 'Fluttershy');

There is actually 4 methods :
+ getLatestImages       : To get latest images
+ getImageByTag         : To get images by tag
+ getRandomImage        : To get one random image
+ getRandomImageList    : To get many random images

Example :

    $tag = 'Fluttershy';
    $quantity = 9;
    $nPage = 1;

    $images = $Dapi->getLatestImages( $quantity, $nPage );
    $images = $Dapi->getImageByTag( $tag, $quantity, $nPage );
    $images = $Dapi->getRandomImage( $tag );
    $images = $Dapi->getRandomImageList( $tag, $quantity );

## I want to show a list of media

    if (is_array($mediaList)) {
        foreach ($mediaList as $media) {
            if($media->getType() == 'image')
            {
                ?>
                <a href="<?= $media->getUrl() ?>"><img src="<?= $media->getUrl() ?>" alt="Loading..."></a>
                <?php
            }
            else
            {
                ?>
                <a href="<?= $media->getUrl() ?>"><img src="/system/derpibooru/assets/movie-icon.png" alt="Loading..."></a>
                <?php
            }
        }
    } else {
        echo '<a href="'.$mediaList->getUrl().'"><img src="'.$mediaList->getUrl().'" alt="Loading..."></a>';
    }

*I know, this is a bad code but I think that it's faster to read.*

## Upgrades in the future ?
If you have any recommandation about this api, don't hesitate and go to talking with me !
For the upgrades... We can add helpers methods for when you would call a tag.
