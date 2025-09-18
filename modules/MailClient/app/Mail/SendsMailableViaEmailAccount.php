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

namespace Modules\MailClient\Mail;

use Illuminate\Support\Facades\Log;
use Modules\MailClient\Client\Client;
use Modules\MailClient\Client\Exceptions\ConnectionErrorException;
use Modules\MailClient\Client\SendsMailForMailable;
use Modules\MailClient\Models\EmailAccount;

trait SendsMailableViaEmailAccount
{
    use SendsMailForMailable;

    /**
     * Provide the email account id.
     */
    abstract protected function emailAccountId(): ?int;

    /**
     * Get the client instance that should be used to send the mailable.
     */
    protected function getClient(): ?Client
    {
        if (! $accountId = $this->emailAccountId()) {
            Log::debug(
                sprintf('Skipping send of "%s" mailable template because an email account hasn\'t been selected.', static::class)
            );

            return null;
        }

        $account = EmailAccount::find($accountId);

        // We will check if the email account requires authentication, as we
        // are not able to send mails if the account requires authentication
        // the template will fallback to the Laravel default mailer behavior
        if (! $account->canSendEmail()) {
            Log::debug(
                sprintf(
                    'Couldn\'t send the "%s" mailable template because there was a problem with the selected email account.', static::class
                )
            );

            return null;
        }

        $client = $account->getClient();

        if ($fromName = $this->accountFromName()) {
            $client->setFromName($fromName);
        }

        return $client;
    }

    /**
     * Get custom account from name text.
     */
    protected function accountFromName(): ?string
    {
        return null;
    }

    /**
     * Handle connection error exception.
     */
    protected function onConnectionError(ConnectionErrorException $e): void
    {
        EmailAccount::find($this->emailAccountId())->setAuthRequired();
    }
}
