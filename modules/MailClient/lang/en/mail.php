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

return [
    'compose' => 'Compose',
    'attachments' => 'Attachments',
    'emails' => 'Emails',
    'create' => 'Create Email',
    'send' => 'Send Email',
    'view' => 'View Mail',
    'from_header' => 'From Header',
    'from_name' => 'From Name',

    'messages' => 'Email Messages',
    'message' => 'Email Message',

    'new_message_placeholder' => 'Compose your email here. Type ":trigger" to search dynamic fields.',

    'signature' => 'Email Signature',
    'signature_info' => 'Create signature that will be added to the bottom of your messages.',
    'show_quoted_content' => 'Show quoted content',
    'trimmed_content' => 'Trimmed Content',
    'disable_sync' => 'Disable Sync',
    'manage_emails' => 'Manage Emails',
    'info' => 'You can send and reply to emails directly via this section.',
    'from_header_info' => 'What people will see in the "from address" when they receive an email from this email address.',
    'placeholders_info' => 'Use :placeholders placeholders to dynamically replace the content like company name, agent name (user who sends the email).',
    'mark_as_read' => 'Mark as read',
    'mark_as_unread' => 'Mark as unread',
    'message_queued_for_sending' => 'The message has been queued for sending, will be synchronized on the next sync batch.',
    'initial_sync_info' => 'This account is queued for initial sync and the synchronization will be performed as soon as the cron job runs, to prevent any synchronization interruptions, make sure the you have configured the cron job as explained in the documentation.',

    'account' => [
        'create_contact' => 'Create Contact record if record does not exists.',
        'use_aliass' => 'Use alias email address',
        'use_aliass_info' => 'The address needs to be a valid alias that already exists in the mail server, useful when using Google Workspace aliases.',
        'enter_alias' => 'Alias E-Mail Address',
        'personal' => 'Personal',
        'shared' => 'Shared',
        'accounts' => 'Email Accounts',
        'sync_emails_from' => 'Sync emails from',
        'sync_period_now' => 'Now',
        'sync_period_1_month_ago' => '1 month ago',
        'sync_period_3_months_ago' => '3 months ago',
        'sync_period_6_months_ago' => '6 months ago',
        'sync_period_note' => 'In most cases you won\'t need and interact with all the emails from :date, lower starting sync period will avoid importing hundreds of emails with attachments and helps save storage space, choosing a lower starting sync period is always recommended.',
        'integration_not_configured' => 'There are no email accounts configured, configure personal or shared email accounts in order to send emails.',
        'already_connected' => 'This email account is already connected.',
        'select_type' => 'Select account type',
        'no_active_folders' => 'This account has no active folders. Enable active folders by editing the mail account, the active folders will be the folders that will be synchronized to the application.',
        'activate_folders' => 'Activate Folders',
        'active_folders' => 'Active folders',
        'active_folders_info' => 'Select the folders you wish to synchronize.',
        'sent_folder' => 'Sent Folder',
        'trash_folder' => 'Trash Folder',
        'test_connection' => 'Test Connection',
        'is_primary' => 'Primary Account',
        'connection_error' => 'Connection test error, please check your configuration, refer to the error for more information: :message',
        'create' => 'Create Email Account',
        'edit' => 'Edit Email Account',
        'manage' => 'Manage Accounts',
        'connect' => 'Connect Account',
        'connect_shared' => 'Connect Shared Account',
        'connect_personal' => 'Connect Personal Account',
        'created' => 'Email account successfully added.',
        'updated' => 'Email account successfully updated.',
        'deleted' => 'Email account successfully deleted',
        'no_accounts_configured' => 'No email accounts configured',
        'no_accounts_configured_info' => 'Connect an account to start sending and organize emails in order close deals faster',
        'create_shared_info' => 'Connect a company email account such as contact@company.com or sales@company.com',
        'create_shared_confirmation_message' => 'When you connect a shared email account, be aware that shared email accounts can be accessed by all of the team members who have been granted "access to shared inbox". <br /> <br />
        This means that each team member will be able to view and interact with the mails.',

        'missing_sent_folder' => 'Action required, select the sent folder for this account.',
        'missing_trash_folder' => 'Action required, select the trash folder for this account.',
        'missing_primary_account' => 'Action required, configure a primary account for sending emails.',

        'type' => 'Account Type',
        'email_address' => 'Email Address',
        'password' => 'Password',
        'username' => 'Username',
        'incoming_mail' => 'Incoming Mail (IMAP)',
        'outgoing_mail' => 'Outgoing Mail (SMTP)',
        'server' => 'Server',
        'port' => 'Port',
        'allow_non_secure_certificate' => 'Allow non-secure certificate',
        'encryption' => 'Encryption',
        'without_encryption' => 'Without Encryption',

        'delete_warning' => 'If you are using this email account as "System Email Account" in settings, you will need to select another account for sending system related mails, additionally, you will need to check your workflows, if any workfow is configured to use the "Send Email" action with this particular email account, you will need to update the action mail account in order the workflow to continue sending mails.',

        'featured' => [
            'sync' => '2-way email sync with your email provider.',
            'save_time' => 'Save time by making use of predefined templates.',
            'placeholders' => 'Compose emails and templates with placeholders.',
            'signature' => 'Add customized signature for a more professional look.',
            'associations' => 'Associate emails to many :resources and :resource.',
            'types' => 'Connect via IMAP, your Gmail or Outlook account.',
        ],
    ],

    'templates' => [
        'select' => 'Select',
        'create' => 'Create Template',
        'name' => 'Name',
        'subject' => 'Subject',
        'is_shared' => 'Share this template with other team members?',
        'body' => 'Body',
        'templates' => 'Templates',
        'created' => 'Mail template successfully created.',
        'updated' => 'Mail template successfully updated.',
        'deleted' => 'Mail template successfully deleted',
    ],

    'labels' => [
        'CATEGORY_PERSONAL' => 'Personal',
        'CATEGORY_SOCIAL' => 'Social',
        'CATEGORY_FORUMS' => 'Forums',
        'IMPORTANT' => 'Important',
        'CATEGORY_UPDATES' => 'Updates',
        'CATEGORY_PROMOTIONS' => 'Promotions',
        'CHAT' => 'Chat',
        'SENT' => 'Sent',
        'INBOX' => 'Inbox',
        'TRASH' => 'Trash',
        'DRAFT' => 'Draft',
        'DRAFTS' => 'DRAFTS',
        'SPAM' => 'Spam',
        'STARRED' => 'Starred',
        'UNREAD' => 'Unread',
    ],

    'workflows' => [
        'actions' => [
            'send' => 'Send Email',
        ],

        'fields' => [
            'from_account' => 'From email account',
            'subject' => 'With subject',
            'message' => 'With message',
            'to' => 'To',
            'send_from_owner_primary_account' => 'Owner Primary Email Account',
        ],
    ],

    'validation' => [
        'invalid_recipients' => 'It looks like some of your recipients has invalid address.',
    ],
];
