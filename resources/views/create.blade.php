<!DOCTYPE html>
<html>
<head>
    <title>Создать миграцию</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.6"></script>
</head>
<body>
    <div class="container py-4" id="app">
        <h1 class="mb-4">Создать новую миграцию</h1>

        <div v-for="error in errors">
            <div v-for="error in error" class="errors alert alert-danger">
            @{{ error }}
            </div>
        </div>

        <form @submit.prevent="submitForm" method="POST" action="{{ route('migrate.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="table_name" class="form-label">Имя таблицы</label>
                    <input type="text" class="form-control" id="table_name" name="table_name" required>
                </div>

                <div class="col-md-4">
                    <label for="migration_name" class="form-label">Имя миграции</label>
                    <input type="text" class="form-control" id="migration_name" name="migration_name">
                </div>

                <div class="col-md-4">
                    <label for="type" class="form-label">Тип миграции</label>
                    <select v-model="type" class="form-select" name="type">
                        <option selected value="create">Создание</option>
                        <option value="update">Обновление</option>
                    </select>
                </div>
            </div>

            <h4>Настройки</h4>
            <!--<div class="card-body">-->
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Engine
                            <select class="form-select" name="engine">
                                <option selected value="InnoDB">InnoDB</option>
                                <option value="MyISAM">MyISAM</option>
                                <option value="MEMORY">Memory</option>
                                <option value="CSV">CSV</option>
                                <option value="Archive">Archive</option>
                                <option value="Blackhole">Blackhole</option>
                                <option value="NDB">NDB</option>
                                <option value="Merge">Merge</option>
                                <option value="Federated">Federated</option>
                                <option value="Example">Example</option>
                            </select>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Charset
                            <select class="form-select" name="charset">
                                <option v-for="value, key in charset" :value="value" :selected="value == 'utf8mb4'">@{{ value }}</option>
                            </select>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Collation
                            <select class="form-select" name="collation" >
                                <optgroup 
                                    v-for="collations, name in collation" 
                                    :label="name">
                                    
                                    <option 
                                        v-for="collation, key in collations" 
                                        :value="collation" 
                                        :key="key">
                                            @{{ collation }}
                                    </option>
                                </optgroup>
                            </select>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Timestamps
                            <input class="form-check-input" type="checkbox" name="timestamps" value="1">
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Temporary
                            <input class="form-check-input" type="checkbox" name="temporary" value="1">
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Comment
                            <input class="form-control" type="text" name="comment" placeholder="">
                        </label>
                    </div>
                </div>
            <!--</div>-->
            
            <h4>Поля</h4>
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-primary" @click="addField">Добавить поле</button>
            </div>
            <div v-for="(field, index) in fields" :key="index" class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Имя поля</label>
                            <input type="text" class="field-name form-control" v-model="field.name" :name="'fields[' + index + '][name]'" required :disabled="false">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Тип</label>
                            <select class="form-select" v-model="selectedValue[index]" :name="'fields[' + index + '][type]'" @change="typeSelect">
                                <optgroup label="Булевы типы">
                                    <option value="boolean">Boolean</option>
                                </optgroup>
                                <optgroup label="Строки и текст">
                                    <option value="char">char</option>
                                    <option value="longText">LongText</option>
                                    <option value="mediumText">MediumText</option>
                                    <option value="string">String</option>
                                    <option value="text">Text</option>
                                    <option value="tinyText">TinyText</option>
                                </optgroup>
                                <optgroup label="Числовые типы">
                                    <option value="bigIncrements">bigIncrements</option>
                                    <option value="bigInteger">Big Integer</option>
                                    <option value="decimal">Decimal</option>
                                    <option value="double">Double</option>
                                    <option value="float">Float</option>
                                    <option value="id">ID</option>
                                    <option value="increments">increments</option>
                                    <option value="integer">Integer</option>
                                    <option value="mediumIncrements">mediumIncrements</option>
                                    <option value="mediumInteger">mediumInteger</option>
                                    <option value="smallIncrements">smallIncrements</option>
                                    <option value="smallInteger">smallInteger</option>
                                    <option value="tinyIncrements">tinyIncrements</option>
                                    <option value="tinyInteger">tinyInteger</option>
                                    <option value="unsignedBigInteger">unsignedBigInteger</option>
                                    <option value="unsignedInteger">unsignedInteger</option>
                                    <option value="unsignedMediumInteger">unsignedMediumInteger</option>
                                    <option value="unsignedTinyInteger">unsignedTinyInteger</option>
                                </optgroup>
                                <optgroup label="Типы даты и времени">
                                    <option value="dateTime">DateTime</option>
                                    <option value="date">Date</option>
                                    <option value="time">Time</option>
                                    <option value="timestamp">Timestamp</option>
                                
                                </optgroup>
                                <optgroup label="Двоичные типы">
                                    <option value="binary">binary</option>
                                </optgroup>
                                <optgroup label="Типы объектов и Json">
                                    <option value="json">JSON</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Длина (если нужно)</label>
                            <input type="number" class="field-length form-control" v-model="field.length" :name="'fields[' + index + '][length]'">
                        </div>


                    </div>
                    
                    <div class="modifiers row mb-3">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" @click="addModifier(index)">Добавить модификатор</button>
                        </div>

                        <draggable v-model="modifiers[index]" draggable=".modifier" item-key="id" @start="drag=true" @end="drag=false">
                        <div v-for="(modifier, modifierIndex) in modifiers[index]" :key="modifier.id" class="modifier mb-3">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Модификаторы</label>
                                    <select class="modifiers form-select" v-model="modifier.name" :name="'fields[' + index + '][modifiers]['+ modifierIndex +'][modifier]'" @change="modifiersChange(modifier, index, modifierIndex)">
                                        <option value="after">after</option>
                                        <option value="change">change</option>
                                        <option value="autoIncrement">autoIncrement</option>
                                        <option value="charset">charset</option>
                                        <option value="collation">collation</option>
                                        <option value="comment">comment</option>
                                        <option value="default">default</option>
                                        <option value="first">first</option>
                                        <option value="from">from</option>
                                        <option value="invisible">invisible</option>
                                        <option value="nullable">nullable</option>
                                        <option value="storedAs">storedAs</option>
                                        <option value="unsigned">unsigned</option>
                                        <option value="useCurrent">useCurrent</option>
                                        <option value="useCurrentOnUpdate">useCurrentOnUpdate</option>
                                        <option value="virtualAs">virtualAs</option>
                                        <option value="generatedAs">generatedAs</option>
                                        <option value="always">always</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Значение модификатора</label>
                                    <input v-if="!(modifiersTypeOne + modifiersTypeSelect).includes(modifier.name)" class="modifiers-value form-control" type="text" :name="'fields[' + index + '][modifiers]['+ modifierIndex +'][value]'">

                                    <select v-if="modifiersTypeSelect.includes(modifier.name)" class="modifiers-value-select form-select" v-model="modifier.value" :name="'fields[' + index + '][modifiers]['+ modifierIndex +'][value]'" v-bind:data-field-index="index" v-bind:data-modifier-index="modifierIndex">
                                        <template v-if="modifier.name === 'collation'">
                                            <optgroup v-for="collations, name in modifier.selectValue" :label="name">
                                                <option v-for="collation, key in collations" :value="collation" :key="key">@{{ collation }}</option>
                                            </optgroup>
                                        </template>
                                        <template v-else>
                                            <option v-for="value, key in modifier.selectValue" :value="value" :key="key">@{{ value }}</option>
                                        </template>
                                    </select>
                                </div>

                                

                                <div class="col-md-3 mt-3">
                                    <button type="button" class="btn btn-sm btn-danger" @click="removeModifier(index, modifierIndex)">Удалить</button>
                                </div>
                            </div>
                        </div>
                        </draggable>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Методы</label>
                        <select class="form-select" name="methods">
                            <option value="after">after</option>
                        </select>
                    </div>

                    <h4>Индексы для колонки</h4>
                    <div class="row mt-3">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       v-model="field.primary" 
                                       :name="'fields[' + index + '][primary]'" 
                                       :id="'primary-'+index" 
                                       value="1"
                                       :checked="field.primary"
                                       @change="updatePrimaryKey(field, index)">
                                <label class="form-check-label" :for="'primary-'+index">
                                    Primary
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       v-model="field.index" 
                                       :name="'fields[' + index + '][index]'" 
                                       :id="'index-'+index" 
                                       value="1"
                                       :checked="field.index"
                                       @change="updateIndexKey(field, index)">
                                <label class="form-check-label" :for="'index-'+index">
                                    Index
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       v-model="field.unique" 
                                       :name="'fields[' + index + '][unique]'" 
                                       :id="'unique-'+index" 
                                       value="1"
                                       :checked="field.unique"
                                       @change="updateUniqueKey(field, index)">
                                <label class="form-check-label" :for="'unique-'+index">
                                    Unique
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       v-model="field.fullText" 
                                       :name="'fields[' + index + '][fullText]'" 
                                       :id="'fullText-'+index" 
                                       value="1"
                                       :checked="field.fullText"
                                       @change="updateFullTextKey(field, index)">
                                <label class="form-check-label" :for="'fullText-'+index">
                                    FullText
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       v-model="field.spatialIndex" 
                                       :name="'fields[' + index + '][spatialIndex]'" 
                                       :id="'spatialIndex-'+index" 
                                       value="1"
                                       :checked="field.spatialIndex"
                                       @change="updateSpatialIndexKey(field, index)">
                                <label class="form-check-label" :for="'spatialIndex-'+index">
                                    SpatialIndex
                                </label>
                            </div>
                        </div>


                    </div>
<!-- 
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="field.nullable" :name="'fields[' + index + '][nullable]'" id="'nullable-'+index" value="1">
                                <label class="form-check-label" :for="'nullable-'+index">
                                    Nullable
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="field.unsigned" :name="'fields[' + index + '][unsigned]'" id="'unsigned-'+index" value="1">
                                <label class="form-check-label" :for="'unsigned-'+index">
                                    Unsigned
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="field.auto_increment" :name="'fields[' + index + '][auto_increment]'" id="'auto_increment-'+index" value="1">
                                <label class="form-check-label" :for="'auto_increment-'+index">
                                    Auto Increment
                                </label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Значение по умолчанию</label>
                            <input type="text" class="form-control" v-model="field.default" :name="'fields[' + index + '][default]'">
                        </div>
                    </div> -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-danger" @click="removeField(index)">Удалить</button>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Индексы</h4>
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-primary" @click="addIndex">Добавить индекс</button>
            </div>

            <div v-for="(index, indexIndex) in indexes" :key="index.id" class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Тип индекса</label>
                            <select class="form-select" v-model="index.type" :name="'indexes[' + indexIndex + '][type]'">
                                <option value="index">Обычный индекс</option>
                                <option value="unique">Уникальный индекс</option>
                                <option value="primary" :disabled="indexes.some(idx => (idx.type === 'primary'))">Первичный ключ</option>
                                <option value="fulltext">Fulltext индекс</option>
                                <option value="spatial">Spatial индекс</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Имя индекса (необязательно)</label>
                            <input type="text" class="form-control" v-model="index.name" 
                                   :name="'indexes[' + indexIndex + '][name]'" 
                                   placeholder="Автоматически сгенерируется">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Алгоритм</label>
                            <select class="form-select" v-model="index.algorithm" :name="'indexes[' + indexIndex + '][algorithm]'">
                                <option value="">По умолчанию</option>
                                <option value="btree">B-Tree</option>
                                <option value="hash">Hash</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Поля для индекса</label>
                            <multiselect 
                                v-model="index.columns"
                                :options="availableFields()"
                                :multiple="true"
                                :close-on-select="false"
                                placeholder="Выберите поля"
                                label="name"
                                track-by="name"

                            ></multiselect>
                            <input type="hidden" v-for="(col, colIndex) in index.columns" 
                                   :name="'indexes[' + indexIndex + '][columns][' + colIndex + ']'"
                                   :value="col.name" required>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger ms-auto" 
                                    @click="removeIndex(indexIndex)">
                                Удалить индекс
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Внешние ключи</h4>
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-primary" @click="addForeignKey">Добавить внешний ключ</button>
            </div>

            <div v-for="(fk, fkIndex) in foreignKeys" :key="fk.id" class="card mb-3">
                <div class="card-body">
                    <div class="col-md-12">
                        <label class="form-label">Реальные таблицы</label>
                        <input :checked="true" class="form-check-input" type="checkbox">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Имя ключа (опционально)</label>
                            <input type="text" class="form-control" v-model="fk.name" 
                                   :name="`foreignKeys[${fkIndex}][name]`">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Колонка</label>
                            <select class="form-select" v-model="fk.column" 
                                    :name="`foreignKeys[${fkIndex}][column]`" required>
                                <option v-for="field in fields" :value="field.name" :key="field.name">
                                    @{{ field.name }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Ссылающаяся таблица</label>
                            <select class="form-select" v-model="fk.referenceTable" 
                                    :name="`foreignKeys[${fkIndex}][referenceTable]`" required>
                                <option v-for="table in availableTables" :value="table" :key="table">
                                    @{{ table }}
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Ссылающаяся колонка</label>
                            <select class="form-select" v-model="fk.referenceColumn" 
                                    :name="`foreignKeys[${fkIndex}][referenceColumn]`" required>
                                <option v-if="fk.referenceTable" 
                                        v-for="column in getTableColumns(fk.referenceTable)" 
                                        :value="column" :key="column">
                                    @{{ column }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">On Delete</label>
                            <select class="form-select" v-model="fk.onDelete" 
                                    :name="`foreignKeys[${fkIndex}][onDelete]`">
                                <option value="RESTRICT">RESTRICT</option>
                                <option value="CASCADE">CASCADE</option>
                                <option value="SET NULL">SET NULL</option>
                                <option value="NO ACTION">NO ACTION</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">On Update</label>
                            <select class="form-select" v-model="fk.onUpdate" 
                                    :name="`foreignKeys[${fkIndex}][onUpdate]`">
                                <option value="RESTRICT">RESTRICT</option>
                                <option value="CASCADE">CASCADE</option>
                                <option value="SET NULL">SET NULL</option>
                                <option value="NO ACTION">NO ACTION</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-sm btn-danger" 
                                    @click="removeForeignKey(fkIndex)">
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Создать миграцию</button>
        </form>
    </div>
    <script>
        // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="_token"').getAttribute('value');

        new Vue({
            components: {
                vuedraggable,
                Multiselect: window.VueMultiselect.default,
            },
            el: '#app',
            data: {
                charset: [
                    'armscii8',
                    'ascii',
                    'big5',
                    'binary',
                    'cp1250',
                    'cp1251',
                    'cp1256',
                    'cp1257',
                    'cp850',
                    'cp852',
                    'cp866',
                    'cp932',
                    'dec8',
                    'eucjpms',
                    'euckr',
                    'gb18030',
                    'gb2312',
                    'gbk',
                    'geostd8',
                    'greek',
                    'hebrew',
                    'hp8',
                    'keybcs2',
                    'koi8r',
                    'koi8u',
                    'latin1',
                    'latin2',
                    'latin5',
                    'latin7',
                    'macce',
                    'macroman',
                    'sjis',
                    'swe7',
                    'tis620',
                    'ucs2',
                    'ujis',
                    'utf16',
                    'utf16le',
                    'utf32',
                    'utf8mb3',
                    'utf8mb4',
                ],
                collation: {
                  "": ["(режим сопоставления)"],
                  "armscii8": [
                    "armscii8_bin",
                    "armscii8_general_ci"
                  ],
                  "ascii": [
                    "ascii_bin",
                    "ascii_general_ci"
                  ],
                  "big5": [
                    "big5_bin",
                    "big5_chinese_ci"
                  ],
                  "binary": [
                    "binary"
                  ],
                  "cp1250": [
                    "cp1250_bin",
                    "cp1250_croatian_ci",
                    "cp1250_czech_cs",
                    "cp1250_general_ci",
                    "cp1250_polish_ci"
                  ],
                  "cp1251": [
                    "cp1251_bin",
                    "cp1251_bulgarian_ci",
                    "cp1251_general_ci",
                    "cp1251_general_cs",
                    "cp1251_ukrainian_ci"
                  ],
                  "cp1256": [
                    "cp1256_bin",
                    "cp1256_general_ci"
                  ],
                  "cp1257": [
                    "cp1257_bin",
                    "cp1257_general_ci",
                    "cp1257_lithuanian_ci"
                  ],
                  "cp850": [
                    "cp850_bin",
                    "cp850_general_ci"
                  ],
                  "cp852": [
                    "cp852_bin",
                    "cp852_general_ci"
                  ],
                  "cp866": [
                    "cp866_bin",
                    "cp866_general_ci"
                  ],
                  "cp932": [
                    "cp932_bin",
                    "cp932_japanese_ci"
                  ],
                  "dec8": [
                    "dec8_bin",
                    "dec8_swedish_ci"
                  ],
                  "eucjpms": [
                    "eucjpms_bin",
                    "eucjpms_japanese_ci"
                  ],
                  "euckr": [
                    "euckr_bin",
                    "euckr_korean_ci"
                  ],
                  "gb18030": [
                    "gb18030_bin",
                    "gb18030_chinese_ci",
                    "gb18030_unicode_520_ci"
                  ],
                  "gb2312": [
                    "gb2312_bin",
                    "gb2312_chinese_ci"
                  ],
                  "gbk": [
                    "gbk_bin",
                    "gbk_chinese_ci"
                  ],
                  "geostd8": [
                    "geostd8_bin",
                    "geostd8_general_ci"
                  ],
                  "greek": [
                    "greek_bin",
                    "greek_general_ci"
                  ],
                  "hebrew": [
                    "hebrew_bin",
                    "hebrew_general_ci"
                  ],
                  "hp8": [
                    "hp8_bin",
                    "hp8_english_ci"
                  ],
                  "keybcs2": [
                    "keybcs2_bin",
                    "keybcs2_general_ci"
                  ],
                  "koi8r": [
                    "koi8r_bin",
                    "koi8r_general_ci"
                  ],
                  "koi8u": [
                    "koi8u_bin",
                    "koi8u_general_ci"
                  ],
                  "latin1": [
                    "latin1_bin",
                    "latin1_danish_ci",
                    "latin1_general_ci",
                    "latin1_general_cs",
                    "latin1_german1_ci",
                    "latin1_german2_ci",
                    "latin1_spanish_ci",
                    "latin1_swedish_ci"
                  ],
                  "latin2": [
                    "latin2_bin",
                    "latin2_croatian_ci",
                    "latin2_czech_cs",
                    "latin2_general_ci",
                    "latin2_hungarian_ci"
                  ],
                  "latin5": [
                    "latin5_bin",
                    "latin5_turkish_ci"
                  ],
                  "latin7": [
                    "latin7_bin",
                    "latin7_estonian_cs",
                    "latin7_general_ci",
                    "latin7_general_cs"
                  ],
                  "macce": [
                    "macce_bin",
                    "macce_general_ci"
                  ],
                  "macroman": [
                    "macroman_bin",
                    "macroman_general_ci"
                  ],
                  "sjis": [
                    "sjis_bin",
                    "sjis_japanese_ci"
                  ],
                  "swe7": [
                    "swe7_bin",
                    "swe7_swedish_ci"
                  ],
                  "tis620": [
                    "tis620_bin",
                    "tis620_thai_ci"
                  ],
                  "ucs2": [
                    "ucs2_bin",
                    "ucs2_croatian_ci",
                    "ucs2_czech_ci",
                    "ucs2_danish_ci",
                    "ucs2_esperanto_ci",
                    "ucs2_estonian_ci",
                    "ucs2_general_ci",
                    "ucs2_general_mysql500_ci",
                    "ucs2_german2_ci",
                    "ucs2_hungarian_ci",
                    "ucs2_icelandic_ci",
                    "ucs2_latvian_ci",
                    "ucs2_lithuanian_ci",
                    "ucs2_persian_ci",
                    "ucs2_polish_ci",
                    "ucs2_roman_ci",
                    "ucs2_romanian_ci",
                    "ucs2_sinhala_ci",
                    "ucs2_slovak_ci",
                    "ucs2_slovenian_ci",
                    "ucs2_spanish2_ci",
                    "ucs2_spanish_ci",
                    "ucs2_swedish_ci",
                    "ucs2_turkish_ci",
                    "ucs2_unicode_520_ci",
                    "ucs2_unicode_ci",
                    "ucs2_vietnamese_ci"
                  ],
                  "ujis": [
                    "ujis_bin",
                    "ujis_japanese_ci"
                  ],
                  "utf16": [
                    "utf16_bin",
                    "utf16_croatian_ci",
                    "utf16_czech_ci",
                    "utf16_danish_ci",
                    "utf16_esperanto_ci",
                    "utf16_estonian_ci",
                    "utf16_general_ci",
                    "utf16_german2_ci",
                    "utf16_hungarian_ci",
                    "utf16_icelandic_ci",
                    "utf16_latvian_ci",
                    "utf16_lithuanian_ci",
                    "utf16_persian_ci",
                    "utf16_polish_ci",
                    "utf16_roman_ci",
                    "utf16_romanian_ci",
                    "utf16_sinhala_ci",
                    "utf16_slovak_ci",
                    "utf16_slovenian_ci",
                    "utf16_spanish2_ci",
                    "utf16_spanish_ci",
                    "utf16_swedish_ci",
                    "utf16_turkish_ci",
                    "utf16_unicode_520_ci",
                    "utf16_unicode_ci",
                    "utf16_vietnamese_ci"
                  ],
                  "utf16le": [
                    "utf16le_bin",
                    "utf16le_general_ci"
                  ],
                  "utf32": [
                    "utf32_bin",
                    "utf32_croatian_ci",
                    "utf32_czech_ci",
                    "utf32_danish_ci",
                    "utf32_esperanto_ci",
                    "utf32_estonian_ci",
                    "utf32_general_ci",
                    "utf32_german2_ci",
                    "utf32_hungarian_ci",
                    "utf32_icelandic_ci",
                    "utf32_latvian_ci",
                    "utf32_lithuanian_ci",
                    "utf32_persian_ci",
                    "utf32_polish_ci",
                    "utf32_roman_ci",
                    "utf32_romanian_ci",
                    "utf32_sinhala_ci",
                    "utf32_slovak_ci",
                    "utf32_slovenian_ci",
                    "utf32_spanish2_ci",
                    "utf32_spanish_ci",
                    "utf32_swedish_ci",
                    "utf32_turkish_ci",
                    "utf32_unicode_520_ci",
                    "utf32_unicode_ci",
                    "utf32_vietnamese_ci"
                  ],
                  "utf8mb3": [
                    "utf8mb3_bin",
                    "utf8mb3_croatian_ci",
                    "utf8mb3_czech_ci",
                    "utf8mb3_danish_ci",
                    "utf8mb3_esperanto_ci",
                    "utf8mb3_estonian_ci",
                    "utf8mb3_general_ci",
                    "utf8mb3_general_mysql500_ci",
                    "utf8mb3_german2_ci",
                    "utf8mb3_hungarian_ci",
                    "utf8mb3_icelandic_ci",
                    "utf8mb3_latvian_ci",
                    "utf8mb3_lithuanian_ci",
                    "utf8mb3_persian_ci",
                    "utf8mb3_polish_ci",
                    "utf8mb3_roman_ci",
                    "utf8mb3_romanian_ci",
                    "utf8mb3_sinhala_ci",
                    "utf8mb3_slovak_ci",
                    "utf8mb3_slovenian_ci",
                    "utf8mb3_spanish2_ci",
                    "utf8mb3_spanish_ci",
                    "utf8mb3_swedish_ci",
                    "utf8mb3_tolower_ci",
                    "utf8mb3_turkish_ci",
                    "utf8mb3_unicode_520_ci",
                    "utf8mb3_unicode_ci",
                    "utf8mb3_vietnamese_ci"
                  ],
                  "utf8mb4": [
                    "utf8mb4_0900_ai_ci",
                    "utf8mb4_0900_as_ci",
                    "utf8mb4_0900_as_cs",
                    "utf8mb4_0900_bin",
                    "utf8mb4_bg_0900_ai_ci",
                    "utf8mb4_bg_0900_as_cs",
                    "utf8mb4_bin",
                    "utf8mb4_bs_0900_ai_ci",
                    "utf8mb4_bs_0900_as_cs",
                    "utf8mb4_croatian_ci",
                    "utf8mb4_cs_0900_ai_ci",
                    "utf8mb4_cs_0900_as_cs",
                    "utf8mb4_czech_ci",
                    "utf8mb4_da_0900_ai_ci",
                    "utf8mb4_da_0900_as_cs",
                    "utf8mb4_danish_ci",
                    "utf8mb4_de_pb_0900_ai_ci",
                    "utf8mb4_de_pb_0900_as_cs",
                    "utf8mb4_eo_0900_ai_ci",
                    "utf8mb4_eo_0900_as_cs",
                    "utf8mb4_es_0900_ai_ci",
                    "utf8mb4_es_0900_as_cs",
                    "utf8mb4_es_trad_0900_ai_ci",
                    "utf8mb4_es_trad_0900_as_cs",
                    "utf8mb4_esperanto_ci",
                    "utf8mb4_estonian_ci",
                    "utf8mb4_et_0900_ai_ci",
                    "utf8mb4_et_0900_as_cs",
                    "utf8mb4_general_ci",
                    "utf8mb4_german2_ci",
                    "utf8mb4_gl_0900_ai_ci",
                    "utf8mb4_gl_0900_as_cs",
                    "utf8mb4_hr_0900_ai_ci",
                    "utf8mb4_hr_0900_as_cs",
                    "utf8mb4_hu_0900_ai_ci",
                    "utf8mb4_hu_0900_as_cs",
                    "utf8mb4_hungarian_ci",
                    "utf8mb4_icelandic_ci",
                    "utf8mb4_is_0900_ai_ci",
                    "utf8mb4_is_0900_as_cs",
                    "utf8mb4_ja_0900_as_cs",
                    "utf8mb4_ja_0900_as_cs_ks",
                    "utf8mb4_la_0900_ai_ci",
                    "utf8mb4_la_0900_as_cs",
                    "utf8mb4_latvian_ci",
                    "utf8mb4_lithuanian_ci",
                    "utf8mb4_lt_0900_ai_ci",
                    "utf8mb4_lt_0900_as_cs",
                    "utf8mb4_lv_0900_ai_ci",
                    "utf8mb4_lv_0900_as_cs",
                    "utf8mb4_mn_cyrl_0900_ai_ci",
                    "utf8mb4_mn_cyrl_0900_as_cs",
                    "utf8mb4_nb_0900_ai_ci",
                    "utf8mb4_nb_0900_as_cs",
                    "utf8mb4_nn_0900_ai_ci",
                    "utf8mb4_nn_0900_as_cs",
                    "utf8mb4_persian_ci",
                    "utf8mb4_pl_0900_ai_ci",
                    "utf8mb4_pl_0900_as_cs",
                    "utf8mb4_polish_ci",
                    "utf8mb4_ro_0900_ai_ci",
                    "utf8mb4_ro_0900_as_cs",
                    "utf8mb4_roman_ci",
                    "utf8mb4_romanian_ci",
                    "utf8mb4_ru_0900_ai_ci",
                    "utf8mb4_ru_0900_as_cs",
                    "utf8mb4_sinhala_ci",
                    "utf8mb4_sk_0900_ai_ci",
                    "utf8mb4_sk_0900_as_cs",
                    "utf8mb4_sl_0900_ai_ci",
                    "utf8mb4_sl_0900_as_cs",
                    "utf8mb4_slovak_ci",
                    "utf8mb4_slovenian_ci",
                    "utf8mb4_spanish2_ci",
                    "utf8mb4_spanish_ci",
                    "utf8mb4_sr_latn_0900_ai_ci",
                    "utf8mb4_sr_latn_0900_as_cs",
                    "utf8mb4_sv_0900_ai_ci",
                    "utf8mb4_sv_0900_as_cs",
                    "utf8mb4_swedish_ci",
                    "utf8mb4_tr_0900_ai_ci",
                    "utf8mb4_tr_0900_as_cs",
                    "utf8mb4_turkish_ci",
                    "utf8mb4_unicode_520_ci",
                    "utf8mb4_unicode_ci",
                    "utf8mb4_vi_0900_ai_ci",
                    "utf8mb4_vi_0900_as_cs",
                    "utf8mb4_vietnamese_ci",
                    "utf8mb4_zh_0900_as_cs"
                  ]
                },
                errors: {},
                type: 'create',
                fields: [
                    { name: '', type: 'boolean', length: null, nullable: false, unsigned: false, auto_increment: false, primary: false, default: '' }
                ],
                fieldsName: [''],
                selectedValue: ['boolean'],
                modifiers: [
                    [/*{
                        id: Date.now(),
                        name: null,
                        value: null,
                        selectValue: null,
                    }*/],
                ],
                modifiersTypeSelect: ['after', 'charset', 'collation'],
                modifiersTypeOne: ['change', 'autoIncrement', 'first', 'invisible', 'unsigned', 'useCurrent', 'useCurrentOnUpdate', 'always'],
                
                indexes: [], // Массив для хранения индексов
                indexTypes: [
                    { value: 'index', text: 'Обычный индекс' },
                    { value: 'unique', text: 'Уникальный индекс' },
                    { value: 'primary', text: 'Первичный ключ' },
                    { value: 'fulltext', text: 'Fulltext индекс' },
                    { value: 'spatial', text: 'Spatial индекс' }
                ],
                algorithms: [
                    { value: '', text: 'По умолчанию' },
                    { value: 'btree', text: 'B-Tree' },
                    { value: 'hash', text: 'Hash' }
                ],

                foreignKeys: [],
                availableTables: [], // Список таблиц БД
                tableColumns: {}     // Колонки таблиц: {table1: ['id', 'name'], ...}
            },
            methods: {
                addField() {
                    this.fields.push({ name: '', type: 'id', length: null, nullable: false, unsigned: false, auto_increment: false, primary: false, default: '' });
                    
                    this.modifiers.push([]);
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                    this.modifiers.splice(index, 1);
                },
                addModifier(index) {
                    this.modifiers[index].push({
                        id: Date.now(),
                        name: null,
                        value: null,
                        selectValue: null,
                    });
                    // this.modifiers[index].selectValue.push(null);
                    // this.modifiers[index].valueSelectSelected.push(null);
                },
                removeModifier(index, modifierIndex) {
                    this.modifiers[index].splice(modifierIndex, 1);
                    // this.modifiers[index].selectValue.splice(modifierIndex, 1);
                    // this.modifiers[index].valueSelectSelected.splice(modifierIndex, 1);
                },
                /*shouldShowSelect(modifierName) {
                  return ['charset', 'collation'].includes(modifierName);
                },*/

                addIndex() {
                    this.indexes.push({
                        id: Date.now() + Math.random(),
                        type: 'index',
                        name: '',
                        algorithm: '',
                        columns: []
                    });
                },
                
                removeIndex(index) {
                    this.indexes.splice(index, 1);
                },
                
                // Получаем список доступных полей для выбора в индексе
                /*get */availableFields() {
                    return this.fields.map(field => ({
                        name: field.name,
                        type: field.type
                    }));
                },

                /*updatePrimaryKey(field, index) {
                    if (field.primary) {
                        // Если выбрано как первичный ключ, добавляем в индексы
                        const primaryIndex = {
                            id: Date.now() + Math.random(),
                            type: 'primary',
                            name: '',
                            algorithm: '',
                            columns: [{name: field.name}]
                        };
                        this.indexes.push(primaryIndex);
                    } else {
                        // Удаляем первичный ключ из индексов
                        this.indexes = this.indexes.filter(idx => 
                            !(idx.type === 'primary' && 
                              idx.columns.some(col => col.name === field.name))
                        );
                    }
                },*/

                updatePrimaryKey(field, index) {
                    if (field.primary) {
                        var primaries = this.indexes.some(idx => (idx.type === 'primary'));

                        if (primaries) {
                            if (confirm('Уже есть primary индекс, удалить его?')) {
                                var index = this.indexes.findIndex(idx => (idx.type === 'primary'));
                                this.indexes.splice(index, 1);
                            } else {
                                alert('Тогда сбросится этот индекс primary');
                                field.primary = false;
                            }
                        }
                    } else {

                    }
                },

                addForeignKey() {
                    this.foreignKeys.push({
                        id: Date.now() + Math.random(),
                        name: '',
                        column: '',
                        referenceTable: '',
                        referenceColumn: '',
                        onDelete: 'RESTRICT',
                        onUpdate: 'RESTRICT'
                    });
                },
                
                removeForeignKey(index) {
                    this.foreignKeys.splice(index, 1);
                },
                
                // Загрузка таблиц с сервера
                async loadTables() {
                    try {
                        const response = await axios.get('/api/tables');
                        this.availableTables = response.data;
                    } catch (error) {
                        console.error('Ошибка загрузки таблиц:', error);
                    }
                },
                
                // Загрузка колонок таблицы
                async loadTableColumns(tableName) {
                    if (!tableName) return;
                    
                    if (!this.tableColumns[tableName]) {
                        try {
                            const response = await axios.get(`/api/tables/${tableName}/columns`);
                            this.$set(this.tableColumns, tableName, response.data);
                        } catch (error) {
                            console.error(`Ошибка загрузки колонок для ${tableName}:`, error);
                        }
                    }
                    return this.tableColumns[tableName] || [];
                },
                
                getTableColumns(tableName) {
                    return this.tableColumns[tableName] || [];
                },

                typeSelect(e) {
                    // console.log(e.target, e.target.closest('.card-body').querySelector('.field-name'));
                    var parentEl = e.target.closest('.card-body');
                    parentEl.querySelector('.field-name').disabled = false;
                    parentEl.querySelector('.field-length').step = 1;
                    if (e.target.value == 'id') {
                        parentEl.querySelector('.field-name').disabled = true;
                    } else if (e.target.value == 'decimal') {
                        parentEl.querySelector('.field-length').step = 0.1;
                    }
                },
                modifiersChange(modifier, fieldIndex, modifierIndex) {
                    console.log(this.$el);/*
                    var parentEl = e.target.closest('.row'),
                        value = e.target.value;*/

                    var index = fieldIndex;

                    

                    // parentEl.querySelector('.modifiers-value').classList.remove('d-none');
                    // parentEl.querySelector('.modifiers-value').disabled = false;
                    // parentEl.querySelector('.modifiers-value-select').classList.add('d-none');

                    

                    if (this.modifiersTypeOne.includes(modifier.name)) {
                        // parentEl.querySelector('.modifiers-value').classList.add('d-none');
                    } else if (this.modifiersTypeSelect.includes(modifier.name)) {
                        // setTimeout(function() {



                        // if (parentEl.querySelector('.modifiers-value-select')) {
                        /*console.log(parentEl.querySelector('.modifiers-value-select'))
                        var index = parentEl.querySelector('.modifiers-value-select').dataset.fieldIndex;
                        var modifierIndex = parentEl.querySelector('.modifiers-value-select').dataset.modifierIndex;
*/
                        modifier.selectValue = [];
                        // this.modifiers[index].valueSelectSelected[modifierIndex] = [null];
                        modifier.value = null;

                        if (modifier.name == 'charset') {
                            // parentEl.querySelector('.modifiers-value').classList.add('d-none');
                            // parentEl.querySelector('.modifiers-value').disabled = true;
                            // parentEl.querySelector('.modifiers-value-select').classList.remove('d-none');

                            // this.modifiers[index].selectValue[modifierIndex] = this.charset;
                            modifier.selectValue = this.charset;
                            // this.modifiers[index].valueSelectSelected[modifierIndex] = 'utf8mb4';
                            modifier.value = 'utf8mb4';
                        } else if (modifier.name == 'collation') {
                            // parentEl.querySelector('.modifiers-value').classList.add('d-none');
                            // parentEl.querySelector('.modifiers-value').disabled = true;
                            // parentEl.querySelector('.modifiers-value-select').classList.remove('d-none');

                            // this.modifiersSelectValue = this.collation;
                            // var index = parentEl.querySelector('.modifiers-value-select').dataset.fieldIndex;
                            // this.modifiersValueSelectSelected[index] = 'utf8mb4_general_ci';
                            
                            modifier.selectValue = this.collation;
                        } else if (modifier.name == 'after') {
                            // parentEl.querySelector('.modifiers-value').classList.add('d-none');
                            // parentEl.querySelector('.modifiers-value').disabled = true;
                            // parentEl.querySelector('.modifiers-value-select').classList.remove('d-none');

                            var columns = this.fields.map(function(field) {
                                return field.name;
                            });

                            modifier.selectValue = columns;
                        }
                    // }.bind(this), 100);
                    }
                },
                async submitForm(e) {
                    const form = e.target;
                    const formData = new FormData(form);

                    axios.post('/migrations/store', formData)
                        .then(response => {
                            if (response.data.success) {
                                this.errors = {};
                                // обработка успешного ответа
                                // console.log(response.data);
                            } else {
                                // console.log(response);s
                                // this.$forceUpdate();
                                this.errors = response.data.errors;
                                console.log(this.errors);
                            }
                        }).catch(error => {
                            // обработка ошибки
                            console.error(error);
                        });
                }
            },
            watch: {
                selectedValue(newVal) {
                    // console.log(newVal);
                },

                // modifiersValue(value, el) {

                    /*console.log(this.$el)
                    document.querySelector('.modifiers-value').classList.remove('d-none');
                    document.querySelector('.modifiers-value').disabled = false;
                    document.querySelector('.modifiers-value-select').classList.add('d-none')
                    if (value == 'charset') {
                        document.querySelector('.modifiers-value').classList.add('d-none');
                        document.querySelector('.modifiers-value').disabled = true;
                        document.querySelector('.modifiers-value-select').classList.remove('d-none');

                        this.modifiersSelectValue = this.charset;
                        this.modifiersValueSelectSelected = 'utf8mb4';
                    } else if (value == 'collation') {
                        document.querySelector('.modifiers-value').classList.add('d-none');
                        document.querySelector('.modifiers-value').disabled = true;
                        document.querySelector('.modifiers-value-select').classList.remove('d-none');

                        this.modifiersSelectValue = this.collation;
                        this.modifiersValueSelectSelected = 'utf8mb4_general_ci';
                    }*/
                // }



                'foreignKeys': {
                    deep: true,
                    handler(newVal) {
                        newVal.forEach(fk => {
                            if (fk.referenceTable && !this.tableColumns[fk.referenceTable]) {
                                this.loadTableColumns(fk.referenceTable);
                            }
                        });
                    }
                }
            },
            mounted() {
                this.loadTables();
                // ... остальная инициализация ...
            },
        });
    </script>
</body>
</html>