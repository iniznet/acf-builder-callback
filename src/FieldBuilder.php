<?php

namespace iniznet\AcfBuilderCallback;

use StoutLogic\AcfBuilder\FieldBuilder as FieldBuilderBase;

/**
 * Builds configurations for an ACF Field
 * @method FieldBuilder addField(string $name, string $type, array $args = [])
 * @method FieldBuilder addFields(FieldsBuilder|array $fields)
 * @method FieldBuilder addChoiceField(string $name, string $type, array $args = [])
 * @method FieldBuilder addText(string $name, array $args = [])
 * @method FieldBuilder addTextarea(string $name, array $args = [])
 * @method FieldBuilder addNumber(string $name, array $args = [])
 * @method FieldBuilder addEmail(string $name, array $args = [])
 * @method FieldBuilder addUrl(string $name, array $args = [])
 * @method FieldBuilder addPassword(string $name, array $args = [])
 * @method FieldBuilder addWysiwyg(string $name, array $args = [])
 * @method FieldBuilder addOembed(string $name, array $args = [])
 * @method FieldBuilder addImage(string $name, array $args = [])
 * @method FieldBuilder addFile(string $name, array $args = [])
 * @method FieldBuilder addGallery(string $name, array $args = [])
 * @method FieldBuilder addTrueFalse(string $name, array $args = [])
 * @method FieldBuilder addSelect(string $name, array $args = [])
 * @method FieldBuilder addRadio(string $name, array $args = [])
 * @method FieldBuilder addCheckbox(string $name, array $args = [])
 * @method FieldBuilder addPostObject(string $name, array $args = [])
 * @method FieldBuilder addPageLink(string $name, array $args = [])
 * @method FieldBuilder addTaxonomy(string $name, array $args = [])
 * @method FieldBuilder addUser(string $name, array $args = [])
 * @method FieldBuilder addDatePicker(string $name, array $args = [])
 * @method FieldBuilder addTimePicker(string $name, array $args = [])
 * @method FieldBuilder addDateTimePicker(string $name, array $args = [])
 * @method FieldBuilder addColorPicker(string $name, array $args = [])
 * @method FieldBuilder addGoogleMap(string $name, array $args = [])
 * @method FieldBuilder addLink(string $name, array $args = [])
 * @method FieldBuilder addTab(string $label, array $args = [])
 * @method FieldBuilder addRange(string $name, array $args = [])
 * @method FieldBuilder addMessage(string $label, string $message, array $args = [])
 * @method FieldBuilder addRelationship(string $name, array $args = [])
 * @method GroupBuilder addGroup(string $name, array $args = [])
 * @method GroupBuilder endGroup()
 * @method RepeaterBuilder addRepeater(string $name, array $args = [])
 * @method RepeaterBuilder endRepeater()
 * @method FlexibleContentBuilder addFlexibleContent(string $name, array $args = [])
 * @method FieldsBuilder addLayout(string|FieldsBuilder $layout, array $args = [])
 * @method LocationBuilder setLocation(string $param, string $operator, string $value)
 */
class FieldBuilder extends FieldBuilderBase
{
	/**
     * @param string $name Field Name, conventionally 'snake_case'.
     * @param string $type Field Type.
     * @param array $config Additional Field Configuration.
     */
    public function __construct($name, $type, $config = [])
    {
        parent::__construct($name, $type, $config);

		$this->doCallback();
    }

	public function doCallback()
	{
		$config = $this->getConfig();

		$this->sanitizationCallback($config['name'], $config['sanitization_cb'] ?? false);
		$this->escapeCallback($config['name'], $config['escape_cb'] ?? false);
		$this->choiceCallback($config['name'], $config['choices_cb'] ?? false);
	}

	/**
	 * @param string $name Field Name
	 * @param callable $callback
	 */
	public function sanitizationCallback($name, $callback)
	{
		if (!is_callable($callback)) {
			return;
		}

		add_filter('acf/update_value/name=' . $name, $callback, 10, 4);
	}

	/**
	 * @param string $name Field Name
	 * @param callable $callback
	 */
	public function escapeCallback($name, $callback)
	{
		if (!is_callable($callback)) {
			return;
		}

		add_filter('acf/load_value/name=' . $name, $callback, 10, 3);
	}

	/**
	 * @param string $name Field Name
	 * @param callable $callback
	 */
	public function choiceCallback($name, $callback)
	{
		if (!is_callable($callback)) {
			return;
		}

		add_filter('acf/load_field/name=' . $name, function($field) use ($callback) {
			$choices = $callback();

			if (!is_array($choices)) {
				return $field;
			}

			$field['choices'] = array_merge($field['choices'], $choices);

			return $field;
		}, 10);
	}
}
