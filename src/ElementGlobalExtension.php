<?php
namespace Arillo\ElementsGlobal;

use SilverStripe\ORM\DataExtension;

class ElementGlobalExtension extends DataExtension
{
    private static $db = [
        'Global' => 'Boolean',
    ];
}
