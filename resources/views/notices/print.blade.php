<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $notice->title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
            margin: 30px 40px;
            background: #fff;
        }
        .header {
            text-align: center;
            color: #444;
        }
        .header img {
            height: 70px;
            margin-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1.2px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2c3e50;
        }
        hr {
            border: none;
            border-top: 2px solid #3498db;
            margin: 20px auto 30px;
            width: 120px;
        }
        .notice-content {
            max-width: 800px;
            margin: auto;
            line-height: 1.6;
            font-size: 16px;
        }
        .notice-content h2 {
            font-weight: 600;
            color: #2980b9;
            margin-bottom: 10px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }
        .notice-meta {
            font-size: 14px;
            color: #555;
            margin-bottom: 25px;
            display: flex;
            justify-content: left;
            gap: 30px;
        }
        .notice-meta strong {
            color: #333;
        }

        /* Print-specific styles */
        @page {
            size: A4;
            margin: 20mm;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 12px;
                color: #777;
                border-top: 1px solid #ddd;
                padding: 5px 0;
            }
            
            .page-number:before {
                content: "Page " counter(page);
            }
            
            .page-count:before {
                content: " of " counter(pages);
            }
            
            body {
                counter-reset: page;
            }
            
            @page {
                @bottom-center {
                    content: "128, Jubilee Road, 10th Floor, Kader Tower, Chittagong, 40001 — Page " counter(page) " of " counter(pages);
                    font-size: 12px;
                    color: #777;
                }
            }
        }
        
        /* Hide footer in screen view */
        @media screen {
            .print-footer {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <img src="{{ asset('images/Jubilee-Techonology-Ltd.png') }}" alt="Company Logo">
        <h1>Juiblee Technology Ltd</h1>
    </div>

    <hr>

    <div class="notice-content">
        <h2>Subject: {{ $notice->title }}</h2>
        <div class="notice-meta">
            <div><strong>Publish Date:</strong> {{ \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') }}</div>
        </div>
        <div>{!! $notice->content !!}</div>
    </div>

    <div class="print-footer">
        <span class="page-number"></span><span class="page-count"></span>
    </div>

</body>
</html>