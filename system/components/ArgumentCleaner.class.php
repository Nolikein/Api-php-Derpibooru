<?php

namespace Nolikein\Api\components;

class ArgumentCleaner
{
    public function cleanUserTags(string $userTags) : string
    {
        # Basic clean operations for secure the tag to use
        $userTags = trim($userTags);
        if (empty($userTags)) {
            return '*';
        }

        $userTags = htmlspecialchars($userTags);

        # Form to url conversion
        $userTags = urlencode($userTags);

        return $userTags;
    }

    public function cleanUserQuantity(int $userQuantity) : int
    {
        if ($userQuantity < 1) {
            $userQuantity = 1;
        }
        return $userQuantity;
    }

    public function cleanUserPage(int $userPage)
    {
        return $this->cleanUserQuantity($userPage);
    }
}
