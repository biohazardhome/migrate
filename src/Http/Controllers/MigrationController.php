<?php

namespace Migrate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class MigrationController extends Controller
{

    public function getTables()
    {
        $tables = [];
    
        // Для MySQL
        if (config('database.default') == 'mysql') {
            $result = DB::select('SHOW TABLES');
            $key = 'Tables_in_' . config('database.connections.mysql.database');
            $tables = array_map(fn($item) => $item->{$key}, $result);
        }
        // Для PostgreSQL
        elseif (config('database.default') == 'pgsql') {
            $result = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            $tables = array_column($result, 'tablename');
        }
        // Для SQLite
        elseif (config('database.default') == 'sqlite') {
            $result = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
            $tables = array_column($result, 'name');
        }
        
        return response()->json($tables);
    }

    public function getTableColumns($table)
    {
        if (!Schema::hasTable($table)) {
            return response()->json([], 404);
        }

        $columns = Schema::getColumnListing($table);
        return response()->json($columns);
    }

    public function create()
    {
        return view('migrate::create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_name' => 'required|string|alpha_dash|max:255',
            'type' => 'required|string|alpha_dash|max:255',
            'migration_name' => 'nullable|string|max:255',

            'engine' => 'string|alpha_dash|max:255',
            'charset' => 'string|alpha_dash|max:255',
            'collation' => 'string|max:255',
            'timestamps' => 'nullable|boolean',
            'softDeletes' => 'nullable|boolean',
            'temporary' => 'nullable|boolean',
            'comment' => 'nullable',
            
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'sometimes|required|string|alpha_dash|max:255',
            'fields.*.type' => 'required|string',
            'fields.*.value' => 'nullable|numeric|min:1',
            // 'fields.*.nullable' => 'nullable|boolean',
            // 'fields.*.default' => 'nullable|string|max:255',
            // 'fields.*.unsigned' => 'nullable|boolean',
            // 'fields.*.auto_increment' => 'nullable|boolean',
            'fields.*.primary' => 'nullable|boolean',
            'fields.*.index' => 'nullable|boolean',
            'fields.*.unique' => 'nullable|boolean',
            'fields.*.fullText' => 'nullable|boolean',
            'fields.*.spatialIndex' => 'nullable|boolean',
            
            'modifiers' => 'nullable|array',

            // 'indexes.*.type' => 'nullable',
            'indexes.*.name' => 'nullable',
            'indexes.*.algorithm' => 'nullable',
            'indexes.*.columns' => 'required_with:indexes.*.type',

            'foreignKeys.*.column' => 'required|string',
            'foreignKeys.*.referenceTable' => 'required|string',
            'foreignKeys.*.referenceColumn' => 'required|string',
            'foreignKeys.*.onDelete' => 'required|in:RESTRICT,CASCADE,SET NULL,NO ACTION',
            'foreignKeys.*.onUpdate' => 'required|in:RESTRICT,CASCADE,SET NULL,NO ACTION',
        ]);

        if ($validator->fails()) {
            /*return redirect()->back()
                ->withErrors($validator)
                ->withInput();*/

            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ]);
        }

        $tableName = $request->input('table_name');
        $type = $request->input('type');
        $migrationName = $request->input('migration_name');
        
        $engine = $request->input('engine');
        $charset = $request->input('charset');
        $collation = $request->input('collation');
        $timestamps = $request->input('timestamps');
        $softDeletes = $request->input('softDeletes');
        $temporary = $request->input('temporary');
        $comment = $request->input('comment');
        $modifiers = $request->input('modifiers');
        // $modifiersValue = $request->input('modifiers-value');
        $indexes = $request->input('indexes');
        // dd($indexes);
        // dd($request->all());
        
        $fields = $request->input('fields');
        // Генерация имени миграции
        if (empty($migrationName)) {
            $migrationName = $type .'_'. $tableName . '_table';
        }

        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
        // Генерация содержимого миграции
        $stub = file_get_contents(resource_path('stubs/migration.create.stub'));
        $stub = str_replace('{{className}}', ucfirst($tableName), $stub);
        $stub = str_replace('{{table}}', $tableName, $stub);
        $typeStub = $type == 'update' ? 'table' : $type;
        $stub = str_replace('{{type}}', $typeStub, $stub);
        $columns = [];
        foreach ($fields as $field) {
            if (isset($field['name'])) {

                if (in_array($field['type'], ['string', 'char', 'decimal']) && isset($field['value'])) {
                    if (in_array($field['type'], ['string', 'char'])) {
                        // $column .= '->length(' . $field['value'] . ')';
                        $column = '$table->'. $field['type'] .'(\''. $field['name'] .'\', '. $field['value'] .')';
                    } else if ($field['type'] === 'decimal') {
                        [$total, $places] = array_map(function($item) { return trim($item); }, explode(',', $field['value'])); // 8, 2
                        // dump($total, $places);
                        $column = '$table->decimal(\'' . $field['name'] . '\', '. $total .', '. $places .')';
                    }
                } else {
                    $column = '$table->' . $field['type'] . '(\'' . $field['name'] . '\')';
                }
            } else {
                $column = '$table->' . $field['type'] . '()';
            }

            if (isset($field['modifiers'])) {
                foreach ($field['modifiers'] as $modifier) {
                    // dd($modifier);
                    if (isset($modifier['modifier'])) {
                        if (isset($modifier['value'])) {
                            $column .= '->' . $modifier['modifier'] . '(\'' . $modifier['value'] . '\')';
                        } else {
                            $column .= '->' . $modifier['modifier'] . '()'; 
                        }
                        // dump($modifier['modifier'], $modifier['value']);
                    }
                }
                    // dd();
            }

            
            /*if (isset($field['nullable']) && $field['nullable']) {
                $column .= '->nullable()';
            }
            if (isset($field['default']) && $field['default'] !== '') {
                $column .= '->default(\'' . $field['default'] . '\')';
            }
            if (isset($field['unsigned']) && $field['unsigned']) {
                $column .= '->unsigned()';
            }
            if (isset($field['auto_increment']) && $field['auto_increment']) {
                $column .= '->autoIncrement()';
            }*/
            if (isset($field['primary']) && $field['primary']) {
                $column .= '->primary()';
            }
            if (isset($field['index']) && $field['index']) {
                $column .= '->index()';
            }
            if (isset($field['unique']) && $field['unique']) {
                $column .= '->unique()';
            }
            if (isset($field['fullText']) && $field['fullText']) {
                $column .= '->fullText()';
            }
            if (isset($field['spatialIndex']) && $field['spatialIndex']) {
                $column .= '->spatialIndex()';
            }
            $column .= ';';
            $columns[] = $column;
        }
        $columnsCode = implode("\n            ", $columns);

        if ($indexes) {
            $this->validateIndexes($indexes);
            $indexesCode = $this->generateIndexesCode($indexes);
            $columnsCode .= $indexesCode;
        }

        $foreignKeysCode = $this->generateForeignKeys($request->input('foreignKeys', []));
        $columnsCode .= $foreignKeysCode;

        // dd($columnsCode);
        $parametrsCode = $this->parametrs(compact('engine', 'charset', 'collation', 'timestamps', 'softDeletes', 'temporary', 'comment'));
        $columnsCode .= $parametrsCode;
        $stub = str_replace('{{columns}}', $columnsCode, $stub);
        // Сохраняем файл миграции
        $path = database_path('migrations/'. $fileName);
        file_put_contents($path, $stub);

        // dd($path, $stub);
        // return redirect()
            // ->route('migrations.create')
            // ->with('success', 'Миграция создана: '. $path .' <a href="">Показать </a><code style="white-space: pre;">'. htmlspecialchars($stub) .'</code>');
        
        return response()->json([
            'success' => true,
            'path' => $path,
            'stub' => htmlspecialchars($stub),
        ]);
    }

    public function parametrs($data) {
        extract($data);

        $parametrs = '';

        if ($engine) {
            $parametrs .= "\n            ".'$table->engine(\''. $engine .'\');';
        }

        if ($charset) {
            $parametrs .= "\n            ".'$table->charset(\''. $charset .'\');';
        }

        if ($collation) {
            $parametrs .= "\n            ".'$table->collation(\''. $collation .'\');';
        }

        if ($timestamps) {
            $parametrs .= "\n            ".'$table->timestamps();';
        }

        if ($softDeletes) {
            $parametrs .= "\n            ".'$table->softDeletes();';
        }

        if ($temporary) {
            $parametrs .= "\n            ".'$table->temporary();';
        }

        if ($comment) {
            $parametrs .= "\n            ".'$table->comment(\''. $comment .'\');';
        }

        if ($parametrs) {
            $parametrs = "\n            ". $parametrs; 
        }

        return $parametrs;
    }

    protected function generateIndexesCode($indexes)
    {
        $code = '';
        
        foreach ($indexes as $index) {
            if (isset($index['columns'])) {
                $columns = $this->formatColumns($index['columns']);
                $type = $index['type'];
                $name = $index['name'] ? "'{$index['name']}'" : 'null';
                $algorithm = $index['algorithm'] ? "'{$index['algorithm']}'" : 'null';
                
                $code .= "\n            ";

                switch ($type) {
                    case 'primary':
                        $code .= "\$table->primary($columns, $name);\n            ";
                        break;
                        
                    case 'unique':
                        $code .= "\$table->unique($columns, $name);\n            ";
                        break;
                        
                    case 'index':
                        if ($algorithm !== 'null') {
                            $code .= "\$table->index($columns, $name, $algorithm);\n            ";
                        } else {
                            $code .= "\$table->index($columns, $name);\n            ";
                        }
                        break;
                        
                    case 'fulltext':
                        $code .= "\$table->fullText($columns, $name);\n            ";
                        break;
                        
                    case 'spatial':
                        $code .= "\$table->spatialIndex($columns, $name);\n            ";
                        break;
                }
            }
        }
        
        return $code;
    }

    protected function validateIndexes($indexes)
    {
        $primaryCount = 0;
        $indexNames = [];
        
        foreach ($indexes as $index) {
            // Проверка уникальности имен индексов
            if ($index['name'] && in_array($index['name'], $indexNames)) {
                throw new \Exception("Duplicate index name: {$index['name']}");
            }
            $indexNames[] = $index['name'];
            
            // Проверка количества первичных ключей
            if ($index['type'] === 'primary') {
                $primaryCount++;
                if ($primaryCount > 1) {
                    throw new \Exception('Only one primary key allowed per table');
                }
            }
            
            // Проверка поддержки алгоритма
            if ($index['algorithm'] && !in_array($index['algorithm'], ['', 'btree', 'hash'])) {
                throw new \Exception("Unsupported index algorithm: {$index['algorithm']}");
            }
        }
    }

    protected function formatColumns($columns)
    {
        if (count($columns) === 1) {
            return "'" . $columns[0] . "'";
        }
        
        $formatted = array_map(function($col) {
            return "'$col'";
        }, $columns);
        
        return '[' . implode(', ', $formatted) . ']';
    }

    protected function generateForeignKeys($foreignKeys)
    {
        $code = "\n            ";
        
        foreach ($foreignKeys as $fk) {
            $column = $fk['column'];
            $references = $fk['referenceColumn'];
            $onTable = $fk['referenceTable'];
            $name = $fk['name'] ? "'{$fk['name']}'" : 'null';
            
            $code .= "\$table->foreign($column, $name)"
                   . "->references('$references')"
                   . "->on('$onTable')";
            
            if ($fk['onDelete'] !== 'RESTRICT') {
                $code .= "->onDelete('{$fk['onDelete']}')";
            }
            
            if ($fk['onUpdate'] !== 'RESTRICT') {
                $code .= "->onUpdate('{$fk['onUpdate']}')";
            }
            
            $code .= ";\n            ";
        }
        
        return $code;
    }
}