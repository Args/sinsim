<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
    	$users = D("customer")->getAllCustomer();
    	$this->assign("users", $users);
        $this->display();
    }


    public function checkCustomerDevice($customerID){
        $user = M('customer')->field("customer_id, fullname, email, telephone, address")->where("customer_id = " . $customerID)->find();
        $this->assign("user", $user);

        $this->display();
    }

    /**
     * Ajax request API
     */
    public function ajaxAddCustomer(){
        $data = array();
        $data['fullname'] = $_POST['name'];
        $data['email'] = $_POST['email'];
        $data['address'] = $_POST['address'];
        $data['telephone'] = $_POST['phone'];
        $data['customer_group_id'] = $_POST['type'];

        $result = D("customer")->registerCustomerAccount($data);
        if($result) {
            $this->success("成功！");
        } else {
            $this->error("添加失败！");
        }

    }
}