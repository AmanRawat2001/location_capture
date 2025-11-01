# Location Capture App

A simple web app that captures your location and generates a PDF with your details and a Google Maps link.

## Requirements

- PHP 8.2+
- Composer
- Internet connection

## How it works

1. User fills form (name/email)
2. Browser gets their location automatically
3. PDF generated with their info and Google Maps link
4. Download ready

## Installation

1. Clone the repository:
```bash
git clone <your-repo-url>
cd location_capture
```

2. Install dependencies:
```bash
composer install
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. (Optional) Add Google Maps API key to `.env`:
```
GOOGLE_MAPS_API_KEY=your_api_key_here
```

5. Start the server:
```bash
php artisan serve
```

6. Visit: http://localhost:8000


