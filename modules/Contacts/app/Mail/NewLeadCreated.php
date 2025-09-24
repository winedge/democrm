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

namespace Modules\Contacts\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Contacts\Models\Contact;
use Modules\Contacts\Resources\Contact as ResourceContact;
use Modules\Core\Common\Placeholders\ActionButtonPlaceholder;
use Modules\Core\Common\Placeholders\PrivacyPolicyPlaceholder;
use Modules\Core\MailableTemplate\DefaultMailable;
use Modules\Core\Resource\ResourcePlaceholders;
use Modules\MailClient\Mail\MailableTemplate;
use Modules\Users\Models\User;
use Modules\Users\Placeholders\UserPlaceholder;

class NewLeadCreated extends MailableTemplate implements ShouldQueue
{
    /**
     * Create a new mailable template instance.
     */
    public function __construct(protected Contact $contact, protected User $creator) {}

    /**
     * Provide the defined mailable template placeholders.
     */
    public function placeholders(): ResourcePlaceholders
    {
        return ResourcePlaceholders::make(new ResourceContact, $this->contact ?? null)
            ->push([
                ActionButtonPlaceholder::make(fn () => $this->contact),
                PrivacyPolicyPlaceholder::make(),
                UserPlaceholder::make(fn () => $this->creator->name, 'creator')
                    ->description(__('contacts::contact.mail_placeholders.creator')),
            ])
            ->withUrlPlaceholder();
    }

    /**
     * Provides the mail template default configuration.
     */
    public static function default(): DefaultMailable
    {
        return new DefaultMailable(static::defaultHtmlTemplate(), static::defaultSubject());
    }

    /**
     * Provides the mail template default message.
     */
    public static function defaultHtmlTemplate(): string
    {
        return '<p>Hello {{ contact.user }}<br /></p>
                <p>A new lead {{ contact.first_name }} has been created by {{ creator }}<br /></p>
                <p><strong>Lead Details:</strong><br />
                Name: {{ contact.first_name }} {{ contact.last_name }}<br />
                Email: {{ contact.email }}<br />
                Source: {{ contact.source.name }}<br />
                Status: {{ contact.lead_status.label }}<br /></p>
                <p>{{#action_button}}View Lead{{/action_button}}</p>';
    }

    /**
     * Provides the mail template default subject.
     */
    public static function defaultSubject(): string
    {
        return 'New Lead: {{ contact.first_name }} {{ contact.last_name }}';
    }
}
