<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $banner;
    protected $image;

    public function __construct(Banner $banner, Image $image)
    {
        $this->banner = $banner;
        $this->image = $image;
    }
    public function index()
    {
        $allBanner = $this->banner->getAllBanner();
        if ($allBanner !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Banner retrieved successfully',
                'data' => $allBanner
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve banners',
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'sub_title' => 'required',
            'content' => 'required',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'image_url.required' => 'The image field is required.',
            'image_url.image' => 'The file must be an image.',
            'image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image_url.max' => 'The image may not be greater than 2048 kilobytes.',
        ]);

        $dataInsert = [
            'title' => $validatedData['title'],
            'sub_title' => $validatedData['sub_title'],
            'content' => $validatedData['content'],
            'created_at' => now()
        ];

        $banner = $this->banner->create($dataInsert);

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'upload_image'
            ])->getSecurePath();

            $banner->image_url = $uploadedFileUrl;
            $banner->image_name = $file->getClientOriginalName(); // Assuming you want to store the original name of the image
            $banner->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Add new banner successfully',
            'data' => $banner
        ], 200);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!empty($id)) {
            $banner = Banner::find($id);
            if ($banner) {
                $deleted = $banner->deleteBannerById($id);
                if ($deleted) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Deleted banner successfully',
                        'data' => $banner
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to delete banner',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'banner not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid banner ID',
            ], 400);
        }
    }
}
