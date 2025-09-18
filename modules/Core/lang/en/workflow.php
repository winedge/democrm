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
    'create' => 'Create Workflow',
    'workflows' => 'Workflows',
    'title' => 'Title',
    'description' => 'Description',
    'created' => 'Workflow successfully created.',
    'updated' => 'Workflow successfully updated.',
    'deleted' => 'Workflow successfully deleted',
    'when' => 'When',
    'then' => 'Then',
    'field_change_to' => 'To',
    'total_executions' => 'Executions: :total',
    'info' => 'The workflows tool automates your sales processes. Internal processes that can be automated include creating activities, sending emails, triggering HTTP requests, etc.',

    'validation' => [
        'invalid_webhook_url' => 'The webhook URL must not start with "https://" or "http://"',
    ],

    'actions' => [
        'webhook' => 'Trigger Webhook',
        'webhook_url_info' => 'Must be a full, valid, publicly accessible URL.',
    ],

    'fields' => [

        'with_header_name' => 'With header name (optional)',
        'with_header_value' => 'With header value (optional)',
        'for_owner' => 'For: Owner (Responsible person)',

        'dates' => [
            'now' => 'With due date: at the moment',
            'in_1_day' => 'With due date: in one day',
            'in_2_days' => 'With due date: in two days',
            'in_3_days' => 'With due date: in three days',
            'in_4_days' => 'With due date: in four days',
            'in_5_days' => 'With due date: in five days',
            'in_1_week' => 'With due date: in 1 week',
            'in_2_weeks' => 'With due date: in 2 weeks',
            'in_1_month' => 'With due date: in 1 month',
        ],
    ],
];
