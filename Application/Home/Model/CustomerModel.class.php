<?php
//Author: Hu Tong
//2016/07/22

namespace Home\Model;
use Think\Model;
use Home\Common\Util;

class CustomerModel extends Model {
	
	public function getCustomerByID($customerID) {
		$map['customer_id'] =  $customerID;
		$map['status'] = 1;
		$map['approved'] = 1;
		$customer = M('customer')->field("*")->where($map)->find();
		return $customer;
	}

	public function getAllCustomer() {
		$map['status'] = 1;
		$map['approved'] = 1;
        //只选出购买机器的客户,group ID为3
        $map['customer_group_id'] = 3;
		$customers = M('customer')->field("customer_id, fullname, email, telephone, address, date_added")->where($map)->order("date_added desc")->select();

		return $customers;
	}

    public function getAllServicePersons() {
        $map['status'] = 1;
        $map['approved'] = 1;
        //只选出售后人员,group ID为4
        $map['customer_group_id'] = 4;
        $customers = M('customer')->field("customer_id, fullname")->where($map)->order("fullname asc")->select();

        return $customers;
    }

	public function isCustomerRegistered($email) {
		$map['email'] = $email;
		$count = M('customer')->field('*')->where($map)->find();
		if( $count ) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @param $customerID  用户ID
     * @return mixed       绑定的机器
     */
	public function getBindedDevicesByID($customerID) {
        $sql = "SELECT di.device_sn, dt.type_name,di.device_des,di.date_production FROM mcc_device_info di LEFT JOIN mcc_device_type dt ON (di.device_type_id = dt.type_id) WHERE di.device_sn IN (SELECT `device_sn` FROM `mcc_device_owner` WHERE `customer_id` = ";
        $sql .= $customerID . " )";
        $result = $this->db->query($sql);


        return $result;
    }

	public function registerAccount($name, $email, $password) {
		$data['fullname'] = $name;
		$data['email'] = $email;
		$salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 );
		$data['salt'] = $salt;
		$data['password'] = sha1 ( $salt . sha1 ( $salt . sha1 ( $password ) ) );
// 		$data['password'] = md5($password);
		$data['customer_group_id'] = 1;//1 -->普通；2 -->VIP; 3-->代理商
		$util = new Util();
		$data['ip'] = $util->getIP();
		$data['status'] = 1;
		$data['approved'] = 1;
		$data['date_added'] = date("Y-m-d H:i:s");

		$result = M('customer')->data($data)->add();
		if($result != null) {
			return true;
		} else {
			return false;
		}
	}

	public function registerCustomerAccount($data) {

        $salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 );
        $data['salt'] = $salt;
        $data['password'] = sha1 ( $salt . sha1 ( $salt . sha1 ( $data['telephone'] ) ) );
        $util = new Util();
        $data['ip'] = $util->getIP();
        $data['status'] = 1;
        $data['approved'] = 1;
        $data['date_added'] = date("Y-m-d H:i:s");
        $result = M('customer')->data($data)->add();
        if($result != null) {
            return true;
        } else {
            return false;
        }
    }


	public function getCustomerDataByEmailAndPassword($email, $password) {
		// $customer_query = $this->db->query("SELECT * FROM " . C('DB_PREFIX') . "customer WHERE LOWER(email) = '" . utf8_strtolower($email) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $password . "'))))) OR password = '" . md5($password) . "') AND status = '1' AND approved = '1'");
		$map['email'] = $email;
		$customer =  M('customer')->field('salt')->where($map)->find();
		if($customer != null && $customer['salt'] != null) {
			$salt = $customer['salt'];
			$map['password'] = sha1 ( $salt . sha1 ( $salt . sha1 ( $password ) ) );;
			return M('customer')->field('*')->where($map)->find();
		} else {
			return null;
		}
		//$map['password'] = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1($password)))));
 		//$map['password'] = "94eaf37408237877b127334c532a017f99fefb1f";
		// return  $customer_query;
	}
}