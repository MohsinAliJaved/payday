<?php

use App\Helpers\Core\General\ResponseHelper;
use App\Helpers\Core\General\AppResponseHelper;

if (!function_exists('created_responses')) {
    function created_responses($name, $data = [])
    {
        return resolve(ResponseHelper::class)->createdResponse($name, $data);
    }
}


if (!function_exists('updated_responses')) {
    function updated_responses($name, $data = [])
    {
        return resolve(ResponseHelper::class)->updatedResponse($name, $data);
    }
}


if (!function_exists('deleted_responses')) {
    function deleted_responses($name, $data = [])
    {
        return resolve(ResponseHelper::class)->deletedResponse($name, $data);
    }
}

if (!function_exists('failed_responses')) {
    function failed_responses($data = [])
    {
        return resolve(ResponseHelper::class)->failedResponse($data);
    }
}

if (!function_exists('attached_response')) {
    function attached_response($name, $data = [])
    {
        return resolve(ResponseHelper::class)->attachedResponse($name, $data);
    }
}

if (!function_exists('detached_response')) {
    function detached_response($name, $data = []) {
        return resolve(ResponseHelper::class)->detachedResponse($name, $data);
    }
}

if (!function_exists('duplicated_response')) {
    function duplicated_response($name, $data = []) {
        return resolve(ResponseHelper::class)->duplicatedResponse($name, $data);
    }
}

if (!function_exists('status_response')) {
    function status_response($name, $status, $data = []) {
        return resolve(ResponseHelper::class)->statusResponse($name, $status, $data);
    }
}

if (!function_exists('custom_failed_response')) {
    function custom_failed_response($name = "", $data = [])
    {
        return resolve(AppResponseHelper::class)->customFailedResponse($name, $data);
    }
}


if (!function_exists('custom_response')) {
    function custom_response($key , $status = true) {
        return [
            'message' => __t($key),
            'status' => $status,
        ];
    }
}
