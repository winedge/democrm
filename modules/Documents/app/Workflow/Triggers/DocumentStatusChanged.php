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

namespace Modules\Documents\Workflow\Triggers;

use Modules\Core\Contracts\Workflow\FieldChangeTrigger;
use Modules\Core\Contracts\Workflow\ModelTrigger;
use Modules\Core\Fields\Select;
use Modules\Core\Workflow\Actions\WebhookAction;
use Modules\Core\Workflow\Trigger;
use Modules\Deals\Workflow\Actions\MarkAssociatedDealsAsLost;
use Modules\Deals\Workflow\Actions\MarkAssociatedDealsAsWon;
use Modules\Documents\Enums\DocumentStatus;
use Modules\MailClient\Workflow\Actions\ResourcesSendEmailToField;
use Modules\MailClient\Workflow\Actions\SendEmailAction;

class DocumentStatusChanged extends Trigger implements FieldChangeTrigger, ModelTrigger
{
    /**
     * Trigger name
     */
    public static function name(): string
    {
        return __('documents::document.workflows.triggers.status_changed');
    }

    /**
     * The trigger related model
     */
    public static function model(): string
    {
        return \Modules\Documents\Models\Document::class;
    }

    /**
     * The field to track changes on
     */
    public static function field(): string
    {
        return 'status';
    }

    /**
     * Provide the change values the user to choose from
     *
     * @return \Modules\Core\Fields\Select
     */
    public static function changeField()
    {
        return Select::make('document_status')->options([
            DocumentStatus::DRAFT->name => DocumentStatus::DRAFT->displayName(),
            DocumentStatus::SENT->name => DocumentStatus::SENT->displayName(),
            DocumentStatus::ACCEPTED->name => DocumentStatus::ACCEPTED->displayName(),
            DocumentStatus::LOST->name => DocumentStatus::LOST->displayName(),
        ]);
    }

    /**
     * Trigger available actions
     */
    public function actions(): array
    {
        return [
            (new SendEmailAction)->toResources(ResourcesSendEmailToField::make()->options([
                'contacts' => [
                    'label' => __('documents::document.workflows.actions.fields.email_to_contact'),
                    'resource' => 'contacts',
                ],
                'companies' => [
                    'label' => __('documents::document.workflows.actions.fields.email_to_company'),
                    'resource' => 'companies',
                ],
                'user' => [
                    'label' => __('documents::document.workflows.actions.fields.email_to_owner_email'),
                    'resource' => 'users',
                ],
                'creator' => [
                    'label' => __('documents::document.workflows.actions.fields.email_to_creator_email'),
                    'resource' => 'users',
                ],
            ])),
            new WebhookAction,
            new MarkAssociatedDealsAsWon('documents'),
            new MarkAssociatedDealsAsLost('documents'),
        ];
    }
}
