<?php

namespace Arillo\ElementsGlobal;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\HiddenField;

class ElementsGlobalModelAdminExtension extends Extension
{
    public function updateEditForm(&$form) 
    {
        $form->Fields()->removeByName('PageID');
		$form->Fields()->push(HiddenField::create('Global', 'Global', 1));
    }
}