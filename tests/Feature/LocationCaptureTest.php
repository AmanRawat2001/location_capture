<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocationCaptureTest extends TestCase
{
    /**
     * Test that the main form page loads successfully.
     */
    public function test_location_form_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('location.form');
        $response->assertSee('Location Capture');
        $response->assertSee('Full Name');
        $response->assertSee('Email Address');
    }

    /**
     * Test form submission with valid data.
     */
    public function test_form_submission_with_valid_data(): void
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ];

        $response = $this->post('/submit-location', $formData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Check if data is stored in session
        $this->assertEquals($formData, session('user_data'));
    }

    /**
     * Test form validation with missing data.
     */
    public function test_form_validation_fails_with_missing_data(): void
    {
        $response = $this->postJson('/submit-location', []);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /**
     * Test success page requires session data.
     */
    public function test_success_page_requires_session_data(): void
    {
        // Try to access success page without session data
        $response = $this->get('/success');

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'No data found. Please submit the form first.');
    }

    /**
     * Test success page with session data.
     */
    public function test_success_page_with_session_data(): void
    {
        // Set session data
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ];

        $this->withSession(['user_data' => $userData]);

        $response = $this->get('/success');

        $response->assertStatus(200);
        $response->assertViewIs('location.success');
        $response->assertSee('Jane Doe');
        $response->assertSee('jane@example.com');
    }

    /**
     * Test PDF download requires session data.
     */
    public function test_pdf_download_requires_session_data(): void
    {
        $response = $this->get('/download-pdf');

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'No data found. Please submit the form first.');
    }

    /**
     * Test PDF download with session data.
     */
    public function test_pdf_download_with_session_data(): void
    {
        // Set session data
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ];

        $this->withSession(['user_data' => $userData]);

        $response = $this->get('/download-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertTrue($response->headers->has('Content-Disposition'));
    }
}
