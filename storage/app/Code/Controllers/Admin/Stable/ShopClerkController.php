<?php

/**
* Created by PhpStorm.
* User: mtt17
* Date: 2018/4/20
* Time: 10:50
*/

namespace App\Http\Controllers\Admin;

use App\Components\Common\RequestValidator;
use App\Components\ShopClerkManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\ShopClerk;
use Illuminate\Http\Request;

class ShopClerkController
{

    /*
    * 首页
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:46
    */
    public function index(Request $request)
    {
        $self_admin = $request->session()->get('self_admin');
        $data = $request->all();
        //相关搜素条件
        $status = null;
        $search_word = null;
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'status' => $status,
            'search_word' => $search_word,
        );
        $shop_clerks =ShopClerkManager::getListByCon($con_arr, true);
        foreach ($shop_clerks as $shop_clerk) {
        $shop_clerk = ShopClerkManager::getInfoByLevel($shop_clerk, '');
        }

        return view('admin.shopClerk.index', ['self_admin' => $self_admin, 'datas' => $shop_clerks, 'con_arr' => $con_arr]);
    }

    /*
    * 编辑-get
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:46
    */
    public function edit(Request $request)
    {
        $data = $request->all();
        $self_admin = $request->session()->get('self_admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $shop_clerk = new ShopClerk();
        if (array_key_exists('id', $data)) {
        $shop_clerk = ShopClerkManager::getById($data['id']);
        $shop_clerk = ShopClerkManager::getInfoByLevel($shop_clerk, "");
        }
        return view('admin.shopClerk.edit', ['self_admin' => $self_admin, 'data' => $shop_clerk, 'upload_token' => $upload_token]);
    }


    /*
    * 添加或编辑-post
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:46
    */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $self_admin = $request->session()->get('self_admin');
        $shop_clerk = new ShopClerk();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $shop_clerk = ShopClerkManager::getById($data['id']);
        }
        $data['admin_id'] = $self_admin['id'];
        $shop_clerk = ShopClerkManager::setInfo($shop_clerk, $data);
        ShopClerkManager::save($shop_clerk);
        return ApiResponse::makeResponse(true, $shop_clerk, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 设置状态
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:46
    */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        $shop_clerk = ShopClerkManager::getById($data['id']);
        if (!$shop_clerk) {
        return ApiResponse::makeResponse(false, "未找到删除信息", ApiResponse::INNER_ERROR);
        }
        $shop_clerk = ShopClerkManager::setInfo($shop_clerk, $data);
        ShopClerkManager::save($shop_clerk);
        return ApiResponse::makeResponse(true, $shop_clerk, ApiResponse::SUCCESS_CODE);
    }

    /*
    * 删除
    *
    * By Auto CodeCreator
    *
    * 2019-05-18 17:14:16
    */
    public function deleteById(Request $request, $id)
    {
        $data = $request->all();
        $shop_clerk = ShopClerkManager::getById($data['id']);
        if (!$shop_clerk) {
        return ApiResponse::makeResponse(false, "未找到删除信息", ApiResponse::INNER_ERROR);
        }
        ShopClerkManager::deleteById($shop_clerk->id);
        return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);
    }


    /*
    * 查看信息
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:46
    *
    */
    public function info(Request $request)
    {
        $data = $request->all();
        $self_admin = $request->session()->get('self_admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
        'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
        return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //信息
        $shop_clerk = ShopClerkManager::getById($data['id']);
        $shop_clerk = ShopClerkManager::getInfoByLevel($shop_clerk, '0');

        return view('admin.shopClerk.info', ['self_admin' => $self_admin, 'data' => $shop_clerk]);
    }

}

