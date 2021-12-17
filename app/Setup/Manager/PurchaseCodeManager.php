<?php


namespace App\Setup\Manager;


use Illuminate\Support\Facades\DB;

class PurchaseCodeManager
{
    public function store($code)
    {
        DB::table('gain')
            ->updateOrInsert([
                'app_id' => config('gain.app_id')
            ],[
                'app_id' => config('gain.app_id'),
                'purchase_code' => $code
            ]);
        cache()->forget('purchase_code_is_set');
    }

    public function getCode()
    {
        return cache()->remember('purchase_code_is_set', 604800, function () {
            return optional(DB::table('gain')->where('app_id', config('gain.app_id'))->first())->purchase_code;
        });
    }
}
