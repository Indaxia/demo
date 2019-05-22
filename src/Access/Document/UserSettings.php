<?php

namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserSettingsInterface;

/**
 * @ODM\EmbeddedDocument
 */
class UserSettings implements UserSettingsInterface
{

}