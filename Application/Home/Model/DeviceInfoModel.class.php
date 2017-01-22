<?php
//Author: Hu Tong
//2016/07/22

namespace Home\Model;
use Think\Exception;
use Think\Model;
use Home\Common\Util;

class DeviceInfoModel extends Model {

	public function getAllDevices() {

		// $devices = M('device_info')->field("*")->select();
		$sql = "SELECT `device_sn`,`device_des`,`guarantee_period`,`date_production`,`date_added`,`type_name` FROM `mcc_device_info` di LEFT JOIN `mcc_device_type` dt ON di.device_type_id = dt.type_id";
		$devices = $this->db->query($sql);

		return $devices;
	}

	public function registerDevice($data) {
	    //添加质保期
	    $data['guarantee_period'] = 12;
        //device添加日期
        $data['date_added'] = date('Y-m-d H:i:s');

        $result = M('device_info')->data($data)->add();
        return $result;
    }

    public function deleteDevice($data) {
        $result = false;
        if($data != null) {
            if(is_array($data)) {
                $ids = $data;
            } else {
                $ids = explode(",", $data);
            }
            $sql = "DELETE FROM mcc_device_info WHERE (";
            for ($i = 0; $i<sizeof($ids); $i++) {
                if( $i == sizeof($ids)-1) {
                    $sql .= " device_sn = '" . $ids[$i] . "' );";
                } else {
                    $sql .= " device_sn = '" . $ids[$i] . "' || ";
                }
            }
            try{
                $result = $this->db->execute($sql) > 0;
            } catch (Exception $exception){
                $result = false;
            }

        }
        return $result;
    }

    public function getInstallDevice($deviceID){
        $sql = "SELECT di.device_sn, di.date_production, di.date_added,dt.type_name, c.fullname, c.address FROM `mcc_device_info` di LEFT JOIN mcc_device_type dt ON (dt.type_id = di.device_type_id) LEFT JOIN mcc_device_owner do ON (di.device_sn = do.device_sn) LEFT JOIN mcc_customer c ON(do.customer_id = c.customer_id ) where di.device_sn= '$deviceID'";
        //AND di.device_sn IN (SELECT `device_sn` FROM `mcc_device_owner`) 如果要求查找出来的device必须拥有owner，加上前面条件
        return $this->db->query($sql);
    }


    public function searchUnbindedDevice($searchContent) {
        $sql = "SELECT di.device_sn FROM `mcc_device_info` di where(di.device_sn like '%" . $searchContent . "%' AND di.device_sn NOT IN (SELECT `device_sn` FROM `mcc_device_owner`)) ORDER BY di.date_added desc limit 0,5";
        return $this->db->query($sql);
    }


    public function searchUninstalledDevice($searchContent) {

        $sql = "SELECT di.device_sn, di.date_production, di.date_added,dt.type_name, c.fullname, c.address FROM `mcc_device_info` di LEFT JOIN mcc_device_type dt ON (dt.type_id = di.device_type_id) LEFT JOIN mcc_device_owner do ON (di.device_sn = do.device_sn) LEFT JOIN mcc_customer c ON(do.customer_id = c.customer_id ) where(di.device_sn like '%" . $searchContent . "%' AND di.device_sn NOT IN (SELECT `device_sn` FROM `mcc_device_install`)) ORDER BY di.date_added desc limit 0,5";
        //AND di.device_sn IN (SELECT `device_sn` FROM `mcc_device_owner`) 如果要求查找出来的device必须拥有owner，加上前面条件
        return $this->db->query($sql);
    }
    /**
     * 检查机器是否被绑定，如果已绑定，返回绑定者的姓名
     * @param $deviceID
     * @return mixed
     */
    public function checkBind($deviceID) {
        $sql = "select c.fullname from mcc_device_owner do LEFT JOIN mcc_device_info di ON (di.device_sn = do.device_sn) LEFT JOIN mcc_customer c ON (c.customer_id = do.customer_id) WHERE (di.device_sn = '" . $deviceID . "' )";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * 绑定之前，必须进行是否绑定的检查（checkBind($deviceID)），
     * @param $deviceID
     * @param $customerID
     */
    public function bindDevice($deviceID, $customerID){
	    //TODO:改变device的bind_owner状态和添加mcc_device_owner的操作必须为原子
//        $data = array();
//        $data['bind_owner'] = 1;
//        $num = M('device_info')->where("device_sn = '" . $deviceID . "'")->save($data);

        $data2 = array();
        $data2['customer_id'] = $customerID;
        $data2['device_sn'] = $deviceID;
        $num2 = M("device_owner")->add($data2);
        if( $num2 > 0) {
            return true;
        } else {
            return false;
        }
    }
}