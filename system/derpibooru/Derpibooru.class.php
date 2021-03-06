<?php

namespace Nolikein\Api;

use Nolikein\Api\components\ArgumentCleaner;
use Nolikein\Api\components\Media;

/**
 * Derpibooru Api
 *
 * @package  Api-php-Derpibooru
 * @author    <duboixys.gekiou@gmail.com>
 */

class Derpibooru
{
    
    # Main constants used in the Api
    const protocol = 'https';
    const domainName = 'derpibooru.org';
    const searchAction = 'search.json';
    const imagesAction = 'images.json';

    /** ---------------
     *  - Method list -
     *  ---------------
     *
     *      getLatestImages     : To get latest images
     *      getImageByTag       : To get images by tag
     *      getRandomImage      : To get one random image
     *      getRandomImageList  : To get many random images
     *
     */

    public function getLatestImages(int $userQuantity = 5, int $userPage = 1) : array
    {
        //  Description : Get $userQuantity latest images of Derpibooru. You can use $userPage to define the page index.
        //  ------------------------------------------------------------------------------------------------------------
        $cleaner = new ArgumentCleaner;
        $quantityArg = 'perpage='.$cleaner->cleanUserQuantity($userQuantity);
        $pageArg = 'page='.$cleaner->cleanUserPage($userPage);

        $completeUrl = self::protocol.'://'.self::domainName.'/'.self::imagesAction.'/?'.$quantityArg.'&'.$pageArg;
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);

        $mediaList = [];
        foreach ($responseDecoded['images'] as $search) {
            $media = new Media();
            $media->setUrl($search['image']);
            $media->setType(strpos($media->getUrl(), '.webm') ? 'movie' : 'image');
            $mediaList[] = $media;
        }
        return $mediaList;
    }

    public function getImageByTag(string $userTags = '*', int $userQuantity = 5, int $userPage = 1) : array
    {
        //  Description : Get $userQuantity images with $userTags tag for the $userPage page index.
        //  ---------------------------------------------------------------------------------------

        # To beginning, we clean all arguments send by the user
        $cleaner = new ArgumentCleaner;
        $tagArg = 'q='.$cleaner->cleanUserTags($userTags);
        $quantityArg = 'perpage='.$cleaner->cleanUserQuantity($userQuantity);
        $pageArg = 'page='.$cleaner->cleanUserPage($userPage);

        # after, we send a request to the website to get a json file with each time all data of an image
        $completeUrl = self::protocol.'://'.self::domainName.'/'.self::searchAction.'/?'.$tagArg.'&'.$quantityArg.'&'.$pageArg;
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);

        # Finaly, we go through the array to get all image link and we return the result
        $mediaList = [];
        foreach ($responseDecoded['search'] as $search) {
            $media = new Media();
            $media->setUrl($search['image']);
            $media->setType(strpos($media->getUrl(), '.webm') ? 'movie' : 'image');
            $mediaList[] = $media;
        }
        return $mediaList;
    }

    public function getRandomImage(string $specificTags = '*') : ?Media
    {
        //  Description : Get one random image.
        //  -----------------------------------

        $cleaner = new ArgumentCleaner;
        $tagArg = 'q='.$cleaner->cleanUserTags($specificTags);

        # the following lines will take one image id
        $completeUrl = self::protocol.'://'.self::domainName.'/'.self::searchAction.'/?'.$tagArg.'&random_image=y';
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);
        
        # There is one item?
        if (!isset($responseDecoded['id'])) {
            return null;
        }

        $mediaId = $responseDecoded['id'];


        # The following lines will send a request with an image id to get all data of an image
        $completeUrl = self::protocol.'://'.self::domainName.'/'.$mediaId.'.json/?'.$tagArg;
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);

        # Finaly, we return the direct link of an image
        $media = new Media();
        $media->setUrl($responseDecoded['image']);
        $media->setType(strpos($media->getUrl(), '.webm') ? 'movie' : 'image');

        return $media;
    }

    public function getRandomImageList(string $specificTags = '*', int $userQuantity = 5) : ?array
    {
        //  Description : Get many random images. Tag possible
        //  --------------------------------------------------

        # We do a little clean
        $cleaner = new ArgumentCleaner;
        $userQuantity = $cleaner->cleanUserQuantity($userQuantity);

        # As a reminder, a generator to browse consumes less than an empty array
        function getRandomImage(Derpibooru $obj, int $nbImages, string $specificTags)
        {
            for ($i=0; $i<$nbImages; $i++) {
                yield $obj->getRandomImage($specificTags);
            }
        }
        
        # Here, we get a list of media and test if the media is empty
        $mediaList = [];
        $randomList = getRandomImage($this, $userQuantity, $specificTags);
        foreach ($randomList as $randomMedia) {
            if (empty($randomMedia)) {
                return null;
            }
            $mediaList[] = $randomMedia;
        }
        return $mediaList;
    }
}
