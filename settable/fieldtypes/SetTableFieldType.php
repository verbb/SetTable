<?php
namespace Craft;

class SetTableFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Set Table');
    }

    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    public function getSettingsHtml()
    {
        $columns = $this->getSettings()->columns;
        $tableData = $this->getSettings()->tableData;

        if (!$columns)
        {
            $columns = array('col1' => array('heading' => '', 'handle' => '', 'type' => 'singleline'));

            // Update the actual settings model for getInputHtml()
            $this->getSettings()->columns = $columns;
        }

        if ($tableData === null)
        {
            $tableData = array('row1' => array());
        }

        $columnSettings = array(
            'heading' => array(
                'heading' => Craft::t('Column Heading'),
                'type' => 'singleline',
                'autopopulate' => 'handle'
            ),
            'handle' => array(
                'heading' => Craft::t('Handle'),
                'class' => 'code',
                'type' => 'singleline'
            ),
            'width' => array(
                'heading' => Craft::t('Width'),
                'class' => 'code',
                'type' => 'singleline',
                'width' => 50
            ),
            'type' => array(
                'heading' => Craft::t('Type'),
                'class' => 'thin',
                'type' => 'select',
                'options' => array(
                    'label' => Craft::t('Label'),
                    'singleline' => Craft::t('Single-line Text'),
                    'multiline' => Craft::t('Multi-line text'),
                    'number' => Craft::t('Number'),
                    'checkbox' => Craft::t('Checkbox'),
                )
            ),
        );

        craft()->templates->includeCssResource('settable/css/settable.css');

        craft()->templates->includeJsResource('js/TableFieldSettings.js');
        craft()->templates->includeJs('new Craft.TableFieldSettings(' .
            '"'.craft()->templates->namespaceInputName('columns').'", ' .
            '"'.craft()->templates->namespaceInputName('tableData').'", ' .
            JsonHelper::encode($columns).', ' .
            JsonHelper::encode($tableData).', ' .
            JsonHelper::encode($columnSettings) .
        ');');

        $columnsField = craft()->templates->render('settable/settings', array(
            'label'        => Craft::t('Table Columns'),
            'instructions' => Craft::t('Define the columns your table should have.'),
            'id'           => 'columns',
            'name'         => 'columns',
            'cols'         => $columnSettings,
            'rows'         => $columns,
            'addRowLabel'  => Craft::t('Add a column'),
            'initJs'       => false
        ));

        $tableDataField = craft()->templates->render('settable/settings', array(
            'label'        => Craft::t('Table Values'),
            'instructions' => Craft::t('Define the set values for the field.'),
            'id'           => 'tableData',
            'name'         => 'tableData',
            'cols'         => $columns,
            'rows'         => $tableData,
            'initJs'       => false
        ));

        return $columnsField.$tableDataField;
    }

    public function getInputHtml($name, $value)
    {
        $input = '<input type="hidden" name="'.$name.'" value="">';

        $tableHtml = $this->_getInputHtml($name, $value, false);

        if ($tableHtml) {
            $input .= $tableHtml;
        }

        return $input;
    }

    public function prepValueFromPost($value)
    {
        return $value;
    }

    public function prepValue($value)
    {
        if (is_array($value) && ($columns = $this->getSettings()->columns)) {
            // Make the values accessible from both the col IDs and the handles
            foreach ($value as &$row) {
                foreach ($columns as $colId => $col) {
                    if ($col['handle']) {
                        $row[$col['handle']] = (isset($row[$colId]) ? $row[$colId] : null);
                    }
                }
            }

            return $value;
        }
    }

    protected function defineSettings()
    {
        return array(
            'columns' => AttributeType::Mixed,
            'tableData' => AttributeType::Mixed,
        );
    }

    public function prepSettings($settings)
    {
        if (!isset($settings['tableData'])) {
            $settings['tableData'] = array();
        }

        return $settings;
    }

    private function _getInputHtml($name, $value, $static)
    {
        $columns = $this->getSettings()->columns;
        $tableData = $this->getSettings()->tableData;

        if ($columns) {
            // Translate the column headings
            foreach ($columns as &$column) {
                if (!empty($column['heading'])) {
                    $column['heading'] = Craft::t($column['heading']);
                }
            }

            // Minor fix for Backwards-compatibility - migrate old data into new key
            if ($value && is_array($value)) {
                foreach ($value as $key => $val) {
                    if (is_numeric($key)) {
                        $value['row'.($key+1)] = $val;
                        unset($value[$key]);
                    }
                }
            }

            if (!$value) {
                if (is_array($tableData)) {
                    $value = $tableData;
                }
            } else {
                // Merge the saved existing values and any new rows
                foreach ($tableData as $key => $val) {
                    if (!isset($value[$key])) {
                        $value[$key] = $val;
                    }
                }
            }

            $id = craft()->templates->formatInputId($name);

            return craft()->templates->render('settable/field', array(
                'id'     => $id,
                'name'   => $name,
                'cols'   => $columns,
                'rows'   => $value,
            ));
        }
    }
}
