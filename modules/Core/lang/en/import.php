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
    'import' => 'Import',
    'start' => 'Start Import',
    'import_records' => 'Import Records',
    'import_in_progress' => 'Import In Progress',
    'download_sample' => 'Download Sample',
    'history' => 'Import History',
    'no_history' => 'No import history found.',
    'spreadsheet_columns' => 'Spreadsheet columns',
    'column_will_not_import' => 'will not be imported',
    'records_being_imported_in_batches' => 'The records are being imported in a few batches, do not navigate from this window until this message dissapear.',
    'import_info' => 'Import records from a CSV file, auto map columns to fields, and reduce manual data entry.',
    'date' => 'Date',
    'file_name' => 'File name',
    'user' => 'User',
    'total_imported' => 'Imported',
    'total_duplicates' => 'Duplicates',
    'total_skipped' => 'Skipped',
    'progress' => 'Progress',
    'status' => 'Status',
    'imported' => 'Records successfully imported',

    'revert' => 'Revert',
    'revert_info' => 'Reverting an import will permanently delete all of the imported records.',
    'why_skipped' => 'Why?',
    'download_skip_file' => 'Download skip file',
    'skip_file' => 'Skip file',
    'total_rows_skipped' => 'Total rows skipped: :count',
    'skip_file_generation_info' => 'A skip file is generated every time a spreadsheet row validation fails.',
    'skip_file_fix_and_continue' => 'Download the skip file to examine the failed rows and why they failed, after the rows are fixed, upload the fixed skip file below to continue the import process for the current import instance.',
    'upload_fixed_skip_file' => 'Upload fixed skip file',

    'steps' => [
        'step_1' => [
            'name' => 'Download Sample',
            'description' => 'Spreadsheet formatting guide.',
        ],
        'step_2' => [
            'name' => 'Upload Spreadsheet',
            'description' => 'Upload file for mapping.',
        ],
        'step_3' => [
            'name' => 'Map Columns',
            'description' => 'Map columns with fields.',
        ],
        'step_4' => [
            'name' => 'Import',
            'description' => 'Start the import process.',
        ],
    ],

    'from_file' => 'Import From :file_type File',
];
