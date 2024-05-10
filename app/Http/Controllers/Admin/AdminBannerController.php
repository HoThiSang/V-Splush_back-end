<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Requests\BannerRequest;

class AdminBannerController extends Controller
{
    protected $banner;

    public function __construct()
    {
        $this->banner = new Banner();
    }

    public function index()
    {
        $allBanner = $this->banner->getAllBanner();
        if ($allBanner !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Banners retrieved successfully',
                'data' => $allBanner
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve banners',
            ], 500);
        }
    }

    public function create()
    {
        // Show form for creating a new banner (if needed)
    }

    public function store(BannerRequest $request)
    {
        // dd($request->all());
        // return response()->json('hellll');
        // $banner = $this->banner;
        $banner = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'sub_title' => $request->input('sub_title')
        ];
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'upload_image'
            ])->getSecurePath();
            $publicId = Cloudinary::getPublicId();
            $filename = time() . '_' . $file->getClientOriginalName();
            $banner['image_url'] = $uploadedFileUrl;
            $banner['image_name'] = $filename;
            $banner['publicId'] = $publicId;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Please upload an image',
            ], 422);
        }
        $banners = $this->banner->creatNewBanner($banner);
        if ($banners > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Banner created successfully',
                'data' => $banner
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner created fail',
                'data' => $banner
            ], 400);
        }
    }



    public function show(string $id)
    {
        // Show the specified banner (if needed)
    }

    public function edit(string $id)
    {
        // Show form for editing the specified banner (if needed)
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(BannerRequest $request, $id)
    // {
    //     $banner = Banner::find($id);
    //     if (!$banner) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Banner not found',
    //         ], 404);
    //     }
    //     $banner = [
    //         'title' => $request->input('title'),
    //         'content' => $request->input('content'),
    //         'sub_title' => $request->input('sub_title')
    //     ];
    //     if ($request->hasFile('image_url')) {
    //         $file = $request->file('image_url');
    //         $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
    //             'folder' => 'upload_image'
    //         ])->getSecurePath();
    //         $publicId = Cloudinary::getPublicId();
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $banner['image_url'] = $uploadedFileUrl;
    //         $banner['image_name'] = $filename;
    //         $banner['publicId'] = $publicId;
    //     }
    //     $bannerUpdated = $this->$banner->updateBanner($id,$banner); 

    //     if ($bannerUpdated) {
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Update banner successfully',
    //             'data' => $bannerUpdated
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to update banner',
    //         ], 422);
    //     }

    // }

    public function update(BannerRequest $request, $id)
    {
        $banner = $this->banner->getbannerById($id);
        if (!empty($banner)) {
            $bannerData = [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'sub_title' => $request->input('sub_title')
            ];
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'upload_image'
                ])->getSecurePath();
                $publicId = Cloudinary::getPublicId();
                $bannerData['image_name'] = $request->title;
                $bannerData['image_url'] = $uploadedFileUrl;
                $bannerData['publicId'] = $publicId;
            }
            $bannerInserted = $this->banner->updatebanner($id, $bannerData);
            if ($bannerInserted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Update banner successfully',
                    'data' => $bannerInserted
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update banner',
                ], 422);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'banner not found',
            ], 404);
        }
    }


    public function destroy(string $id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            $deleted = $banner->deleteBannerById($id);
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Banner deleted successfully',
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
                'message' => 'Banner not found',
            ], 404);
        }
    }
}
