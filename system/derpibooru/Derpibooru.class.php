<?php

namespace Nolikein\Api;

use Nolikein\Api\components\ArgumentCleaner;

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

        $imgList = [];
        foreach ($responseDecoded['images'] as $search) {
            $imgList[] = $search['image'];
        }
        return $imgList;
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
        $imgList = [];
        foreach ($responseDecoded['search'] as $search) {
            $imgList[] = $search['image'];
        }
        return $imgList;
    }

    public function getRandomImage(string $specificTags = '*') : string
    {
        //  Description : Get one random image.
        //  -----------------------------------

        $cleaner = new ArgumentCleaner;
        $tagArg = 'q='.$cleaner->cleanUserTags($specificTags);

        # the following lines will take one image id
        $completeUrl = self::protocol.'://'.self::domainName.'/'.self::searchAction.'/?'.$tagArg.'&random_image=y';
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);
        $imageId = $responseDecoded['id'];

        # The following lines will send a request with an image id to get all data of an image
        $completeUrl = self::protocol.'://'.self::domainName.'/'.$imageId.'.json/?'.$tagArg.'&'.$quantityArg.'&'.$pageArg;
        $siteResponse = file_get_contents($completeUrl);
        $responseDecoded = json_decode($siteResponse, 1);

        # Finaly, we return the direct link of an image
        return $responseDecoded['image'];
    }

    public function getRandomImageList(int $userQuantity = 5, string $specificTags = '*')
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
        
        # Here, we call a generator $userQuantity time and return all url images get with it
        $imagesUrl = [];
        $randomList = getRandomImage($this, $userQuantity, $specificTags);
        foreach ($randomList as $random_image) {
            $imagesUrl[] = $random_image;
        }
        return $imagesUrl;
    }
}
