<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

return [
    'import' => 'Импорт',
    'start' => 'Начать импорт',
    'import_records' => 'Импорт записей',
    'download_sample' => 'Скачать пример',
    'history' => 'История импорта',
    'no_history' => 'История импорта не найдена.',
    'spreadsheet_columns' => 'Столбцы электронной таблицы',
    'column_will_not_import' => 'не будет импортировано',
    'date' => 'Дата',
    'file_name' => 'Имя файла',
    'user' => 'Пользователь',
    'total_imported' => 'Импортировано',
    'total_duplicates' => 'Дубликаты',
    'total_skipped' => 'Пропущено',
    'status' => 'Статус',
    'imported' => 'Записи успешно импортированы',
    'why_skipped' => 'Почему?',
    'download_skip_file' => 'Скачать прощенные файлы',
    'skip_file' => 'Пропустить файл',
    'total_rows_skipped' => 'Всего пропущено строк: :count',
    'skip_file_generation_info' => 'Файл пропуска создается каждый раз при сбое проверки строки электронной таблицы.',
    'skip_file_fix_and_continue' => 'Загрузите файл пропуска, чтобы изучить неудачные строки и причины их возникновения. После того, как строки будут исправлены, загрузите файл с фиксированным пропуском ниже, чтобы продолжить процесс импорта для текущего экземпляра импорта.',
    'upload_fixed_skip_file' => 'Загрузить файл с фиксированным пропуском',
    'steps' => [
        'step_1' => [
            'name' => 'Скачать пример',
            'description' => 'Руководство по форматированию электронных таблиц.',
        ],
        'step_2' => [
            'name' => 'Загрузить таблицу',
            'description' => 'Загрузите файл для сопоставления.',
        ],
        'step_3' => [
            'name' => 'Столбцы карты',
            'description' => 'Сопоставьте столбцы с полями.',
        ],
        'step_4' => [
            'name' => 'Импорт',
            'description' => 'Запустите процесс импорта.',
        ],
    ],
    'from_file' => 'Импорт из файла :file_type',
];
