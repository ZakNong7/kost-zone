<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    private $cloudinary;
    
    public function __construct()
    {
        // Get from env
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        // Debug log
        Log::info('Cloudinary Config Check:', [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret_set' => !empty($apiSecret)
        ]);
        
        // Manual validation
        if (!$cloudName || !$apiKey || !$apiSecret) {
            Log::error('Cloudinary credentials missing!', [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret_set' => !empty($apiSecret)
            ]);
            
            throw new \Exception('Cloudinary credentials not configured. Check your .env file.');
        }
        
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }
    
    public function upload($filePath, $folder = 'kost-zone/kosts')
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder' => $folder
            ]);
            
            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function delete($publicId)
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed: ' . $e->getMessage());
        }
    }
    
    public function extractPublicId($url)
    {
        $pattern = '/\/upload\/(?:v\d+\/)?(.+)\.\w+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}