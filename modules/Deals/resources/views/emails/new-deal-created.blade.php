<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #333;">{{ __('deals::deal.mail.new_deal_created_subject', ['name' => $deal->name]) }}</h2>
        
        <p>{{ __('deals::deal.mail.new_deal_created_greeting', ['creator' => $creator->name]) }}</p>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #495057;">{{ __('deals::deal.mail.deal_details') }}</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.name') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.amount') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->amount ? money($deal->amount, $deal->currency)->format() : __('core::app.not_specified') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.expected_close_date') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : __('core::app.not_specified') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.pipeline.name') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->pipeline->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.stage.name') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->stage->name }}</td>
                </tr>
                @if($deal->user)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6c757d;">{{ __('deals::fields.deals.user.name') }}:</td>
                    <td style="padding: 8px 0;">{{ $deal->user->name }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $deal->resource()->viewRouteFor($deal) }}" 
               style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">
                {{ __('deals::deal.mail.view_deal') }}
            </a>
        </div>
        
        <p style="color: #6c757d; font-size: 14px;">
            {{ __('deals::deal.mail.created_by', ['creator' => $creator->name, 'date' => $deal->created_at->format('M d, Y \a\t H:i')]) }}
        </p>
    </div>
</body>
</html>

