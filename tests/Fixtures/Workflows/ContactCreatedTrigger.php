<?php

namespace Tests\Fixtures\Workflows;

use Modules\Contacts\Models\Contact;
use Modules\Core\Contracts\Workflow\EventTrigger;
use Modules\Core\Contracts\Workflow\ModelTrigger;
use Modules\Core\Workflow\Trigger;

class ContactCreatedTrigger extends Trigger implements EventTrigger, ModelTrigger
{
    /**
     * Trigger name
     */
    public static function name(): string
    {
        return 'Contact created';
    }

    /**
     * The trigger related model
     */
    public static function model(): string
    {
        return Contact::class;
    }

    /**
     * The model event trigger
     */
    public static function event(): string
    {
        return 'created';
    }

    /**
     * Trigger available actions
     *
     * @return string
     */
    public function actions(): array
    {
        return [
            new CreateDealAction,
            new CreateActivityAction,
        ];
    }
}
