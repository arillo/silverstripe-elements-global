# SilverStripe Elements global

[![Latest Stable Version](https://poser.pugx.org/arillo/silverstripe-elements-global/v/stable?format=flat)](https://packagist.org/packages/arillo/silverstripe-elements-global)
[![Total Downloads](https://poser.pugx.org/arillo/silverstripe-elements-global/downloads?format=flat)](https://packagist.org/packages/arillo/silverstripe-elements-global)

Elements-global will allow you to define specific elements as being global. Global elements can be added to pages by creating a VirtualElement that will work as a link/placeholder to the global element.

The elements that should be available globaly should implement the IElementsGlobal interface. When creating this elements via a ModelAdmin they will automatically get the flag Global set to true.

__You need to specify at least one Element that implements the IElementsGlobal interface.__

```php
<?php
class HeroElement extends ElementBase implements IElementsGlobal
{
...
}
```

You can restrict the allowed elements to be referenced by a virtual element on a per pagetype basis on your config.yml via the virtual_elements attribute.

```yml
HomePage:
  element_relations:
    Elements:
      - HeroElement
      - DownloadElement
      - TeaserElement
      - VirtualElement
    Sidebar:
      - HeroElement
      - VirtualElement
  virtual_elements:
    Elements:
      - HeroElement
```
