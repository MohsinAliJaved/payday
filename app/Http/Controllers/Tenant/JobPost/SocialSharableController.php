<?php

namespace App\Http\Controllers\Tenant\JobPost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\JobPost\JobPost;
use App\Helpers\Core\Traits\FileHandler;
use App\Services\Tenant\JobPost\SocialSharableService;

class SocialSharableController extends Controller
{
    use FileHandler;

    public function __construct(SocialSharableService $service)
    {
        $this->service = $service;
    }

    public function show($id)
    {
        return JobPost::query()->select('id')->with('jobPostThumbnail')->find($id);
    }

    public function update(Request $request, JobPost $jobPost)
    {
        $request->validate([
            'thumbnail_image' => 'required|image|max:512'
        ]);
        $this->service
            ->setModel($jobPost)
            ->setAttrs($request->only('thumbnail_image'))
            ->storeAttachment();

        return updated_responses('thumbnail_image');
    }
}
