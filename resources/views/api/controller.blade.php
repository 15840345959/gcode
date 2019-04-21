<html>

/**
* Created by PhpStorm.
* User:robot
* Date: {{$date_time}}
* Time: 13:29
*/

namespace App\Http\Controllers\Api;


use App\Components\RequestValidator;
use App\Components\{{$model_name}}Manager;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;

class {{$model_name}}Controller
{

    /*
    * 根据id获取信息
    *
    * By Auto CodeCreator
    *
    * {{$date_time}}
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
        ${{$var_name}} = {{$model_name}}Manager::getById($data['id']);
        //补充信息
        if(${{$var_name}}){
            $level = null;
            if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
                $level = $data['level'];
            }
            ${{$var_name}} = {{$model_name}}Manager::getInfoByLevel(${{$var_name}},$level);
        }
        return ApiResponse::makeResponse(true, ${{$var_name}}, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 根据条件获取列表
    *
    * By Auto CodeCreator
    *
    * {{$date_time}}
    */
    public function getListByCon(Request $request)
    {
        $data = $request->all();
        $status = '1';
        //配置条件
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'status' => $status,
        );
        ${{$var_name}}s = {{$model_name}}Manager::getListByCon($con_arr, false);
        foreach (${{$var_name}}s as ${{$var_name}}) {
            ${{$var_name}} = {{$model_name}}Manager::getInfoByLevel(${{$var_name}}, '');
        }

        return ApiResponse::makeResponse(true, ${{$var_name}}s, ApiResponse::SUCCESS_CODE);
    }
}

</html>