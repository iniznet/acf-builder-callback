<?php

namespace iniznet\AcfBuilderCallback;

use iniznet\AcfBuilderCallback\FieldBuilder;
use StoutLogic\AcfBuilder\FieldsBuilder as FieldsBuilderBase;

class FieldsBuilder extends FieldsBuilderBase
{
	/**
     * @param string $name Field Group name
     * @param array $groupConfig Field Group configuration
     */
	public function __construct($name, array $groupConfig = [])
	{
		parent::__construct($name, $groupConfig);
	}

	/**
     * Add a field of a specific type
     * @param string $name
     * @param string $type
     * @param array $args field configuration
     * @throws FieldNameCollisionException if name already exists.
     * @return FieldBuilder
     */
    public function addField($name, $type, array $args = [])
    {
        return $this->initializeField(new FieldBuilder($name, $type, $args));
    }
}
