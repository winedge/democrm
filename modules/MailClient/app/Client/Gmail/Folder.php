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

namespace Modules\MailClient\Client\Gmail;

use Google\Service\Exception as GoogleServiceException;
use Modules\Core\Facades\Google as Client;
use Modules\MailClient\Client\AbstractFolder;
use Modules\MailClient\Client\Exceptions\ConnectionErrorException;
use Modules\MailClient\Client\Exceptions\MessageNotFoundException;

class Folder extends AbstractFolder
{
    /**
     * Gmail Folder Delimiter
     */
    const DELIMITER = '/';

    /**
     * Get the folder unique identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->getEntity()->getId();
    }

    /**
     * Get folder message
     *
     * @param  string  $uid
     * @return \Modules\MailClient\Client\Gmail\Message
     *
     * @throws \Modules\MailClient\Client\Exceptions\MessageNotFoundException
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getMessage($uid)
    {
        try {
            $message = Client::message()
                ->withLabels($this->getId())
                ->get($uid);

            return new Message($message);
        } catch (GoogleServiceException $e) {
            if ($e->getCode() === 404) {
                throw new MessageNotFoundException($e->getMessage(), $e->getCode(), $e);
            } elseif ($e->getCode() == 401) {
                throw new ConnectionErrorException($e->getMessage(), $e->getCode(), $e);
            }

            throw $e;
        }
    }

    /**
     * Get messages in the folder
     *
     * @param  int  $limit
     * @return \Illuminate\Support\Collection&\Modules\Core\Common\Google\Services\MessageCollection
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getMessages($limit = 50)
    {
        try {
            return Client::message()
                ->withLabels($this->getId())
                ->preload(Message::class)
                ->take($limit)
                ->all();
        } catch (GoogleServiceException $e) {
            if ($e->getCode() == 401) {
                throw new ConnectionErrorException;
            }

            throw $e;
        }
    }

    /**
     * Get messages starting from specific date and time
     *
     * @param  string  $dateTime
     * @param  int  $limit
     * @return \Illuminate\Support\Collection&\Modules\Core\Common\Google\Services\MessageCollection
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getMessagesFrom($dateTime, $limit = 50)
    {
        try {
            return Client::message()
                ->withLabels($this->getId())
                ->preload(Message::class)
                ->after(strtotime($dateTime))
                ->take($limit)
                ->all();
        } catch (GoogleServiceException $e) {
            if ($e->getCode() == 401) {
                throw new ConnectionErrorException($e->getMessage(), $e->getCode(), $e);
            }

            throw $e;
        }
    }

    /**
     * Get the folder system name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getEntity()->getName();
    }

    /**
     * Get the folder display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return last(explode(self::DELIMITER, $this->getName()));
    }

    /**
     * Check whether the folder is selectable
     *
     * @return bool
     */
    public function isSelectable()
    {
        return true;
    }

    /**
     * Check whether a message can be moved to this folder
     *
     * @return bool
     */
    public function supportMove()
    {
        return ! $this->isDraft() && ! $this->isSent();
    }
}
