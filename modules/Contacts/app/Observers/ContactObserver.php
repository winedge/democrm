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

namespace Modules\Contacts\Observers;

use Modules\Activities\Models\Guest;
use Modules\Contacts\Models\Contact;
use Modules\Contacts\Notifications\NewLeadCreated;
use Modules\Users\Models\User;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     */
    public function created(Contact $contact): void
    {
        $this->sendNewLeadNotifications($contact);
    }

    /**
     * Handle the Contact "deleting" event.
     */
    public function deleting(Contact $contact): void
    {
        if ($contact->isForceDeleting()) {
            $contact->purge();
        } else {
            $contact->guests()->delete();
        }
    }

    /**
     * Handle the Contact "restored" event.
     */
    public function restored(Contact $contact)
    {
        $contact->guests()->onlyTrashed()->get()->each(function (Guest $guest) {
            $guest->restore();
        });
    }

    /**
     * Send notifications for new lead creation.
     */
    protected function sendNewLeadNotifications(Contact $contact): void
    {
        $creator = $contact->creator;
        
        if (!$creator) {
            return;
        }

        // Get all users who should be notified
        $usersToNotify = $this->getUsersToNotify($contact);

        foreach ($usersToNotify as $user) {
            // Send notification to all users including the creator for testing
            // Remove this condition if you want to notify the creator as well
            // if ($user->id === $creator->id) {
            //     continue;
            // }

            $user->notify(new NewLeadCreated($contact, $creator));
        }
    }

    /**
     * Get users who should be notified about new leads.
     */
    protected function getUsersToNotify(Contact $contact): \Illuminate\Database\Eloquent\Collection
    {
        // Start with an empty Eloquent Collection
        $users = User::whereRaw('1 = 0')->get(); // Empty collection with correct type

        // Add the contact owner if exists
        if ($contact->user) {
            $users = $users->merge(collect([$contact->user]));
        }

        // Add all super admins
        $superAdmins = User::where('super_admin', true)->get();
        $users = $users->merge($superAdmins);

        // Add users with 'edit all contacts' permission
        $usersWithPermission = User::whereHas('permissions', function ($query) {
            $query->where('name', 'edit all contacts');
        })->get();
        
        $users = $users->merge($usersWithPermission);

        // Add the creator to ensure they get notified for testing
        if ($contact->creator) {
            $users = $users->merge(collect([$contact->creator]));
        }

        // Return unique users as Eloquent Collection
        return $users->unique('id')->values();
    }
}
