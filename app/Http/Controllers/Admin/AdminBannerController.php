<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\BannerRequest;


class AdminBannerController extends Controller
{
    protected $banner;
    public function __construct()
    {
        $this->banner = new Banner();
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/admin-show-all-banner",
     *     summary="Get all banners",
     *     tags={"Banners"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="500", description="Error"),
     *     security={{"bearerAuth":{}}}
     * )
     */
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/admin-create-banner",
     *     summary="Create a new banner",
     *     tags={"Banners"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title of banner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Content of banner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sub_title",
     *         in="query",
     *         description="Subtitle of banner",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url",
     *                     type="string",
     *                     format="binary",
     *                     description="Image of banner"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Banner created successfully"),
     *     @OA\Response(response="422", description="Validation errors"),
     *     @OA\Response(response="400", description="Banner creation failed"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function store(BannerRequest $request)
    {
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
    /**
     * @OA\Post(
     *     path="/api/admin-update-banner/{id}",
     *     summary="Update a banner by ID",
     *     tags={"Banners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the banner to update",
     *         @OA\Schema(
     *             type="integer",
     *             format="uuid"
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title of banner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Content of banner",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="sub_title",
     *         in="query",
     *         description="Subtitle of banner",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image_url",
     *                     type="string",
     *                     format="binary",
     *                     description="Image of post"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Banner not found"),
     *     @OA\Response(response="422", description="Failed to update banner")
     * )
     */
    public function update(BannerRequest $request, $id)
    {
        $banner = $this->banner->getbannerById($id);
        if (!empty($banner)) {
            $bannerData = [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'sub_title' => $request->input('sub_title'),
                'updated_at' => now()
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
            $bannerInserted = $this->banner->updateBanner($id, $bannerData);
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
    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/admin-delete-banner/{id}",
     *     summary="Delete a banner by ID",
     *     tags={"Banners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the banner to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Banner deleted successfully"),
     *     @OA\Response(response="404", description="Banner not found"),
     *     @OA\Response(response="500", description="Failed to delete banner")
     * )
     */
    public function destroy(int $id)
    {
        $banner = $this->banner->getBannerById($id);
        if ($banner) {
            $deleted = $this->banner->deleteBannerById($id);
            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Banner deleted successfully!',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete banner!',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner not found with id ' . $id,
            ], 404);
        }
    }
}
