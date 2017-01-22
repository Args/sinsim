<?php
/**
 * Created by PhpStorm.
 * User: ding_you
 * Date: 1/3/2017
 * Time: 10:15 AM
 */
namespace Home\Controller;
use Think\Controller;

class MachineController extends Controller {
    public function index(){
        $devices = D("device_info")->getAllDevices();
        $this->assign("machines", $devices);

        $this->display();
    }

    /**
     * 显示添加机器页面
     */
    public function add() {
        $this->display();
    }

    /**
     * 获取指定用户下面绑定的机器
     *
     * @param $customerID 用户ID
     */
    public function ajaxGetBindedDevices($customerID) {
        $bindedDevices = D('customer')->getBindedDevicesByID($customerID);
        $this->success($bindedDevices);
    }

    /**
     * @param $searchContent
     */
    public function ajaxSearchUnbindedDevice($searchContent){
        if($searchContent == null) {
            //返回空值
            $this->success(array());
        } else{
//            $map['device_sn'] = array('like','%' .$searchContent . '%');
////            $map['bind_owner'] = 0;//“0” -->未绑定
//            $result = M("device_info")->field('device_sn')->where($map)->limit(5)->order("date_added desc")->select();
            $this->success(D('device_info')->searchUnbindedDevice($searchContent));
        }
    }

    public function ajaxSearchUnInstalledDevice($searchContent)
    {
        if ($searchContent == null) {
            //返回空值
            $this->success(array());
        } else {
            $this->success(D('device_info')->searchUninstalledDevice($searchContent));
        }
    }

    public function bindDevice($deviceID, $customerID){
        $result = D('device_info')->checkBind($deviceID);
        if(sizeof($result) > 0) {
            $this->error("机器已被[" . $result[0]['fullname'] . "]绑定，请联系管理员！");
        }

        $result2 = D("device_info")->bindDevice($deviceID, $customerID);
        if($result2) {
            $this->success("绑定成功！");
        } else {
            $this->error("绑定失败！");
        }
    }

    public function unBindDevice($deviceID) {
        $map = array();
        $map['device_sn'] = $deviceID;
        $result = M('device_owner')->where($map)->delete();
        if($result > 0) {
            $this->success("解绑成功！");
        } else {
            $this->success("解绑失败！");
        }
    }

    public function ajaxAdd() {
        $data = array();
        $data['device_sn'] = $_POST['device_id'];
        $data['device_type_id'] = $_POST['device_name'];
        $data['date_production'] = $_POST['device_production'];
        $data['device_des'] = $_POST['device_des'];

        //机器ID判断
        if($data['device_sn'] == null ) {
            $this->error("机器ID不能为空！");
        } else {
            if(strlen($data['device_sn']) <12) {
                $this->error("机器ID小于12位！");
            } else if( strlen($data['device_sn']) > 12) {
                $this->error("机器ID大于12位！");
            }
        }

        //机器名称check
        if($data['device_type_id'] == null) {
            $this->error("机器名称为空");
        }

        //机器出厂日期check
        if($data['date_production'] == null) {
            $this->error("出厂日期为空");
        }

        if(D("device_info")->registerDevice($data)) {
            $this->success("成功！");
        } else {
            $this->error("添加机器失败！");
        }
    }

    public function ajaxDelete() {
        if($_POST != null && $_POST['ids'] != null) {
            if(D("device_info")->deleteDevice($_POST['ids'])) {
                $this->success("成功！");
            } else {
                $this->error("删除机器失败！");
            }

//            $this->error($_POST['ids']);
        } else {
            $this->error("设备号为空！");
        }
    }
}