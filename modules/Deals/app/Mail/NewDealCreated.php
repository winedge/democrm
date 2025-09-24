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

namespace Modules\Deals\Mail;

use Modules\Core\MailableTemplate\DefaultMailable;
use Modules\Core\MailableTemplate\MailableTemplate;
use Modules\Deals\Models\Deal;
use Modules\Users\Models\User;

class NewDealCreated extends MailableTemplate
{
    /**
     * Create a new mailable instance.
     */
    public function __construct(protected Deal $deal, protected User $creator) {}

    /**
     * Provides the mail template default configuration.
     */
    public static function default(): DefaultMailable
    {
        return new DefaultMailable(static::defaultHtmlTemplate(), static::defaultSubject());
    }

    /**
     * Provides the mail template default HTML message.
     */
    public static function defaultHtmlTemplate(): string
    {
        return '<p>Hi,</p>
                <p>{{ creator }} has created a new deal: <strong>{{ name }}</strong></p>
                <p><strong>Deal Details:</strong></p>
                <ul>
                    <li><strong>Amount:</strong> {{ amount }}</li>
                    <li><strong>Expected Close Date:</strong> {{ expected_close_date }}</li>
                    <li><strong>Pipeline:</strong> {{ pipeline }}</li>
                    <li><strong>Stage:</strong> {{ stage }}</li>
                </ul>
                <p><a href="{{ url }}">View Deal</a></p>';
    }

    /**
     * Provide the mailable template identifier.
     */
    public function template(): string
    {
        return 'deals::emails.new-deal-created';
    }

    /**
     * Provide the mailable template default subject.
     */
    public static function defaultSubject(): string
    {
        return __('deals::deal.mail.new_deal_created_subject', [
            'name' => '{{ name }}',
        ]);
    }

    /**
     * Provide the mailable template default content.
     */
    public function defaultContent(): string
    {
        return __('deals::deal.mail.new_deal_created_content', [
            'creator' => $this->creator->name,
            'name' => $this->deal->name,
            'amount' => $this->deal->amount ? money($this->deal->amount, $this->deal->currency)->format() : __('core::app.not_specified'),
            'expected_close_date' => $this->deal->expected_close_date ? $this->deal->expected_close_date->format('M d, Y') : __('core::app.not_specified'),
            'pipeline' => $this->deal->pipeline->name,
            'stage' => $this->deal->stage->name,
            'url' => $this->deal->resource()->viewRouteFor($this->deal),
        ]);
    }

    /**
     * Provide the mailable template default content.
     */
    public function defaultHtmlContent(): string
    {
        return __('deals::deal.mail.new_deal_created_html_content', [
            'creator' => $this->creator->name,
            'name' => $this->deal->name,
            'amount' => $this->deal->amount ? money($this->deal->amount, $this->deal->currency)->format() : __('core::app.not_specified'),
            'expected_close_date' => $this->deal->expected_close_date ? $this->deal->expected_close_date->format('M d, Y') : __('core::app.not_specified'),
            'pipeline' => $this->deal->pipeline->name,
            'stage' => $this->deal->stage->name,
            'url' => $this->deal->resource()->viewRouteFor($this->deal),
        ]);
    }

    /**
     * Provide the mailable template default content.
     */
    public function defaultTextContent(): string
    {
        return __('deals::deal.mail.new_deal_created_text_content', [
            'creator' => $this->creator->name,
            'name' => $this->deal->name,
            'amount' => $this->deal->amount ? money($this->deal->amount, $this->deal->currency)->format() : __('core::app.not_specified'),
            'expected_close_date' => $this->deal->expected_close_date ? $this->deal->expected_close_date->format('M d, Y') : __('core::app.not_specified'),
            'pipeline' => $this->deal->pipeline->name,
            'stage' => $this->deal->stage->name,
            'url' => $this->deal->resource()->viewRouteFor($this->deal),
        ]);
    }
}
