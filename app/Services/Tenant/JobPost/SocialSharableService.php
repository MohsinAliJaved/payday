<?php


namespace App\Services\Tenant\JobPost;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\Tenant\JobPost\JobPost;
use App\Services\Tenant\TenantService;

class SocialSharableService extends TenantService
{
    use FileHandler;

    public function __construct(JobPost $jobPost)
    {
        $this->model = $jobPost;
    }

    public function storeAttachment()
    {
        if (request()->hasFile('thumbnail_image')){

            $this->deleteImage(optional(request()->thumbnail_image)->path);

            $file_path = $this->isWithOriginalName()->storeFile(request()->thumbnail_image, 'job_post');

            $this->model->jobPostThumbnail()->updateOrCreate([
                'type' => 'job_post_thumbnail_image'
            ], [
                'path' => $file_path
            ]);
        }

        return $this;
    }
}