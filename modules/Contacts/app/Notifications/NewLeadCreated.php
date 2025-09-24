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

namespace Modules\Contacts\Notifications;

use Modules\Contacts\Mail\NewLeadCreated as NewLeadCreatedMailable;
use Modules\Contacts\Models\Contact;
use Modules\Core\MailableTemplate\MailableTemplate;
use Modules\Core\Notification;
use Modules\Users\Models\User;

// Remove ShouldQueue to send notifications immediately
class NewLeadCreated extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(protected Contact $contact, protected User $creator) {}

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): NewLeadCreatedMailable&MailableTemplate
    {
        return (new NewLeadCreatedMailable($this->contact, $this->creator))->to($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        // Get the company name if available
        $companyName = 'N/A';
        if ($this->contact->companies && $this->contact->companies->isNotEmpty()) {
            $companyName = $this->contact->companies->first()->name;
        }

        // Get the tort field value (check for custom field with cf_ prefix)
        $tortValue = 'N/A';
        if (isset($this->contact->cf_tort)) {
            $tortValue = $this->contact->cf_tort;
        } elseif (isset($this->contact->tort)) {
            $tortValue = $this->contact->tort;
        }

        return [
            'path' => $this->contact->resource()->viewRouteFor($this->contact),
            'lang' => [
                'key' => 'contacts::contact.notifications.new_lead',
                'attrs' => [
                    'creator' => $this->creator->name,
                    'name' => $this->contact->full_name ?? ($this->contact->first_name . ' ' . $this->contact->last_name),
                    'email' => $this->contact->email ?? 'N/A',
                    'source' => $this->contact->source?->name ?? 'N/A',
                    'status' => $this->contact->lead_status?->label() ?? 'N/A',
                    'tort' => $tortValue,
                    'company' => $companyName,
                ],
            ],
            // Force sound and persistence for all new lead notifications
            'play_sound' => true,
            'persistent' => true,
            'sound_enabled' => true, // Additional flag
            'priority' => 'high', // Mark as high priority
        ];
    }
}
