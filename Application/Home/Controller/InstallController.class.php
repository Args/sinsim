<?php
/**
 * Created by PhpStorm.
 * User: Hu Tong
 * Date: 1/3/2017
 * Time: 10:15 AM
 */
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class InstallController extends Controller {
    public function index(){
        $statuses = M('install_status')->field("status_id, status_name")->where("enable=1")->select();
        $this->assign('statuses', $statuses);

        $machineTypes = M('device_type')->field("type_id, type_name")->where("enable=1")->select();
        $this->assign('machineTypes', $machineTypes);

        $installRecords = D('device_install')->getInstallRecords();
        $this->assign('installRecords', $installRecords);

        $this->display();
    }

    public function create(){

        $basic = D('device_install')->getEnabledBasicItemForInstallMenu();
//        print_r(json_encode($result));
        $this->assign('basicItems', $basic);

        $others = D('device_install')->getEnableOtherItemForInstallMenu();

        $this->assign('otherItems',$others);

        $servicePerson = D('customer')->getAllServicePersons();

        $this->assign('servicePersons',$servicePerson);

        $this->display();
    }

    public function modifyRecordItem(){
        $basic = D('device_install')->getEnabledBasicItem();
//        print_r(json_encode($result));
        $this->assign('basicItems', $basic);

        $others = D('device_install')->getEnableOtherItem();

        $this->assign('otherItems',$others);
//        print_r(($others));
        $this->display();
    }

    public function InstallDetail($installID, $deviceID) {

        $this->assign("installID",$installID);
        $deviceInfo = D('device_info')->getInstallDevice($deviceID);
        $this->assign("deviceData",$deviceInfo);

        $installInfo = M('device_install')->where("install_id = '$installID' ")->find();
        $this->assign("installInfo",$installInfo);

        $statuses = M('install_status')->field("status_id, status_name")->where("enable=1")->select();
        $this->assign('statuses', $statuses);

        $servicePerson = D('customer')->getAllServicePersons();
        $this->assign('servicePersons',$servicePerson);

        $basic = D('device_install')->getBasicItemByInstallID($installID);
//        print_r(json_encode($result));
        $this->assign('basicItems', $basic);

        $others = D('device_install')->getOtherItemByInstallID($installID);

        $this->assign('otherItems',$others);

        $this->display();
    }

    public function submitInstallOrder($deviceID, $spID, $basicItems, $otherItems, $installDate) {
        $map['device_sn'] = $deviceID;
        if(M('device_install')->where($map)->find()) {
            $this->error("设备已安装调试，请确认！");
        } else {
            $result = D("device_install")->submitInstallOrder($deviceID, $spID, $basicItems, $otherItems, $installDate);
            if($result) {
                $this->success("成功！");
            } else {
                $this->error("失败！");
            }
        }
    }

    public function filterInstallMenu() {
        $condition['filter_id'] = $_POST['filter_id'];
        $condition['filter_type'] = $_POST['filter_type'];
        $condition['filter_customer'] = $_POST['filter_customer'];
        $condition['filter_status']= $_POST['filter_status'];
        $condition['filter_service']= $_POST['filter_service'];
        $condition['filter_begin_time']= $_POST['filter_begin_time'];
        $condition['filter_end_time']= $_POST['filter_end_time'];
        $installMenus = D('device_install')->getInstallMenusByCondition($condition);
        $this->success($installMenus);
    }


    public function ajaxAddBasicItem($name, $mark) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['basic_item_name'] = $name;
        $result = M('install_basic_item')->add($data);
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('添加失败！');
        }
    }

    public function ajaxModifyBasicItem($id, $name) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['basic_item_id'] = $id;
        $data['basic_item_name'] = $name;
        $result = M('install_basic_item')->data($data)->save();
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('修改失败！');
        }
    }

    public function ajaxDeleteBasicItem($id) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['basic_item_id'] = $id;
        $data['basic_item_enable'] = 0;
        $result = M('install_basic_item')->data($data)->save();
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('删除失败！');
        }
    }

    //添加其他信息项
    public function ajaxAddItem($typeId, $name, $mark) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['item_name'] = $name;
        $data['item_type'] = $typeId;
        $result = M('install_item')->add($data);
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('添加失败！');
        }
    }

    public function ajaxModifyItem($id, $name) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['item_id'] = $id;
        $data['item_name'] = $name;
        $result = M('install_item')->data($data)->save();
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('修改失败！');
        }
    }

    public function ajaxDeleteItem($id) {
        //TODO:以后可能需要加上信息项的描述信息
        $data['item_id'] = $id;
        $data['enable'] = 0;
        $result = M('install_item')->data($data)->save();
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('删除失败！');
        }
    }

    public function ajaxAddType($name){
        //TODO:以后可能需要加上信息项的描述信息
        $data['item_type_name'] = $name;
        $result = M('install_item_type')->add($data);
        if($result != null) {
            $this->success("成功！");
        } else {
            $this->error('添加失败！');
        }
    }

//    public function testTrans() {
//        $tran_result = true;
//        $trans = M("device_install");
//        $trans->startTrans();
//        try {
//            for ($i = 0; $i<3; $i++) {
//                $data['install_id'] = $i;
//                $data['customer_id'] = 1;
//                $result = $trans->data($data)->add();
//                if(!$result || $i>1) {
//                    throw new Exception();
//                }
//            }
//        } catch (Exception $e) {
//            $tran_result = false;
//        }
//        if(!$tran_result) {
//            $trans->rollback();
//            print_r("Failed!");
//        } else {
//            $trans->commit();
//            print_r("Success!");
//
//        }
//    }
}