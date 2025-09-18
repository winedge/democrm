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

namespace Modules\MailClient\Client\Outlook;

use GuzzleHttp\Exception\ClientException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Microsoft\Graph\Model\MailFolder;
use Microsoft\Graph\Model\Message as MessageModel;
use Modules\Core\Facades\MsGraph as Api;
use Modules\MailClient\Client\AbstractFolder;
use Modules\MailClient\Client\Exceptions\ConnectionErrorException;
use Modules\MailClient\Client\Exceptions\MessageNotFoundException;
use Modules\MailClient\Client\FolderType;
use Modules\MailClient\Client\MasksMessages;

class Folder extends AbstractFolder
{
    use MasksFolders,
        MasksMessages,
        ProvidesMessageUri;

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
     * @return \Modules\MailClient\Client\Outlook\Message
     *
     * @throws \Modules\MailClient\Client\Exceptions\MessageNotFoundException
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getMessage($uid)
    {
        try {
            return $this->maskMessage(Api::createGetRequest($this->getMessageUri($uid))
                ->setReturnType(MessageModel::class)
                ->execute(), Message::class);
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                throw new MessageNotFoundException;
            }

            throw $e;
        } catch (IdentityProviderException $e) {
            throw new ConnectionErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get messages
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getMessages()
    {
        try {
            $iterator = Api::createCollectionGetRequest(
                $this->getFolderMessagesUri($this->getId())
            )->setReturnType(MessageModel::class);

            return $this->maskMessages(Api::iterateCollectionRequest($iterator), Message::class);
        } catch (IdentityProviderException $e) {
            throw new ConnectionErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * https://docs.microsoft.com/en-us/graph/delta-query-messages
     *
     * @param  null|string  $deltaToken
     * @param  null|string  $startFrom  Get messages starting from specific date and time
     * @return \Microsoft\Graph\Http\GraphCollectionRequest
     */
    public function createDeltaOrGetMessagesRequest($deltaLink = null, $startFrom = null)
    {
        $endpoint = $deltaLink ?? $this->getMessagesDeltaUri($this->getId());

        if (! $deltaLink && $startFrom) {
            /**
             * @link https://docs.microsoft.com/en-us/graph/query-parameters#filter-parameter
             *
             * The only supported $filter expresssions are $filter=receivedDateTime+ge+{value}
             * or $filter=receivedDateTime+gt+{value}.
             * @link https://docs.microsoft.com/en-us/graph/api/message-delta?view=graph-rest-1.0&tabs=http#odata-query-parameters
             */
            $startFrom = (new \DateTime($startFrom))->format('Y-m-d\TH:i:s\Z');
            // $endpoint .= '&' . urlencode("\$filter=receivedDateTime ge '$startFrom'");
            $endpoint .= '&$filter=receivedDateTime ge '.$startFrom;
        }

        return Api::createCollectionGetRequest($endpoint)->setReturnType(MessageModel::class);
    }

    /**
     * Get the folder system name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getDisplayName();
    }

    /**
     * Get the folder display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getEntity()->getDisplayName();
    }

    /**
     * Get the folder well known name property
     *
     * @return string
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getWellKnownName()
    {
        return $this->getEntity()->getProperties()['wellKnownName'] ?? null;
    }

    /**
     * Check whether the folder has child folders
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->getEntity()->getChildFolderCount() > 0;
    }

    /**
     * Get folder children.
     *
     * @return array
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    public function getChildren()
    {
        if ($this->children) {
            return $this->children;
        }

        if ($this->hasChildren()) {
            // The folders relations are not deep loaded, in this case
            // the folder has children but they are not loaded,
            // we need to fetch the children for the folder separately
            // Also when initially fetching the folders
            // The childrens are array, we need to mask them
            if ($children = $this->getEntity()->getChildFolders()) {
                $children = array_map(function ($child) {
                    return new MailFolder($child);
                }, $children);
            } else {
                $children = $this->fetchFolderChildren($this->getId());
            }

            return $this->children = $this->maskFolders($children);
        }

        // No children, set the property as array to prevent fetching the next
        // time when this method is accessed
        return $this->children = [];
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
     * Get the folder type
     *
     * @return string|null
     */
    public function getType()
    {
        $type = parent::getType();
        $wellKnownName = $this->getWellKnownName();

        $map = FolderType::KNOWN_FOLDER_NAME_MAP;

        if (! $type && array_key_exists($wellKnownName, $map)) {
            return $map[$wellKnownName];
        }

        // null
        return $type;
    }

    /**
     * Fetch the folder children
     *
     * @return array
     *
     * @throws \Modules\MailClient\Client\Exceptions\ConnectionErrorException
     */
    protected function fetchFolderChildren()
    {
        try {
            $iterator = Api::createCollectionGetRequest(
                "/me/mailFolders/{$this->getId()}/childFolders"
            )->setReturnType(MailFolder::class);

            return Api::iterateCollectionRequest($iterator);
        } catch (IdentityProviderException $e) {
            throw new ConnectionErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
