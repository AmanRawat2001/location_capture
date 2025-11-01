<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Capture Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📍 Location Capture & PDF Generator</h1>
            <p class="text-gray-600">Enter your information and capture your current location</p>
        </div>

        <!-- Status Message -->
        <div id="locationStatus" class="hidden p-4 rounded-md mb-6">
            <span id="statusText"></span>
        </div>

        <!-- Main Form -->
        <form id="locationForm" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter your full name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                    <input type="text" id="latitude" name="latitude" readonly 
                           placeholder="Will be auto-detected"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600">
                </div>
                
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                    <input type="text" id="longitude" name="longitude" readonly 
                           placeholder="Will be auto-detected"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
                <button type="button" id="getLocationBtn" 
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                    Get My Location
                </button>
                
                <button type="submit" id="submitBtn" disabled
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-200">
                    Submit & Generate PDF
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('locationForm');
            const getLocationBtn = document.getElementById('getLocationBtn');
            const submitBtn = document.getElementById('submitBtn');
            const locationStatus = document.getElementById('locationStatus');
            const statusText = document.getElementById('statusText');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // CSRF token setup for AJAX requests
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get location button click handler
            getLocationBtn.addEventListener('click', function() {
                getCurrentLocation();
            });

            // Form submit handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm();
            });

            function getCurrentLocation() {
                if (!navigator.geolocation) {
                    showLocationStatus('error', 'Geolocation is not supported by this browser.');
                    return;
                }

                // Show loading status
                showLocationStatus('loading', 'Getting your location...');
                getLocationBtn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Update form fields
                        latitudeInput.value = latitude.toFixed(6);
                        longitudeInput.value = longitude.toFixed(6);
                        
                        // Show success status
                        showLocationStatus('success', 'Location captured successfully!');
                        
                        // Enable submit button
                        submitBtn.disabled = false;
                        getLocationBtn.disabled = false;
                    },
                    function(error) {
                        let errorMessage = 'Unable to get your location.';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location access denied. Please allow location access.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information is unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out.';
                                break;
                        }
                        
                        showLocationStatus('error', errorMessage);
                        getLocationBtn.disabled = false;
                    }
                );
            }

            function showLocationStatus(type, message) {
                locationStatus.className = `p-4 rounded-md mb-6 ${getStatusClasses(type)}`;
                statusText.textContent = message;
                locationStatus.classList.remove('hidden');
            }

            function getStatusClasses(type) {
                switch(type) {
                    case 'loading':
                        return 'bg-yellow-100 border border-yellow-300 text-yellow-800';
                    case 'success':
                        return 'bg-green-100 border border-green-300 text-green-800';
                    case 'error':
                        return 'bg-red-100 border border-red-300 text-red-800';
                    default:
                        return 'bg-gray-100 border border-gray-300 text-gray-800';
                }
            }

            function submitForm() {
                const formData = new FormData(form);
                
                // Show loading state
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;

                fetch('/submit-location', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Success! Redirecting to results page...');
                        window.location.href = '/success';
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong!'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: Something went wrong. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.textContent = 'Submit & Generate PDF';
                    submitBtn.disabled = false;
                });
            }

            // Auto-get location on page load
            setTimeout(() => {
                getCurrentLocation();
            }, 1000);
        });
    </script>
</body>
</html>