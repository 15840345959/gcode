<?php


/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Components;


use App\Components\Common\Utils;
use App\Models\UserCoupon;
use App\Components\Redis\RedisManager;

//V3版本的manager层，在V2版本的基础上，进一步结合redis
class UserCouponManager
{

    /*
     * getById
     *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
     */
    public static function getById($id)
    {
        $info = UserCoupon::where('id', $id)->first();
        return $info;
    }

    /*
    * getByIdWithTrashed
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
    */
    public static function getByIdWithTrashed($id)
    {
        $info = UserCoupon::withTrashed()->where('id', $id)->first();
        return $info;
    }

    /*
    * deleteById
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
    */
    public static function deleteById($id)
    {
        $info = self::getById($id);
        $result = $info->delete();
        RedisManager::del("user_coupon:" . $info->id);
        return $result;
    }


    /*
    * save
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
    */
    public static function save($info,$refresh_redis=0)
    {
        $result = $info->save();

        //进行redis的刷新
        if($refresh_redis==1){
            RedisManager::del("user_coupon:" . $info->id);
        }
        return $result;
    }


    /*
     * getInfoByLevel
     *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        $level_arr = explode(',', $level);

        $info->status_str = Utils::COMMON_STATUS_VAL[$info->status];

        //0:
        if (in_array('0', $level_arr)) {

        }
        //1:
        if (in_array('1', $level_arr)) {

        }
        //2:
        if (in_array('2', $level_arr)) {

        }

        //X:        脱敏
        if (strpos($level, 'X') !== false) {

        }
        //Y:        压缩，去掉content_html等大报文信息
        if (strpos($level, 'Y') !== false) {
            unset($info->content_html);
            unset($info->seq);
            unset($info->status);
            unset($info->updated_at);
            unset($info->deleted_at);
        }
        //Z:        预留
        if (strpos($level, 'Z') !== false) {

        }


        return $info;
    }

    /*
     * getListByCon
     *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new UserCoupon();

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        if (array_key_exists('ids_arr', $con_arr) && !empty($con_arr['ids_arr'])) {
            $infos = $infos->wherein('id', $con_arr['ids_arr']);
        }

    
        if (array_key_exists('id', $con_arr) && !Utils::isObjNull($con_arr['id'])) {
            $infos = $infos->where('id', '=', $con_arr['id']);
        }
    
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }
    
        if (array_key_exists('coupon_id', $con_arr) && !Utils::isObjNull($con_arr['coupon_id'])) {
            $infos = $infos->where('coupon_id', '=', $con_arr['coupon_id']);
        }
    
        if (array_key_exists('received_at', $con_arr) && !Utils::isObjNull($con_arr['received_at'])) {
            $infos = $infos->where('received_at', '=', $con_arr['received_at']);
        }
    
        if (array_key_exists('valid_end_time', $con_arr) && !Utils::isObjNull($con_arr['valid_end_time'])) {
            $infos = $infos->where('valid_end_time', '=', $con_arr['valid_end_time']);
        }
    
        if (array_key_exists('valid_status', $con_arr) && !Utils::isObjNull($con_arr['valid_status'])) {
            $infos = $infos->where('valid_status', '=', $con_arr['valid_status']);
        }
    
        if (array_key_exists('used_status', $con_arr) && !Utils::isObjNull($con_arr['used_status'])) {
            $infos = $infos->where('used_status', '=', $con_arr['used_status']);
        }
    
        if (array_key_exists('used_at', $con_arr) && !Utils::isObjNull($con_arr['used_at'])) {
            $infos = $infos->where('used_at', '=', $con_arr['used_at']);
        }
    
        if (array_key_exists('order_id', $con_arr) && !Utils::isObjNull($con_arr['order_id'])) {
            $infos = $infos->where('order_id', '=', $con_arr['order_id']);
        }
    
        if (array_key_exists('seq', $con_arr) && !Utils::isObjNull($con_arr['seq'])) {
            $infos = $infos->where('seq', '=', $con_arr['seq']);
        }
    
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
    
        if (array_key_exists('created_at', $con_arr) && !Utils::isObjNull($con_arr['created_at'])) {
            $infos = $infos->where('created_at', '=', $con_arr['created_at']);
        }
    
        if (array_key_exists('updated_at', $con_arr) && !Utils::isObjNull($con_arr['updated_at'])) {
            $infos = $infos->where('updated_at', '=', $con_arr['updated_at']);
        }
    
        if (array_key_exists('deleted_at', $con_arr) && !Utils::isObjNull($con_arr['deleted_at'])) {
            $infos = $infos->where('deleted_at', '=', $con_arr['deleted_at']);
        }
    
        //排序设定
        if (array_key_exists('orderby', $con_arr) && is_array($con_arr['orderby'])) {
            $orderby_arr = $con_arr['orderby'];
            //例子，传入数据样式为'status'=>'desc'
            if (array_key_exists('status', $orderby_arr) && !Utils::isObjNull($orderby_arr['status'])) {
                $infos = $infos->orderby('status', $orderby_arr['status']);
            }
            //如果传入random，代表要随机获取
            if (array_key_exists('random', $orderby_arr) && !Utils::isObjNull($orderby_arr['random'])) {
                $infos = $infos->inRandomOrder();
            }
        }
        $infos = $infos->orderby('seq', 'desc')->orderby('id', 'desc');

        //分页设定
        if ($is_paginate) {
            $page_size = Utils::PAGE_SIZE;
            //如果con_arr中有page_size信息
            if (array_key_exists('page_size', $con_arr) && !Utils::isObjNull($con_arr['page_size'])) {
                $page_size = $con_arr['page_size'];
            }
            $infos = $infos->paginate($page_size);
        }
        else {
            //如果con_arr中有page_size信息 2019-10-08优化，可以不分页也获取多条数据
            if (array_key_exists('page_size', $con_arr) && !Utils::isObjNull($con_arr['page_size'])) {
                $page_size = $con_arr['page_size'];
                $infos = $infos->take($page_size);
            }
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * setInfo
     *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
     */
    public static function setInfo($info, $data,$level=0)
    {
        //0级信息配置
        if( $level >= 0){
        }

        //1级信息配置
        if( $level >= 1){
        }

        //2级信息配置
        if( $level >= 2){
        }

        
        if (array_key_exists('id', $data)) {
                $info->id = $data['id'];
            }
        
        if (array_key_exists('user_id', $data)) {
                $info->user_id = $data['user_id'];
            }
        
        if (array_key_exists('coupon_id', $data)) {
                $info->coupon_id = $data['coupon_id'];
            }
        
        if (array_key_exists('received_at', $data)) {
                $info->received_at = $data['received_at'];
            }
        
        if (array_key_exists('valid_end_time', $data)) {
                $info->valid_end_time = $data['valid_end_time'];
            }
        
        if (array_key_exists('valid_status', $data)) {
                $info->valid_status = $data['valid_status'];
            }
        
        if (array_key_exists('used_status', $data)) {
                $info->used_status = $data['used_status'];
            }
        
        if (array_key_exists('used_at', $data)) {
                $info->used_at = $data['used_at'];
            }
        
        if (array_key_exists('order_id', $data)) {
                $info->order_id = $data['order_id'];
            }
        
        if (array_key_exists('seq', $data)) {
                $info->seq = $data['seq'];
            }
        
        if (array_key_exists('status', $data)) {
                $info->status = $data['status'];
            }
        
        if (array_key_exists('created_at', $data)) {
                $info->created_at = $data['created_at'];
            }
        
        if (array_key_exists('updated_at', $data)) {
                $info->updated_at = $data['updated_at'];
            }
        
        if (array_key_exists('deleted_at', $data)) {
                $info->deleted_at = $data['deleted_at'];
            }
        
        return $info;
    }

    /*
    * 统一封装数量操作，部分对象涉及数量操作，例如产品销售，剩余数等，统一通过该方法封装
    *
    * By Auto CodeCreator
    *
    * 2020-01-03 02:14:51
    *
    * @param  id：对象id item：操作对象 num：加减数值
    */
    public static function setNum($id, $item, $num)
    {
        $info = self::getById($id);
        switch ($item) {
            case "show_num":
                $info->show_num = $info->show_num + $num;
            break;
            case "left_num":
                $info->left_num = $info->left_num + $num;
                break;
            case "send_num":
                $info->send_num = $info->send_num + $num;
                break;
        }
        $info = self::save($info);
        return $info;
    }

    /*
    * 获取最近的一条信息
    *
    * By TerryQi
    *
    */
    public static function getLatest()
    {
        $info = self::getListByCon(['status' => '1'], false)->first();
        return $info;
    }

}

