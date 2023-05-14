<?php

namespace App\Services;

class UtilityService
{
    public function __construct()
    {
        // ...
    }

    public static function genRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function genRandomFileName()
    {
        // datetime str
        return date("YmdHis", time()) . UtilityService::genRandomString(5);
        
    }

    public static function getPatchStatus($jobBatch)
    {
        if (!empty($jobBatch->finished_at)) {
            return "完了";
        } elseif ($jobBatch->total_jobs == $jobBatch->failed_jobs) {
            return "完了(失敗)";
        } elseif ($jobBatch->pending_jobs != 0 && $jobBatch->pending_jobs == $jobBatch->failed_jobs) {
            return "完了(部分失敗)";
        } else {
            return "処理中";
        }
    }
}
