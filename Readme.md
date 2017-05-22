# arillo\elements-global

Elements-global will allow you to define specific elements as being global. Global elements can be added to pages by creating a VirtualElement that will work as a link/placeholder to the global element.

The elements that should be available globally should implement the IElementsGlobal interface. When creating this elements via a ModelAdmin they will automatically get the flag Global set to true.
