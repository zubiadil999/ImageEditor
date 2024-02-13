<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class ImageEditorController extends Controller
{
    public function process(Request $request)
    {
        $feature = $request->input('feature');
    
        switch ($feature) {
            case 'background_removal':
                return $this->backgroundRemoval($request);
                break;
            case 'cleanup':
                return $this->cleanup($request);
                break;
            default:
                return back()->with('error', 'Invalid editing feature selected.');
                break;
        }
    }
    
    private function backgroundRemoval(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);
    
        $imageFile = $request->file('image');

        $imagePath = $imageFile->store('uploads', 'public');

        $response = Http::withHeaders([
            'x-api-key' => env('CLIPDROP_API_KEY'), 
        ])->attach('image_file', file_get_contents(storage_path('app/public/' . $imagePath)), $imageFile->getClientOriginalName())
          ->post('https://clipdrop-api.co/remove-background/v1');
    
        if ($response->successful()) {
            $headers = $response->headers();
            $imageMimeType = $headers['Content-Type'][0] ?? 'image/png';
            $imageFormat = explode('/', $imageMimeType)[1];

            $editedImage = $response->body();
            $base64Image = 'data:' . $imageMimeType . ';base64,' . base64_encode($editedImage);
    
            return view('result', [
                'base64Image' => $base64Image,
                'imageFormat' => $imageFormat,    
            ]);
        } else {
            Log::error('Failed to process the image:', ['error' => $result->body()]);

            return back()->with('error', 'Failed to process the image: ' . $result->body());
        }
    }

    public function cleanup(Request $request)
{
    $request->validate([
        'image' => 'required|image',
        'mask' => 'image',
    ]);

    $imageFile = $request->file('image');
    $maskFile = $request->file('mask');

    $imagePath = $imageFile->store('uploads', 'public');
    $maskPath = $maskFile ? $maskFile->store('uploads', 'public') : null;

    $response = Http::withHeaders([
        'x-api-key' => env('CLIPDROP_API_KEY'),
    ])->attach('image_file', file_get_contents(storage_path('app/public/' . $imagePath)), $imageFile->getClientOriginalName());

    if ($maskPath) {
        $response->attach('mask_file', file_get_contents(storage_path('app/public/' . $maskPath)), $maskFile->getClientOriginalName());
    }

    $result = $response->post('https://clipdrop-api.co/cleanup/v1');
    if ($result->successful()) {
        $editedImage = $result->json();
        $editedImage['original_image_path'] = 'storage/uploads/' . basename($imagePath);

        if ($maskPath) {
            $editedImage['masked_image_path'] = 'storage/uploads/' . basename($maskPath);
        }

        return view('result', ['editedImage' => $editedImage]);
    } else {
        \Illuminate\Support\Facades\Log::error('Failed to process the image:', ['error' => $result->body()]);
        return back()->with('error', 'Failed to process the image: ' . $result->body());
    }
}



}