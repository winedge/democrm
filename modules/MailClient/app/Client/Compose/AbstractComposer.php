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

namespace Modules\MailClient\Client\Compose;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use KubAT\PhpSimple\HtmlDomParser;
use Modules\Core\Common\Placeholders\Placeholders;
use Modules\Core\Models\Media;
use Modules\MailClient\Client\Client;
use Modules\MailClient\Client\FolderIdentifier;
use Modules\MailClient\Support\MailTracker;

abstract class AbstractComposer
{
    use ForwardsCalls;

    const SEND = 'send';

    const REPLY = 'reply';

    const FORWARD = 'forward';

    /**
     * Create new AbstractComposer instance.
     */
    public function __construct(protected Client $client, ?FolderIdentifier $sentFolder = null)
    {
        if ($sentFolder) {
            $this->setSentFolder($sentFolder);
        }
    }

    /**
     * Send a new message.
     *
     * @return \Modules\MailClient\Client\Contracts\MessageInterface|null
     */
    abstract public function send();

    /**
     * Set the client sent folder.
     */
    public function setSentFolder(FolderIdentifier $identifier): static
    {
        $this->client->setSentFolder(
            $this->client->getFolders()->find($identifier)
        );

        return $this;
    }

    /**
     * Add trackers to the message body.
     */
    public function withTrackers(): static
    {
        (new MailTracker)->createTrackers($this);

        return $this;
    }

    /**
     * Convert the media images from the given message to base64.
     *
     * @param  string  $message
     * @return string
     */
    protected function convertMediaImagesToBase64($message)
    {
        if (! $message) {
            return $message;
        }

        $dom = HtmlDomParser::str_get_html($message);

        foreach ($dom->find('img') as $image) {
            if (Str::startsWith($image->src, [
                rtrim(url(config('app.url'), '/')).'/media',
                'media',
                '/media',
            ]) && Str::endsWith($image->src, 'preview')) {
                if (preg_match('/[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}/', $image->src, $matches)) {
                    // Find the inline attachment by token via the media model
                    $media = Media::byToken($matches[0])->first();

                    $image->src = 'data:'.$media->mime_type.';base64,'.base64_encode($media->contents());
                }
            }
        }

        return $dom->save();
    }

    /**
     * Get the mail client instance.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Pass dynamic methods onto the client instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return static
     */
    public function __call($method, $parameters)
    {
        if ($method === 'htmlBody') {
            // First we will clean up spaces from the editor and then
            // we will clean up the placeholders input fields when empty
            $parameters[0] = trim(str_replace(
                ['<p><br /></p>', '<p><br/></p>', '<p><br></p>', '<p>&nbsp;</p>'],
                "\n",
                Placeholders::cleanup($parameters[0])
            ));

            // Next, we will convert the media images that are inline from the current server
            // to base64 images so the EmbeddedImagesProcessor can embed them inline
            // If we don't embed the images and use the URL directly and the user decide to
            // change his Concord CRM installation domain, the images won't longer works, for this reason
            // we need to embed them inline like any other email client
            $parameters[0] = $this->convertMediaImagesToBase64($parameters[0]);
        }

        $this->forwardCallTo(
            $this->client,
            $method,
            $parameters
        );

        return $this;
    }
}
