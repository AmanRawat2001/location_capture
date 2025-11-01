<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Captured Successfully</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Success Header -->
        <div class="bg-green-50 border-b border-green-200 text-center py-8">
            <h1 class="text-3xl font-bold text-green-800 mb-2">Location Captured Successfully!</h1>
            <p class="text-green-600">Your information has been saved and is ready for PDF generation.</p>
        </div>

        <div class="p-8">
            <!-- User Information -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-8">
                <h3 class="text-xl font-semibold text-blue-800 mb-4">Captured Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-24">Name:</span>
                        <span class="text-gray-900">{{ $userData['name'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-24">Email:</span>
                        <span class="text-gray-900">{{ $userData['email'] }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-24">Latitude:</span>
                        <span class="text-gray-900 font-mono">{{ number_format($userData['latitude'], 6) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-24">Longitude:</span>
                        <span class="text-gray-900 font-mono">{{ number_format($userData['longitude'], 6) }}</span>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Location Preview</h3>
                <div class="border border-gray-200 rounded-lg overflow-hidden mb-4">
                    <div id="map" class="h-80 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                            <p class="text-gray-600">Loading map...</p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="https://www.google.com/maps?q={{ $userData['latitude'] }},{{ $userData['longitude'] }}" 
                       target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        Open in Google Maps
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('location.download') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200">
                    Download PDF
                </a>
                
                <a href="{{ route('location.form') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition duration-200">
                    Capture Another Location
                </a>
            </div>

            <!-- Info -->
            <div class="text-center mt-8 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-600 text-sm">
                    💡 The PDF will contain your information and a clickable Google Maps link.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Global variables for Google Maps
        let map;
        let marker;
        const latitude = {{ $userData['latitude'] }};
        const longitude = {{ $userData['longitude'] }};

        // Initialize Google Maps
        function initMap() {
            const location = { lat: latitude, lng: longitude };
            
            // Create the map
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location
            });
            
            // Create marker
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "Your Location"
            });
            
            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `<div><strong>Your Location</strong><br>Lat: ${latitude.toFixed(6)}<br>Lng: ${longitude.toFixed(6)}</div>`
            });
            
            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });
        }

        // Handle Google Maps API load error
        function handleMapError() {
            const mapContainer = document.getElementById('map');
            mapContainer.innerHTML = `
                <div class="h-80 bg-yellow-50 border-2 border-dashed border-yellow-300 flex items-center justify-center p-8">
                    <div class="text-center max-w-md">
                        <div class="text-4xl mb-4">🗝️</div>
                        <h4 class="text-lg font-semibold text-yellow-800 mb-4">Google Maps API Key Required</h4>
                        <div class="text-sm text-yellow-700 space-y-2">
                            <p>To display the map preview:</p>
                            <ol class="text-left list-decimal list-inside space-y-1">
                                <li>Get an API key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" class="underline font-medium">Google Cloud Console</a></li>
                                <li>Add <code class="bg-yellow-200 px-1 rounded">GOOGLE_MAPS_API_KEY=your_key_here</code> to your .env file</li>
                                <li>Refresh this page</li>
                            </ol>
                        </div>
                    </div>
                </div>
            `;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Try to load Google Maps
            const apiKey = '{{ config("googlemaps.api_key", "") }}';
            
            if (!apiKey) {
                handleMapError();
            } else {
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap`;
                script.async = true;
                script.defer = true;
                script.onerror = handleMapError;
                document.head.appendChild(script);
            }
        });
    </script>
</body>
</html>