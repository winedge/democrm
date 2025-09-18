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

use Modules\MailClient\Client\AbstractAttachment;

class Attachment extends AbstractAttachment
{
    /**
     * Get attachment content id
     *
     * @return string|null
     */
    public function getContentId()
    {
        return $this->getEntity()->getContentId();
    }

    /**
     * Get the attachment file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getEntity()->getName();
    }

    /**
     * Get the attachment content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getEntity()->getContentBytes();
    }

    /**
     * Get the attachment content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->getEntity()->getContentType();
    }

    /**
     * Get the attachment encoding
     *
     * All Microsoft attachments encoding are base64
     *
     * @return string
     */
    public function getEncoding()
    {
        return 'base64';
    }

    /**
     * Check whether the attachment is inline
     *
     * @return bool
     */
    public function isInline()
    {
        return $this->getEntity()->getIsInline();
    }

    /**
     * Get the attachment size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->getEntity()->getSize();
    }

    /**
     * Check whether the attachment is embedded message
     *
     * @return bool
     */
    public function isEmbeddedMessage()
    {
        return $this->getEntity()->getContentType() == 'message/rfc822';
    }
}
