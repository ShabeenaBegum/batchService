<?php
/**
 * Created by PhpStorm.
 * User: shabe
 * Date: 2/28/2018
 * Time: 12:58 PM
 */

namespace App\Http\Requests\Batch;


class SessionCancel
{
    public function handle($request, $batch)
    {
        return $request;
    }
}