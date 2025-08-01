<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MigrationCreatorController extends Controller
{
    public function create()
    {
        return view('migrations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_name' => 'required|string|alpha_dash|max:255',

            'engine' => 'string|alpha_dash|max:255',
            'charset' => 'string|alpha_dash|max:255',
            'collation' => 'string|alpha_dash|max:255',
            'timestamps' => 'nullable|boolean',
            
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'sometimes|required|string|alpha_dash|max:255',
            'fields.*.type' => 'required|string|in:id,string,text,integer,bigInteger,float,double,decimal,boolean,date,dateTime,time,timestamp,json',
            'fields.*.length' => 'nullable|integer|min:1',
            'fields.*.nullable' => 'nullable|boolean',
            'fields.*.default' => 'nullable|string|max:255',
            'fields.*.unsigned' => 'nullable|boolean',
            'fields.*.auto_increment' => 'nullable|boolean',
            'fields.*.primary' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $tableName = $request->input('table_name');
        
        $engine = $request->input('engine');
        $charset = $request->input('charset');
        $collation = $request->input('collation');
        $timestamps = $request->input('timestamps');
        // dump($engine);
        
        $fields = $request->input('fields');
        // Генерация имени миграции
        $migrationName = 'create_' . $tableName . '_table';
        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
        // Генерация содержимого миграции
        $stub = file_get_contents(resource_path('stubs/migration.create.stub'));
        $stub = str_replace('{{table}}', $tableName, $stub);
        $columns = [];
        foreach ($fields as $field) {
            if (isset($field['name'])) {
                $column = '$table->' . $field['type'] . '(\'' . $field['name'] . '\')';
            } else {
                $column = '$table->' . $field['type'] . '()';
            }

            if (in_array($field['type'], ['string', 'char']) && isset($field['length'])) {
                $column .= '->length(' . $field['length'] . ')'; // precision
            }
            if (isset($field['nullable']) && $field['nullable']) {
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
            }
            if (isset($field['primary']) && $field['primary']) {
                $column .= '->primary()';
            }
            $column .= ';';
            $columns[] = $column;
        }
        $columnsCode = implode("\n            ", $columns);

        // dd($columnsCode);
        $parametrs = $this->parametrs(compact('engine', 'charset', 'collation', 'timestamps'));
        $columnsCode .= $parametrs;
        $stub = str_replace('{{columns}}', $columnsCode, $stub);
        // Сохраняем файл миграции
        $path = database_path('migrations/'. $fileName);
        file_put_contents($path, $stub);

        // dd($path, $stub);
        return redirect()->route('migrations.create')->with('success', 'Миграция создана: '. $path .' <a href="">Показать </a><code style="white-space: pre;">'. $stub .'</code>');
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

        if ($parametrs) {
            $parametrs = "\n            ". $parametrs; 
        }

        return $parametrs;
    }
}
