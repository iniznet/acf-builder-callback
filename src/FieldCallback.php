<?php

namespace iniznet\AcfBuilderCallback;

class FieldCallback
{

	/**
	 * @param string $name Field Name
	 * @param callable $callback
	 */
	public static function defaultValueCallback($name, $callback)
	{
		if (!is_callable($callback)) {
			return;
		}

		add_filter('acf/load_value/name=' . $name, function($value, $postId, $field) use ($callback) {
            if ($value) {
                return $value;
            }

            return $callback($value, $postId, $field);
        }, 10, 4);
	}

	/**
	 * @param string $name Field Name
	 * @param callable $callback
	 */
	public static function sanitizationCallback($name, $callback)
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
	public static function escapeCallback($name, $callback)
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
	public static function choicesCallback($name, $callback)
	{
		if (!is_callable($callback)) {
			return;
		}

		/**
		 * @param array $field Field configuration
		 */
		add_filter('acf/prepare_field/name=' . $name, function ($field) use ($callback) {
			$choices = $callback($field);

			if (!is_array($choices)) {
				return $field;
			}

			$field['choices'] = array_replace($field['choices'], $choices);

			return $field;
		});
	}

	public static function run()
	{
		/**
		 * @param array $field Field configuration
		 */
		add_filter('acf/load_field', function ($field) {
			self::defaultValueCallback($field['name'], $field['default_value_cb'] ?? false);
			self::sanitizationCallback($field['name'], $field['sanitization_cb'] ?? false);
			self::escapeCallback($field['name'], $field['escape_cb'] ?? false);
			self::choicesCallback($field['name'], $field['choices_cb'] ?? false);

			return $field;
		});
	}
}
