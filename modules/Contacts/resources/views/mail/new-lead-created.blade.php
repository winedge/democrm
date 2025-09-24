<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('contacts::contact.mail.new_lead_subject', ['name' => $contact->full_name]) }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #3B82F6;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e2e8f0;
        }
        .lead-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3B82F6;
        }
        .detail-row {
            display: flex;
            margin: 10px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #64748b;
        }
        .detail-value {
            flex: 1;
        }
        .cta-button {
            display: inline-block;
            background: #3B82F6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('contacts::contact.mail.new_lead_title') }}</h1>
    </div>
    
    <div class="content">
        <p>{{ __('contacts::contact.mail.new_lead_intro', ['creator' => $creator->name]) }}</p>
        
        <div class="lead-details">
            <h3>{{ __('contacts::contact.mail.lead_details') }}</h3>
            
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.first_name') }}:</div>
                <div class="detail-value">{{ $contact->first_name }}</div>
            </div>
            
            @if($contact->last_name)
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.last_name') }}:</div>
                <div class="detail-value">{{ $contact->last_name }}</div>
            </div>
            @endif
            
            @if($contact->email)
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.email') }}:</div>
                <div class="detail-value">{{ $contact->email }}</div>
            </div>
            @endif
            
            @if($contact->source)
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.source.name') }}:</div>
                <div class="detail-value">{{ $contact->source->name }}</div>
            </div>
            @endif
            
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::lead.status.status') }}:</div>
                <div class="detail-value">{{ $contact->lead_status->label() }}</div>
            </div>
            
            @if($contact->user)
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.user.name') }}:</div>
                <div class="detail-value">{{ $contact->user->name }}</div>
            </div>
            @endif
            
            @if($contact->job_title)
            <div class="detail-row">
                <div class="detail-label">{{ __('contacts::fields.contacts.job_title') }}:</div>
                <div class="detail-value">{{ $contact->job_title }}</div>
            </div>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $contactUrl }}" class="cta-button">
                {{ __('contacts::contact.mail.view_lead_button') }}
            </a>
        </div>
        
        <div class="footer">
            <p>{{ __('contacts::contact.mail.footer_text') }}</p>
        </div>
    </div>
</body>
</html>
