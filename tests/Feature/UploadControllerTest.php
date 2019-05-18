<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Auth;
use DB;

class UploadControllerTest extends TestCase
{

    public function testUploadMovie()
    {
        // create a test admin
        $testUser = $this->CreateTestAdmin();

        // Become the user
        $this->actingAs($testUser);

        // create test files
        $testVideoFile = UploadedFile::fake()->create('video.mp4');
        $testThumbnailFile = UploadedFile::fake()->image('thumb.jpg');

        $testResponse = $this->post('admin/upload',
        [
            'title' => 'TestTitle',
            'year' => 2000,
            'summary' => 'Test Summary',
            'subscription' => 'VideoDivision',
            'mediatype' => 'movie',
            'duration' => '0:0:0',
            'video' => $testVideoFile,
            'thumbnail' => $testThumbnailFile,
            'actorSelect1' => 1,
            'directorSelect1' => 1,
            'genreSelect1' => 1
        ]);

        // should return the upload view
        $testResponse->assertViewIs('upload');

        // check the view has status indicating success
        $testResponse->assertViewHas('status', 1);
    }

    public function testUploadShow()
    {
        // create a test admin
        $testUser = $this->CreateTestAdmin();

        // Become the user
        $this->actingAs($testUser);

        // create test files
        $testVideoFile = UploadedFile::fake()->create('video.mp4');
        $testThumbnailFile = UploadedFile::fake()->image('thumb.jpg');

        $testResponse = $this->post(
            'admin/upload',
            [
                'title' => 'TestTitle',
                'year' => 2000,
                'summary' => 'Test Summary',
                'subscription' => 'VideoDivision',
                'mediatype' => 'show',
                'duration' => '0:0:0',
                'video' => $testVideoFile,
                'thumbnail' => $testThumbnailFile,
                'actorSelect1' => 1,
                'directorSelect1' => 1,
                'genreSelect1' => 1,
                'seasonNumber' => 1,
                'episodeNumber' => 1,
                'addNewShow' => 'on',
                'showInput' => 'TestShowName',
                'showSummary' => 'Test Show Summary',
            ]
        );

        // should return the upload view
        $testResponse->assertViewIs('upload');

        // check the view has status indicating success
        $testResponse->assertViewHas('status', 1);
    }

    public function testViewPageAsAdmin()
    {
        // create a test admin
        $testUser = $this->CreateTestAdmin();

        // Become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('admin/upload');

        $testResponse->assertViewIs('upload');
    }

    public function testFailToViewPageAsNonAdmin()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('admin/upload');
        $testResponse->assertRedirect('home');
    }

    public function testFailToUploadVideoAsNonAdmin()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        $testResponse = $this->post('admin/upload');
        $testResponse->assertRedirect('home');
    }

}
