<?php

/**
* Created by PhpStorm.
* User: mtt17
* Date: 2018/4/20
* Time: 10:50
*/

namespace App\Http\Controllers\Admin;

use App\Components\Common\RequestValidator;
use App\Components\UserCartManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\UserCart;
use Illuminate\Http\Request;

class UserCartController
{

    /*
    * 首页
    *
    * By Auto CodeCreator
    *
    * 2020-02-02 04:07:18
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
        $user_carts =UserCartManager::getListByCon($con_arr, true);
        foreach ($user_carts as $user_cart) {
        $user_cart = UserCartManager::getInfoByLevel($user_cart, '');
        }

        return view('admin.userCart.index', ['self_admin' => $self_admin, 'datas' => $user_carts, 'con_arr' => $con_arr]);
    }

    /*
    * 编辑-get
    *
    * By Auto CodeCreator
    *
    * 2020-02-02 04:07:18
    */
    public function edit(Request $request)
    {
        $data = $request->all();
        $self_admin = $request->session()->get('self_admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $user_cart = new UserCart();
        if (array_key_exists('id', $data)) {
        $user_cart = UserCartManager::getById($data['id']);
        $user_cart = UserCartManager::getInfoByLevel($user_cart, "");
        }
        return view('admin.userCart.edit', ['self_admin' => $self_admin, 'data' => $user_cart, 'upload_token' => $upload_token]);
    }


    /*
    * 添加或编辑-post
    *
    * By Auto CodeCreator
    *
    * 2020-02-02 04:07:18
    */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $self_admin = $request->session()->get('self_admin');
        $user_cart = new UserCart();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $user_cart = UserCartManager::getById($data['id']);
        }
        $data['admin_id'] = $self_admin['id'];
        $user_cart = UserCartManager::setInfo($user_cart, $data);
        UserCartManager::save($user_cart);
        return ApiResponse::makeResponse(true, $user_cart, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 设置状态
    *
    * By Auto CodeCreator
    *
    * 2020-02-02 04:07:18
    */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        $user_cart = UserCartManager::getById($data['id']);
        if (!$user_cart) {
        return ApiResponse::makeResponse(false, "未找到删除信息", ApiResponse::INNER_ERROR);
        }
        $user_cart = UserCartManager::setInfo($user_cart, $data);
        UserCartManager::save($user_cart);
        return ApiResponse::makeResponse(true, $user_cart, ApiResponse::SUCCESS_CODE);
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
        $user_cart = UserCartManager::getById($data['id']);
        if (!$user_cart) {
        return ApiResponse::makeResponse(false, "未找到删除信息", ApiResponse::INNER_ERROR);
        }
        UserCartManager::deleteById($user_cart->id);
        return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);
    }


    /*
    * 查看信息
    *
    * By Auto CodeCreator
    *
    * 2020-02-02 04:07:18
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
        $user_cart = UserCartManager::getById($data['id']);
        $user_cart = UserCartManager::getInfoByLevel($user_cart, '0');

        return view('admin.userCart.info', ['self_admin' => $self_admin, 'data' => $user_cart]);
    }

}

