<!DOCTYPE html>
<html>
<head>
    <title>Создать миграцию</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container py-4" id="app">
        <h1 class="mb-4">Создать новую миграцию</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {!! session('success') !!}
            </div>
        @endif

        @if($errors)
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        <form method="POST" action="{{ route('migrations.store') }}">
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
                    <select class="form-select" name="type">
                        <option selected value="create">Создание</option>
                        <option value="update">Обновление</option>
                    </select>
                </div>
            </div>

            <h4>Настройки</h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Engine
                            <select class="form-select" name="engine">
                                <option selected value="InnoDB">InnoDB</option>
                                <option value="MyISAM">MyISAM</option>
                                <option value="MEMORY">Memory</option>
                            </select>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Charset
                            <select class="form-select" name="charset">
                                <option value="armscii8">armscii8</option>
                                <option value="ascii">ascii</option>
                                <option value="big5">big5</option>
                                <option value="binary">binary</option>
                                <option value="cp1250">cp1250</option>
                                <option value="cp1251">cp1251</option>
                                <option value="cp1256">cp1256</option>
                                <option value="cp1257">cp1257</option>
                                <option value="cp850">cp850</option>
                                <option value="cp852">cp852</option>
                                <option value="cp866">cp866</option>
                                <option value="cp932">cp932</option>
                                <option value="dec8">dec8</option>
                                <option value="eucjpms">eucjpms</option>
                                <option value="euckr">euckr</option>
                                <option value="gb18030">gb18030</option>
                                <option value="gb2312">gb2312</option>
                                <option value="gbk">gbk</option>
                                <option value="geostd8">geostd8</option>
                                <option value="greek">greek</option>
                                <option value="hebrew">hebrew</option>
                                <option value="hp8">hp8</option>
                                <option value="keybcs2">keybcs2</option>
                                <option value="koi8r">koi8r</option>
                                <option value="koi8u">koi8u</option>
                                <option value="latin1">latin1</option>
                                <option value="latin2">latin2</option>
                                <option value="latin5">latin5</option>
                                <option value="latin7">latin7</option>
                                <option value="macce">macce</option>
                                <option value="macroman">macroman</option>
                                <option value="sjis">sjis</option>
                                <option value="swe7">swe7</option>
                                <option value="tis620">tis620</option>
                                <option value="ucs2">ucs2</option>
                                <option value="ujis">ujis</option>
                                <option value="utf16">utf16</option>
                                <option value="utf16le">utf16le</option>
                                <option value="utf32">utf32</option>
                                <option value="utf8mb3">utf8mb3</option>
                                <option selected value="utf8mb4">utf8mb4</option>
                            </select>
                        </label>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Collation
                            <select class="form-select" name="collation"><option value="">(режим сопоставления)</option><optgroup label="armscii8"><option>armscii8_bin</option><option>armscii8_general_ci</option></optgroup><optgroup label="ascii"><option>ascii_bin</option><option>ascii_general_ci</option></optgroup><optgroup label="big5"><option>big5_bin</option><option>big5_chinese_ci</option></optgroup><optgroup label="binary"><option>binary</option></optgroup><optgroup label="cp1250"><option>cp1250_bin</option><option>cp1250_croatian_ci</option><option>cp1250_czech_cs</option><option>cp1250_general_ci</option><option>cp1250_polish_ci</option></optgroup><optgroup label="cp1251"><option>cp1251_bin</option><option>cp1251_bulgarian_ci</option><option>cp1251_general_ci</option><option>cp1251_general_cs</option><option>cp1251_ukrainian_ci</option></optgroup><optgroup label="cp1256"><option>cp1256_bin</option><option>cp1256_general_ci</option></optgroup><optgroup label="cp1257"><option>cp1257_bin</option><option>cp1257_general_ci</option><option>cp1257_lithuanian_ci</option></optgroup><optgroup label="cp850"><option>cp850_bin</option><option>cp850_general_ci</option></optgroup><optgroup label="cp852"><option>cp852_bin</option><option>cp852_general_ci</option></optgroup><optgroup label="cp866"><option>cp866_bin</option><option>cp866_general_ci</option></optgroup><optgroup label="cp932"><option>cp932_bin</option><option>cp932_japanese_ci</option></optgroup><optgroup label="dec8"><option>dec8_bin</option><option>dec8_swedish_ci</option></optgroup><optgroup label="eucjpms"><option>eucjpms_bin</option><option>eucjpms_japanese_ci</option></optgroup><optgroup label="euckr"><option>euckr_bin</option><option>euckr_korean_ci</option></optgroup><optgroup label="gb18030"><option>gb18030_bin</option><option>gb18030_chinese_ci</option><option>gb18030_unicode_520_ci</option></optgroup><optgroup label="gb2312"><option>gb2312_bin</option><option>gb2312_chinese_ci</option></optgroup><optgroup label="gbk"><option>gbk_bin</option><option>gbk_chinese_ci</option></optgroup><optgroup label="geostd8"><option>geostd8_bin</option><option>geostd8_general_ci</option></optgroup><optgroup label="greek"><option>greek_bin</option><option>greek_general_ci</option></optgroup><optgroup label="hebrew"><option>hebrew_bin</option><option>hebrew_general_ci</option></optgroup><optgroup label="hp8"><option>hp8_bin</option><option>hp8_english_ci</option></optgroup><optgroup label="keybcs2"><option>keybcs2_bin</option><option>keybcs2_general_ci</option></optgroup><optgroup label="koi8r"><option>koi8r_bin</option><option>koi8r_general_ci</option></optgroup><optgroup label="koi8u"><option>koi8u_bin</option><option>koi8u_general_ci</option></optgroup><optgroup label="latin1"><option>latin1_bin</option><option>latin1_danish_ci</option><option>latin1_general_ci</option><option>latin1_general_cs</option><option>latin1_german1_ci</option><option>latin1_german2_ci</option><option>latin1_spanish_ci</option><option>latin1_swedish_ci</option></optgroup><optgroup label="latin2"><option>latin2_bin</option><option>latin2_croatian_ci</option><option>latin2_czech_cs</option><option>latin2_general_ci</option><option>latin2_hungarian_ci</option></optgroup><optgroup label="latin5"><option>latin5_bin</option><option>latin5_turkish_ci</option></optgroup><optgroup label="latin7"><option>latin7_bin</option><option>latin7_estonian_cs</option><option>latin7_general_ci</option><option>latin7_general_cs</option></optgroup><optgroup label="macce"><option>macce_bin</option><option>macce_general_ci</option></optgroup><optgroup label="macroman"><option>macroman_bin</option><option>macroman_general_ci</option></optgroup><optgroup label="sjis"><option>sjis_bin</option><option>sjis_japanese_ci</option></optgroup><optgroup label="swe7"><option>swe7_bin</option><option>swe7_swedish_ci</option></optgroup><optgroup label="tis620"><option>tis620_bin</option><option>tis620_thai_ci</option></optgroup><optgroup label="ucs2"><option>ucs2_bin</option><option>ucs2_croatian_ci</option><option>ucs2_czech_ci</option><option>ucs2_danish_ci</option><option>ucs2_esperanto_ci</option><option>ucs2_estonian_ci</option><option>ucs2_general_ci</option><option>ucs2_general_mysql500_ci</option><option>ucs2_german2_ci</option><option>ucs2_hungarian_ci</option><option>ucs2_icelandic_ci</option><option>ucs2_latvian_ci</option><option>ucs2_lithuanian_ci</option><option>ucs2_persian_ci</option><option>ucs2_polish_ci</option><option>ucs2_roman_ci</option><option>ucs2_romanian_ci</option><option>ucs2_sinhala_ci</option><option>ucs2_slovak_ci</option><option>ucs2_slovenian_ci</option><option>ucs2_spanish2_ci</option><option>ucs2_spanish_ci</option><option>ucs2_swedish_ci</option><option>ucs2_turkish_ci</option><option>ucs2_unicode_520_ci</option><option>ucs2_unicode_ci</option><option>ucs2_vietnamese_ci</option></optgroup><optgroup label="ujis"><option>ujis_bin</option><option>ujis_japanese_ci</option></optgroup><optgroup label="utf16"><option>utf16_bin</option><option>utf16_croatian_ci</option><option>utf16_czech_ci</option><option>utf16_danish_ci</option><option>utf16_esperanto_ci</option><option>utf16_estonian_ci</option><option>utf16_general_ci</option><option>utf16_german2_ci</option><option>utf16_hungarian_ci</option><option>utf16_icelandic_ci</option><option>utf16_latvian_ci</option><option>utf16_lithuanian_ci</option><option>utf16_persian_ci</option><option>utf16_polish_ci</option><option>utf16_roman_ci</option><option>utf16_romanian_ci</option><option>utf16_sinhala_ci</option><option>utf16_slovak_ci</option><option>utf16_slovenian_ci</option><option>utf16_spanish2_ci</option><option>utf16_spanish_ci</option><option>utf16_swedish_ci</option><option>utf16_turkish_ci</option><option>utf16_unicode_520_ci</option><option>utf16_unicode_ci</option><option>utf16_vietnamese_ci</option></optgroup><optgroup label="utf16le"><option>utf16le_bin</option><option>utf16le_general_ci</option></optgroup><optgroup label="utf32"><option>utf32_bin</option><option>utf32_croatian_ci</option><option>utf32_czech_ci</option><option>utf32_danish_ci</option><option>utf32_esperanto_ci</option><option>utf32_estonian_ci</option><option>utf32_general_ci</option><option>utf32_german2_ci</option><option>utf32_hungarian_ci</option><option>utf32_icelandic_ci</option><option>utf32_latvian_ci</option><option>utf32_lithuanian_ci</option><option>utf32_persian_ci</option><option>utf32_polish_ci</option><option>utf32_roman_ci</option><option>utf32_romanian_ci</option><option>utf32_sinhala_ci</option><option>utf32_slovak_ci</option><option>utf32_slovenian_ci</option><option>utf32_spanish2_ci</option><option>utf32_spanish_ci</option><option>utf32_swedish_ci</option><option>utf32_turkish_ci</option><option>utf32_unicode_520_ci</option><option>utf32_unicode_ci</option><option>utf32_vietnamese_ci</option></optgroup><optgroup label="utf8mb3"><option>utf8mb3_bin</option><option>utf8mb3_croatian_ci</option><option>utf8mb3_czech_ci</option><option>utf8mb3_danish_ci</option><option>utf8mb3_esperanto_ci</option><option>utf8mb3_estonian_ci</option><option>utf8mb3_general_ci</option><option>utf8mb3_general_mysql500_ci</option><option>utf8mb3_german2_ci</option><option>utf8mb3_hungarian_ci</option><option>utf8mb3_icelandic_ci</option><option>utf8mb3_latvian_ci</option><option>utf8mb3_lithuanian_ci</option><option>utf8mb3_persian_ci</option><option>utf8mb3_polish_ci</option><option>utf8mb3_roman_ci</option><option>utf8mb3_romanian_ci</option><option>utf8mb3_sinhala_ci</option><option>utf8mb3_slovak_ci</option><option>utf8mb3_slovenian_ci</option><option>utf8mb3_spanish2_ci</option><option>utf8mb3_spanish_ci</option><option>utf8mb3_swedish_ci</option><option>utf8mb3_tolower_ci</option><option>utf8mb3_turkish_ci</option><option>utf8mb3_unicode_520_ci</option><option>utf8mb3_unicode_ci</option><option>utf8mb3_vietnamese_ci</option></optgroup><optgroup label="utf8mb4"><option>utf8mb4_0900_ai_ci</option><option>utf8mb4_0900_as_ci</option><option>utf8mb4_0900_as_cs</option><option>utf8mb4_0900_bin</option><option>utf8mb4_bg_0900_ai_ci</option><option>utf8mb4_bg_0900_as_cs</option><option>utf8mb4_bin</option><option>utf8mb4_bs_0900_ai_ci</option><option>utf8mb4_bs_0900_as_cs</option><option>utf8mb4_croatian_ci</option><option>utf8mb4_cs_0900_ai_ci</option><option>utf8mb4_cs_0900_as_cs</option><option>utf8mb4_czech_ci</option><option>utf8mb4_da_0900_ai_ci</option><option>utf8mb4_da_0900_as_cs</option><option>utf8mb4_danish_ci</option><option>utf8mb4_de_pb_0900_ai_ci</option><option>utf8mb4_de_pb_0900_as_cs</option><option>utf8mb4_eo_0900_ai_ci</option><option>utf8mb4_eo_0900_as_cs</option><option>utf8mb4_es_0900_ai_ci</option><option>utf8mb4_es_0900_as_cs</option><option>utf8mb4_es_trad_0900_ai_ci</option><option>utf8mb4_es_trad_0900_as_cs</option><option>utf8mb4_esperanto_ci</option><option>utf8mb4_estonian_ci</option><option>utf8mb4_et_0900_ai_ci</option><option>utf8mb4_et_0900_as_cs</option><option selected="">utf8mb4_general_ci</option><option>utf8mb4_german2_ci</option><option>utf8mb4_gl_0900_ai_ci</option><option>utf8mb4_gl_0900_as_cs</option><option>utf8mb4_hr_0900_ai_ci</option><option>utf8mb4_hr_0900_as_cs</option><option>utf8mb4_hu_0900_ai_ci</option><option>utf8mb4_hu_0900_as_cs</option><option>utf8mb4_hungarian_ci</option><option>utf8mb4_icelandic_ci</option><option>utf8mb4_is_0900_ai_ci</option><option>utf8mb4_is_0900_as_cs</option><option>utf8mb4_ja_0900_as_cs</option><option>utf8mb4_ja_0900_as_cs_ks</option><option>utf8mb4_la_0900_ai_ci</option><option>utf8mb4_la_0900_as_cs</option><option>utf8mb4_latvian_ci</option><option>utf8mb4_lithuanian_ci</option><option>utf8mb4_lt_0900_ai_ci</option><option>utf8mb4_lt_0900_as_cs</option><option>utf8mb4_lv_0900_ai_ci</option><option>utf8mb4_lv_0900_as_cs</option><option>utf8mb4_mn_cyrl_0900_ai_ci</option><option>utf8mb4_mn_cyrl_0900_as_cs</option><option>utf8mb4_nb_0900_ai_ci</option><option>utf8mb4_nb_0900_as_cs</option><option>utf8mb4_nn_0900_ai_ci</option><option>utf8mb4_nn_0900_as_cs</option><option>utf8mb4_persian_ci</option><option>utf8mb4_pl_0900_ai_ci</option><option>utf8mb4_pl_0900_as_cs</option><option>utf8mb4_polish_ci</option><option>utf8mb4_ro_0900_ai_ci</option><option>utf8mb4_ro_0900_as_cs</option><option>utf8mb4_roman_ci</option><option>utf8mb4_romanian_ci</option><option>utf8mb4_ru_0900_ai_ci</option><option>utf8mb4_ru_0900_as_cs</option><option>utf8mb4_sinhala_ci</option><option>utf8mb4_sk_0900_ai_ci</option><option>utf8mb4_sk_0900_as_cs</option><option>utf8mb4_sl_0900_ai_ci</option><option>utf8mb4_sl_0900_as_cs</option><option>utf8mb4_slovak_ci</option><option>utf8mb4_slovenian_ci</option><option>utf8mb4_spanish2_ci</option><option>utf8mb4_spanish_ci</option><option>utf8mb4_sr_latn_0900_ai_ci</option><option>utf8mb4_sr_latn_0900_as_cs</option><option>utf8mb4_sv_0900_ai_ci</option><option>utf8mb4_sv_0900_as_cs</option><option>utf8mb4_swedish_ci</option><option>utf8mb4_tr_0900_ai_ci</option><option>utf8mb4_tr_0900_as_cs</option><option>utf8mb4_turkish_ci</option><option>utf8mb4_unicode_520_ci</option><option>utf8mb4_unicode_ci</option><option>utf8mb4_vi_0900_ai_ci</option><option>utf8mb4_vi_0900_as_cs</option><option>utf8mb4_vietnamese_ci</option><option>utf8mb4_zh_0900_as_cs</option></optgroup></select>
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
            </div>
            
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
                            <select class="form-select" v-model="field.type" :name="'fields[' + index + '][type]'" @change="typeSelect">
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
                            <input type="number" class="form-control" v-model="field.length" :name="'fields[' + index + '][length]'">
                        </div>


                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Модификаторы</label>
                            <select class="form-select" name="methods">
                                <option value="after">after</option>
                                <option value="after">change</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Значение модификатора</label>
                            <input class="form-control" type="text">
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">Методы</label>
                            <select class="form-select" name="methods">
                                <option value="after">after</option>
                            </select>
                        </div>
                    </div>

                    

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
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="field.primary" :name="'fields[' + index + '][primary]'" id="'primary-'+index" value="1">
                                <label class="form-check-label" :for="'primary-'+index">
                                    Primary Key
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Значение по умолчанию</label>
                            <input type="text" class="form-control" v-model="field.default" :name="'fields[' + index + '][default]'">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-danger" @click="removeField(index)">Удалить</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Создать миграцию</button>
        </form>
    </div>
    <script>
        new Vue({
            el: '#app',
            data: {
                fields: [
                    { name: '', type: 'id', length: null, nullable: false, unsigned: false, auto_increment: false, primary: false, default: '' }
                ]
            },
            methods: {
                addField() {
                    this.fields.push({ name: '', type: 'id', length: null, nullable: false, unsigned: false, auto_increment: false, primary: false, default: '' });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                },
                typeSelect(e) {
                    // console.log(e.target.value);
                    document.querySelector('.field-name').disabled = false;
                    if (e.target.value == 'id') {
                        document.querySelector('.field-name').disabled = true;
                    }
                }
            }
        });
    </script>
</body>
</html>