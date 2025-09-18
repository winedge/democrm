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

namespace Modules\MailClient\Concerns;

use Exception;
use Modules\MailClient\Client\Contracts\MessageInterface;
use Modules\MailClient\Models\ScheduledEmail;
use Modules\MailClient\Services\EmailAccountMessageSyncService;
use Throwable;

trait SendsScheduledEmail
{
    use InteractsWithEmailMessageAssociations;

    /**
     * The number of minutes to retry failed messages.
     */
    protected int $retryAfter = 60;

    /**
     * Send the message.
     */
    public function send()
    {
        try {
            if (! $this->isSending()) {
                $this->markAsSending();
            }

            if (! $this->account->canSendEmail()) {
                throw new Exception(
                    'Account is unable to send emails: '.$this->account->sync_state_comment
                );
            }

            $remoteMessage = $this->performSend();

            $this->markAsSent();

            if ($remoteMessage) {
                (new EmailAccountMessageSyncService)->create(
                    $this->account,
                    $remoteMessage,
                    $this->associations ?: []
                );
            }
        } catch (Throwable $e) {
            $retries = $this->retries + 1;
            $isFinalRetry = $retries >= ScheduledEmail::$maxRetries;

            $this->markAsFailed($e->getMessage(), [
                'retry_after' => ! $isFinalRetry ? now()->addMinutes($this->retryAfter) : null,
                'retries' => $retries,
                'failed_at' => $isFinalRetry ? now() : null,
            ]);
        }
    }

    /**
     * Send the scheduled message via the mail client.
     */
    protected function performSend(): ?MessageInterface
    {
        $composer = $this->account->createMessageComposer($this->type, $this->related_message_id);

        $this->addComposerAssociationsHeaders($composer, $this->associations ?: []);

        foreach ($this->media as $attachment) {
            $composer->attachFromStorageDisk(
                $attachment->disk,
                $attachment->getDiskPath(),
                $attachment->basename
            );
        }

        return $composer->subject($this->subject)
            ->to($this->to)
            ->bcc($this->bcc)
            ->cc($this->cc)
            ->htmlBody($this->html_body)
            ->withTrackers()
            ->send();
    }
}
