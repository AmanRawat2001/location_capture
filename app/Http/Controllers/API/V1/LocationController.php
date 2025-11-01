<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display the location capture form
     */
    public function index()
    {
        return view('location.form');
    }

    /**
     * Process the form submission and store data in session
     */
    public function store(Request $request)
    {
        // Validate the form data
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // Store data in session for PDF generation
        session([
            'user_data' => $validatedData,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location captured successfully!',
            'data' => $validatedData,
        ]);
    }

    /**
     * Generate and download PDF with location data
     */
    public function downloadPdf()
    {
        $userData = session('user_data');

        if (! $userData) {
            return redirect()->route('location.form')->with('error', 'No data found. Please submit the form first.');
        }

        // Generate Google Maps URL
        $mapsUrl = "https://www.google.com/maps?q={$userData['latitude']},{$userData['longitude']}";

        // Prepare data for PDF
        $pdfData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'latitude' => $userData['latitude'],
            'longitude' => $userData['longitude'],
            'maps_url' => $mapsUrl,
            'generated_at' => now()->format('F j, Y, g:i a'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('location.pdf', $pdfData);

        // Download PDF
        return $pdf->download('location-details-'.now()->format('Y-m-d-H-i-s').'.pdf');
    }

    /**
     * Show success page with map preview
     */
    public function success()
    {
        $userData = session('user_data');

        if (! $userData) {
            return redirect()->route('location.form')->with('error', 'No data found. Please submit the form first.');
        }

        return view('location.success', compact('userData'));
    }
}
