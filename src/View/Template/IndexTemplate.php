<?php

declare(strict_types=1);

namespace BakeTwig\View\Template;

use BakeTwig\View\Template;
use Cake\Database\Schema\TableSchema;
use Cake\ORM\Table;
use Gajus\Dindent\Indenter;

class IndexTemplate extends Template
{
    public function getTemplate(): string
    {
        /**
         * @var array $associations
         * @var string $action
         * @var array $fields
         * @var array $indexColumns
         * @var array $keyFields
         * @var Table $modelObject
         * @var string $pluralHumanName
         * @var string $pluralVar
         * @var array $primaryKey
         * @var TableSchema $schema
         * @var string $singularVar
         * @var string $singularHumanName
         */
        extract($this->viewVars);

        $fields = $this->Bake->filterFields($fields, $schema, $modelObject, $indexColumns, ['binary', 'text']);

        /**
         * @return string
         */
        $tableHeaders = function() use ($fields): string {
            $tableHeaders = array_map(fn($field) => "<th>{{ helper_Paginator_sort('$field') }}</th>", $fields);
            return implode("\n", $tableHeaders);
        };

        /**
         * @return string
         */
        $tableRows = function() use (
            $associations, $fields, $primaryKey, $schema, $singularVar): string {

            $rows = [];

            foreach ($fields as $key => $field) {
                $isKey = false;

                if (isset($associations['BelongsTo'])) {
                    foreach ($associations['BelongsTo'] as $alias => $details) {
                        if ($field === $details['foreignKey']) {
                            $isKey = true;
                            // TD
                            break;
                        }
                    }
                }

                if ($isKey !== true) {
                    $columnData = $this->Bake->columnData($field, $schema);

                    if(!in_array($columnData['type'], ['integer', 'float', 'decimal', 'biginteger', 'smallinteger', 'tinyinteger'])) {
                       $rows[$key] = "<td>{{ $singularVar.$field|humanize }}</td>";
                    }elseif($columnData['null']) {
                        // TD
                    }else{
                        $rows[$key] = "<td>{{ helper_Number_format($singularVar.$field) }}</td>";
                    }
                }
            }

            return implode("\n", $rows);
        };

        $pk = $singularVar . '.' . $primaryKey[0];

        $template = <<<TEMPLATE
        <div class="$pluralVar index content">
            {{ helper_Html_link(__('New $singularHumanName'), { action : 'add'}, { class : 'button float-right'}) }}
            <h3>{{ __('$pluralHumanName') }}</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            {$tableHeaders()}
                            <th class="actions">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for $singularVar in $pluralVar %}
                            <tr>
                                {$tableRows()}
                                <td class="actions">
                                    {{ helper_Html_link(__('View'), { action : 'view', 0 : $pk}) }}
                                    {{ helper_Html_link(__('Edit'), { action : 'edit', 0 : $pk}) }}
                                    {{ helper_Form_postLink(__('Delete'), { action : 'delete', 0 : $pk }, { confirm : __('Are you sure you want to delete # {0}?', $pk )}) }}
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
            <div class="paginator">
                <ul class="pagination">
                    {{ helper_Paginator_first('<< ' ~ __('first')) }}
                    {{ helper_Paginator_prev('< ' ~ __('previous')) }}
                    {{ helper_Paginator_numbers() }}
                    {{ helper_Paginator_next(__('next') ~ ' >') }}
                    {{ helper_Paginator_last(__('last') ~ ' >>') }}
                </ul>
                <p>{{ helper_Paginator_counter() }}</p>
            </div>
        </div>
        TEMPLATE;

        $indenter = new Indenter();

        $template = $indenter->indent($template);

        return $template;
    }
}
