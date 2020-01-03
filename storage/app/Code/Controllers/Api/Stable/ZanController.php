<?php

/**
* Created by PhpStorm.
* User:robot
* Date: 2020-01-03 02:14:57
* Time: 13:29
*/

namespace App\Http\Controllers\Api;


use App\Components\Common\RequestValidator;
use App\Components\ZanManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use Illuminate\Http\Request;

class ZanController
{

    /*
    * 根据id获取信息
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:57
    */
    public function getById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $zan = ZanManager::getById($data['id']);
        //补充信息
        if($zan){
            $level = null;
            if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
                $level = $data['level'];
            }
            $zan = ZanManager::getInfoByLevel($zan,$level);
        }
        return ApiResponse::makeResponse(true, $zan, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 根据条件获取列表
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:57
    */
    public function getListByCon(Request $request)
    {
        $data = $request->all();
        $status = '1';
        $is_paginate = true;
        $level='Y';
        //配置获取信息级别
        if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
            $level = $data['level'];
        }
        //配置是否分页
        if (array_key_exists('is_paginate', $data) && !Utils::isObjNull($data['is_paginate'])) {
            $is_paginate = $data['is_paginate'];
        }

        //配置条件
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'status' => $status,
        );
        $zans = ZanManager::getListByCon($con_arr, $is_paginate);
        foreach ($zans as $zan) {
            $zan = ZanManager::getInfoByLevel($zan, $level);
        }

        return ApiResponse::makeResponse(true, $zans, ApiResponse::SUCCESS_CODE);
    }
}

