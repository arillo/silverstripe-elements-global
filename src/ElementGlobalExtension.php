<?php
namespace Arillo\ElementsGlobal;

use SilverStripe\Core\Extension;

class ElementGlobalExtension extends Extension
{
    private static $db = [
        'Global' => 'Boolean',
    ];
}