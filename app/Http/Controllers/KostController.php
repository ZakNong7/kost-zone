<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KostController extends Controller
{
    private $cloudinary;
    
    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'google_maps_link' => 'nullable|url',
            'type' => 'required|in:Putra,Putri,Campuran',
            'max_occupants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'facilities' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'contact_whatsapp' => 'nullable|string',
            'contact_instagram' => 'nullable|string',
            'contact_facebook' => 'nullable|string',
        ]);

        $images = [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $url = $this->cloudinary->upload($image->getRealPath());
                    $images[] = $url;
                    Log::info('Image uploaded: ' . $url);
                } catch (\Exception $e) {
                    Log::error('Upload failed: ' . $e->getMessage());
                    return back()->with('error', 'Upload gagal: ' . $e->getMessage())->withInput();
                }
            }
        }

        Kost::create([
            'owner_id' => auth()->guard('owner')->id(),
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'google_maps_link' => $request->google_maps_link,
            'type' => $request->type,
            'max_occupants' => $request->max_occupants,
            'price' => $request->price,
            'facilities' => $request->facilities,
            'images' => $images,
            'approval_status' => 'pending',
            'contact_whatsapp' => $request->contact_whatsapp,
            'contact_instagram' => $request->contact_instagram,
            'contact_facebook' => $request->contact_facebook,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Kost berhasil ditambahkan');
    }

    public function update(Request $request, Kost $kost)
    {
        if ($kost->owner_id !== auth()->guard('owner')->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'google_maps_link' => 'nullable|url',
            'type' => 'required|in:Putra,Putri,Campuran',
            'max_occupants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'facilities' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'contact_whatsapp' => 'nullable|string',
            'contact_instagram' => 'nullable|string',
            'contact_facebook' => 'nullable|string',
        ]);

        $images = $kost->images ?? [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $url = $this->cloudinary->upload($image->getRealPath());
                    $images[] = $url;
                } catch (\Exception $e) {
                    return back()->with('error', 'Upload gagal')->withInput();
                }
            }
        }

        $kost->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'google_maps_link' => $request->google_maps_link,
            'type' => $request->type,
            'max_occupants' => $request->max_occupants,
            'price' => $request->price,
            'facilities' => $request->facilities,
            'images' => $images,
            'contact_whatsapp' => $request->contact_whatsapp,
            'contact_instagram' => $request->contact_instagram,
            'contact_facebook' => $request->contact_facebook,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Kost berhasil diupdate');
    }

    public function deleteImage(Request $request, Kost $kost)
    {
        if ($kost->owner_id !== auth()->guard('owner')->id()) {
            return response()->json(['success' => false], 403);
        }

        $images = $kost->images ?? [];
        $imagePath = $request->image_path;

        if (in_array($imagePath, $images)) {
            $publicId = $this->cloudinary->extractPublicId($imagePath);
            if ($publicId) {
                $this->cloudinary->delete($publicId);
            }
            
            $images = array_values(array_diff($images, [$imagePath]));
            $kost->update(['images' => $images]);
            
            return response()->json([
                'success' => true, 
                'remaining_images' => count($images)
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function deleteAllImages(Kost $kost)
    {
        if ($kost->owner_id !== auth()->guard('owner')->id()) {
            return response()->json(['success' => false], 403);
        }

        foreach ($kost->images ?? [] as $image) {
            $publicId = $this->cloudinary->extractPublicId($image);
            if ($publicId) {
                $this->cloudinary->delete($publicId);
            }
        }
        
        $kost->update(['images' => []]);
        
        return response()->json(['success' => true]);
    }

    public function toggleActive(Kost $kost)
    {
        if ($kost->owner_id !== auth()->guard('owner')->id()) {
            abort(403);
        }

        $kost->update(['is_active' => !$kost->is_active]);
        
        return back()->with('success', 'Status berhasil diubah');
    }
}