<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Details PDF</title>
    <!-- Inline Tailwind-inspired styles for PDF compatibility -->
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 1.25rem;
            background-color: #ffffff;
            color: #374151;
            line-height: 1.6;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #3b82f6;
        }
        
        .header h1 {
            color: #1f2937;
            font-size: 1.875rem;
            margin: 0;
            font-weight: 700;
        }
        
        .header p {
            color: #6b7280;
            margin: 0.5rem 0 0 0;
            font-size: 0.875rem;
        }
        
        .info-section {
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            border-radius: 0.375rem;
        }
        
        .info-section h3 {
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .info-row {
            margin-bottom: 0.75rem;
            display: flex;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            display: inline-block;
            width: 120px;
        }
        
        .info-value {
            color: #1f2937;
        }
        
        .maps-section {
            background-color: #fef3c7;
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            border: 2px solid #f59e0b;
            margin-bottom: 2rem;
        }
        
        .maps-section h3 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .maps-link {
            color: #2563eb;
            text-decoration: underline;
            word-break: break-all;
            display: block;
            margin: 1rem 0;
            padding: 0.75rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-family: 'Courier New', monospace;
        }
        
        .coordinates-grid {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .coordinate-box {
            flex: 1;
            background: white;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            text-align: center;
        }
        
        .coordinate-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .coordinate-value {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.75rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Location Capture Report</h1>
        <p>Generated on: {{ $generated_at }}</p>
    </div>

    <div class="content">
        <!-- Personal Information -->
        <div class="info-section">
            <h3>Personal Information</h3>
            <div class="info-row">
                <span class="info-label">Name:</span>
                {{ $name }}
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                {{ $email }}
            </div>
        </div>

        <!-- Location Information -->
        <div class="info-section">
            <h3>Location Coordinates</h3>
            <div class="info-row">
                <span class="info-label">Latitude:</span>
                {{ number_format($latitude, 6) }}
            </div>
            <div class="info-row">
                <span class="info-label">Longitude:</span>
                {{ number_format($longitude, 6) }}
            </div>
        </div>

        <!-- Google Maps Link -->
        <div class="maps-section">
            <h3>Google Maps Location</h3>
            <p>Click the link below to view this location on Google Maps:</p>
            <a href="{{ $maps_url }}" class="maps-link">{{ $maps_url }}</a>
            <p><small>Copy and paste the above link into your web browser to open the location in Google Maps.</small></p>
        </div>
    </div>

    <div class="footer">
        <p>This document was generated by the Location Capture System.</p>
    </div>
</body>
</html>