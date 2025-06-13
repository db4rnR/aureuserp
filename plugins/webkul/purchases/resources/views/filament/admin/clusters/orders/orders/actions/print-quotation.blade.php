<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333333;
            line-height: 1.6;
            margin: 0;
        }

        .quotation {
            margin-bottom: 50px;
            page-break-after: always;
        }

        .quotation:last-child {
            page-break-after: auto;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .company-info {
            width: 50%;
            float: left;
        }

        .vendor-info {
            width: 45%;
            float: right;
            text-align: right;
            border-left: 2px solid #f0f0f0;
            padding-left: 20px;
        }

        .clearfix {
            clear: both;
        }

        .quote-number {
            font-size: 24px;
            color: #1a4587;
            margin: 25px 0;
            padding: 15px 0;
            border-bottom: 2px solid #1a4587;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .items-table th {
            background: #1a4587;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .terms {
            margin-top: 40px;
            padding: 20px 0;
            border-top: 1px solid #e9ecef;
        }

        .note {
            color: #666;
            font-size: 0.9em;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    @foreach ($records as $record)
        <div class="quotation">
            <!-- Header Section -->
            <div class="header">
                <div class="company-info">
                    <div style="font-size: 28px; color: #1a4587; margin-bottom: 10px;">{{ $record->company->name }}</div>

                    @if ($record->company->partner)
                        <div>
                            {{ $record->company->partner->street1 }}

                            @if ($record->company->partner->street2)
                                ,{{ $record->company->partner->street2 }}
                            @endif
                        </div>
                        
                        <div>
                            {{ $record->company->partner->city }},

                            @if ($record->company->partner->state)
                                {{ $record->company->partner->state->name }},
                            @endif
                            
                            {{ $record->company->partner->zip }}
                        </div>
                        
                        @if ($record->company->partner->country)
                            <div>
                                {{ $record->company->partner->country->name }}
                            </div>
                        @endif
                        
                        @if ($record->company->email)
                            <div>
                                Email: 
                                {{ $record->company->email }}
                            </div>
                        @endif
                        
                        @if ($record->company->phone)
                            <div>
                                Phone: 
                                {{ $record->company->phone }}
                            </div>
                        @endif
                    @endif
                </div>

                <div class="vendor-info">
                    <div>{{ $record->partner->name }}</div>

                    <div>
                        {{ $record->partner->street1 }}

                        @if ($record->partner->street2)
                            ,{{ $record->partner->street2 }}
                        @endif
                    </div>
                    
                    <div>
                        {{ $record->partner->city }},

                        @if ($record->partner->state)
                            {{ $record->partner->state->name }},
                        @endif
                        
                        {{ $record->partner->zip }}
                    </div>
                    
                    @if ($record->partner->country)
                        <div>
                            {{ $record->partner->country->name }}
                        </div>
                    @endif
                    
                    @if ($record->partner->email)
                        <div>
                            Email: 
                            {{ $record->partner->email }}
                        </div>
                    @endif
                    
                    @if ($record->partner->phone)
                        <div>
                            Phone: 
                            {{ $record->partner->phone }}
                        </div>
                    @endif
                </div>
                
                <div class="clearfix"></div>
            </div>

            <!-- Quote Number -->
            <div class="quote-number">
                Request for Quotation #{{ $record->name }}
            </div>

            <!-- Items Table -->
            @if (! $record->lines->isEmpty())
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Expected Date</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($record->lines as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->planned_at }}</td>
                                <td>{{ $item->product_qty.' '.$item->uom->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Additional Information -->
            @if ($record->valid_until)
                <div class="terms">
                    <strong>Quotation Valid Until:</strong><br>
                    {{ $record->valid_until }}
                </div>
            @endif

            <div class="note">
                We look forward to your response. Please contact us for any clarifications.
            </div>
        </div>
    @endforeach
</body>
</html>