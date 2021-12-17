<?php

namespace App\Helpers\Core\General;

class AppResponseHelper
{
    public function submittedResponse($name, $data = [])
    {
        return array_merge([
            'status' => true,
            'message' => trans('default.submitted_response', [
                'name' => trans("default.{$name}"),
            ]),
        ], $data);
    }

    public function nothingStoreResponse()
    {
        return array_merge([
            'status' => true,
            'message' => trans('default.nothing_store'),
        ]);
    }

    public function updateFailedResponse($name, $data = [])
    {
        return array_merge([
            'status' => false,
            'message' => trans('default.update_failed_response', [
                'name' => trans("default.{$name}"),
            ]),
        ], $data);
    }

    public function fileUploadResponse($name, $data = [])
    {
        return array_merge([
            'status' => false,
            'message' => trans('default.file_upload_response', [
                'name' => trans("default.{$name}"),
            ]),
        ], $data);
    }

    public function customFailedResponse($name, $data = [])
    {
        return array_merge(
            [
                'status' => false,
                'message' => trans($name ? "default.{$name}" : 'default.failed_response'),
                'data' => $data,
            ]
        );
    }
}
