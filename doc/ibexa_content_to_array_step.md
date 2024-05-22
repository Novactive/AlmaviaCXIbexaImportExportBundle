# Ibexa content to array step

This step transform a content into an associative array based on a map.

The map represent the generated associative array. Each entry value represente the property to extract from the content.

To do this extraction we use a `AlmaviaCX\Bundle\IbexaImportExport\Ibexa\ValueAccessor` created by the `AlmaviaCX\Bundle\IbexaImportExport\Ibexa\ValueAccessorBuilder` service.

This accessor define the properties available for extraction. 

## Field value extraction

A mecanic is implemented to use a different extractor based on the field type.

This allow to provide different format of a field value. As an exemple for a richtext it's possible to extract the xml or html version.

A field value extractor is a service implementing `AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field\ValueTransformer\ContentFieldValueTransformerInterface` with a tag `{ name: 'importexport.content.field.value.transformer', type: '<field type identifier>' }`
