# Api-Derpibooru

## How to install ?

You need to include the class and its namespace :

    use Nolikein\Api\Derpibooru;
    require __DIR__.'/system/components/ArgumentCleaner.class.php';
    require __DIR__.'/system/Derpibooru.class.php';

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
    $images = $Dapi->getRandomImageList( $tag, $quantity, $nPage );
    $images = $Dapi->getRandomImage( $tag );
    $images = $Dapi->getRandomImageList( $quantity, $tag );

## I want to show an image

    if(is_array($images))
        foreach($images as $image) {
            ?>
                <img src="<?= $image ?>" alt="Not found">
            <?php
        }
    else
        echo '<img src="'.$images.'" alt="Not found">';
    ?>

*I know, this is a bad code but I think that it's faster to read.*

## Upgrades in the future ?
If you have any recommandation about this api, don't hesitate and go to talking with me !
For the upgrades... We can add helpers methods for when you would call a tag.