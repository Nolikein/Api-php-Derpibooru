<?php

namespace Nolikein\Api\components;

class Media
{
    private $url;
    private $type;

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }
}
