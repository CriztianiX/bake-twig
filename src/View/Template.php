<?php

declare(strict_types=1);

namespace BakeTwig\View;

use Bake\View\BakeView;
use Bake\View\Helper\BakeHelper;
use Cake\Database\Schema\TableSchema;

class Template {
    private array $viewVars = [];

    private BakeHelper $Bake;

    /**
     * @param BakeView $view
     */
    public function __construct(private BakeView $view)
    {
        $this->Bake = new BakeHelper($this->view);

        $varNames = $view->getVars();

        foreach ($varNames as $varName) {
            $this->viewVars[$varName] = $view->get($varName);
        }
    }

    public function form(): string
    {
        /**
         * @var string $action
         * @var array $fields
         * @var array $keyFields
         * @var string $pluralHumanName
         * @var string $pluralVar
         * @var array $primaryKey
         * @var TableSchema $schema
         * @var string $singularVar
         * @var string $singularHumanName
         */
        extract($this->viewVars);

        $template = <<<TEMPLATE
        <div class="row">
            <aside class="column">
                <div class="side-nav">
                    <h4 class="heading">{{ __('Actions') }} ?></h4>
                    {{ASIDE_ADD}}
                    {{ helper_html_link(__('List $pluralHumanName'), {'action' : 'index'})|raw }}
                </div>
            </aside>
            <div class="column-responsive column-80">
                <div class="$pluralVar form content">
                    {{ helper_Form_create($singularVar) }}
                    <fieldset>
                        <legend>{{ __('$action $singularHumanName') }}</legend>
                        {{FORM_INPUTS}}
                    </fieldset>
                    {{ helper_Form_button(__('Submit')) }}
                    {{ helper_Form_end() }}
                </div>
             </div>
        </div>
        TEMPLATE;

        // Append 'add action' if necessary
        $appendAdd = function () use ($action, $pluralHumanName, $primaryKey, $singularVar) {
            if (strpos($action, 'add') === false) {
                return <<<TEMPLATE
                    {{
                        helper_Form_postLink(
                            __('Delete'),
                            {'action' : 'delete', 0 : $singularVar.$primaryKey[0]},
                            {'confirm' : __('Are you sure you want to delete # {0}?', $singularVar.$primaryKey[0])})|raw
                    }}
                TEMPLATE;
            }

            return '';
        };

        // Append form inputs
        $appendInputs = function() use ($fields, $keyFields, $primaryKey, $schema) {
            $inputs = [];

            foreach ($fields as $field) {
                if (in_array($field, $primaryKey)) {
                    continue;
                }

                if (isset($keyFields[$field])) {
                    $fieldData = $this->Bake->columnData($field, $schema);

                    if($fieldData['null']) {
                        $inputs[] = "{{ helper_Form_control('$field', {'options' : $keyFields[$field], 'empty' : true}) }}";
                    }else{
                        $inputs[] = "{{ helper_Form_control('$field', {'options' : $keyFields[$field]}) }}";
                    }
                }elseif(!in_array($field, ['created', 'modified', 'updated'])) {
                    $fieldData = $this->Bake->columnData($field, $schema);

                    if(in_array($fieldData['type'], ['date', 'datetime', 'time']) && $fieldData['null']) {
                        $inputs[] = "{{ helper_Form_control('$field', {'empty' : true}) }}";
                    }else{
                        $inputs[] = "{{ helper_Form_control('$field') }}";
                    }
                }
            }

            return implode("\n", $inputs);
        };

        $toReplace = ['{{ASIDE_ADD}}' => $appendAdd(), '{{FORM_INPUTS}}' => $appendInputs()];

        return str_replace(array_keys($toReplace), array_values($toReplace), $template);
    }
}
