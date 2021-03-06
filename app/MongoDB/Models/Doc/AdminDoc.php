<?php
/**
 * Created by PhpStorm.
 * User: TerryQi
 * Date: 2019/10/23
 * Time: 12:17
 */

namespace App\MongoDB\Models\Doc;


class AdminDoc
{
    private $collection_name = "admin";       //mongodb的collection名称

    private $name;          //名称
    private $avatar;       //头像
    private $phonenum;      //手机号
    private $email;      //邮箱
    private $remark;       //备注
    private $token;       //token串
    private $role = '0';          //角色 0：超级管理员 1:普通管理员 2:客服
    private $admin_id;          //创建管理员id
    private $seq = 99;       //排序       值越大越靠前
    private $status = '1';        //状态        0：失效 1：生效

}