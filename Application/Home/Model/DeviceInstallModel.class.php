<?php
/**
 * Created by PhpStorm.
 * User: PC-LHF
 * Date: 2017-01-03
 * Time: 11:16
 */

namespace Home\Model;

use Think\Model;
use Home\Common\InstallItem;
use Home\Common\Util;
use Think\Exception;

class DeviceInstallModel extends Model
{

    /**
     * 获取调试的基本信息项
     * @return mixed
     */
    public function getEnabledBasicItem()
    {
        $sql = "SELECT basic_item_id, basic_item_name FROM `mcc_install_basic_item` WHERE basic_item_enable =1 ORDER BY basic_item_id ASC";
        return $this->db->query($sql);
    }

    /**
     * 通过install ID获取调试的基本信息项
     * @return mixed
     */
    public function getBasicItemByInstallID($installID)
    {
        $sql = "SELECT ibir.*, ibi.basic_item_name FROM mcc_install_basic_item_record ibir 
                LEFT JOIN mcc_install_basic_item ibi ON (ibir.basic_item_id = ibi.basic_item_id) WHERE ibir.install_id = '$installID'  ORDER BY basic_item_id ASC";

        return $this->db->query($sql);
    }


    /**
     * 获取其他创建的信息项（平绣、特种系列、毛巾绣等）
     */
    public function getEnableOtherItem()
    {
        $result = array();
        $typeSQL = "SELECT item_type_id, item_type_name FROM mcc_install_item_type WHERE enable =1";
        $types = $this->db->query($typeSQL);
        for ($i = 0; $i < sizeof($types); $i++) {
            $itemSQL = "SELECT * FROM mcc_install_item WHERE item_type=" . $types[$i]['item_type_id'] . " AND enable=1";
            $item = new InstallItem();
            $item->setName($types[$i]['item_type_name']);
            $item->setTypeId($types[$i]['item_type_id']);
            $item->setItems($this->db->query($itemSQL));
            $result[$i] = $item;
//          $result[$types[$i]['item_type_name']] = $this->db->query($itemSQL);
        }
        return $result;
    }

    /**
     * 通过调试单号获取其他创建的信息项（平绣、特种系列、毛巾绣等）
     */
    public function getOtherItemByInstallID($installID)
    {
        $result = array();
        $typeSQL = "SELECT item_type_id, item_type_name FROM mcc_install_item_type WHERE enable =1";
        $types = $this->db->query($typeSQL);
        for ($i = 0; $i < sizeof($types); $i++) {
            $itemSQL = "SELECT iir.*,ii.item_name FROM mcc_install_item_record iir
                        LEFT JOIN mcc_install_item ii ON (ii.item_id = iir.item_id)
                        LEFT JOIN mcc_install_item_type iit ON (iit.item_type_id = ii.item_type) 
                        WHERE iit.item_type_id=" . $types[$i]['item_type_id'] . " AND iir.install_id='$installID'";
            $item = new InstallItem();
            $item->setName($types[$i]['item_type_name']);
            $item->setTypeId($types[$i]['item_type_id']);
//            $item->setAddMenuStatus(0);//默认不加入menu
            $temp = $this->db->query($itemSQL);
            if(!$temp) break;
            $item->setItems($temp);
            $result[$i] = $item;
//          $result[$types[$i]['item_type_name']] = $this->db->query($itemSQL);
        }
        return $result;
    }
    /**
     * 获取调试的基本信息项, 并加上是否加如调试清单的状态，默认是disable
     * @return mixed
     */
    public function getEnabledBasicItemForInstallMenu()
    {
        $sql = "SELECT basic_item_id, basic_item_name FROM `mcc_install_basic_item` WHERE basic_item_enable =1 ORDER BY basic_item_id ASC";
        $result = $this->db->query($sql);
        for ($i = 0; $i < sizeof($result); $i++) {
            $result[$i]['add_menu'] = 0;
        }
        return $result;
    }

    /**
     * 获取其他创建的信息项（平绣、特种系列、毛巾绣等），并加上是否加如调试清单的状态，默认是disable（0）
     */
    public function getEnableOtherItemForInstallMenu()
    {
        $result = array();
        $typeSQL = "SELECT item_type_id, item_type_name FROM mcc_install_item_type WHERE enable =1";
        $types = $this->db->query($typeSQL);
        for ($i = 0; $i < sizeof($types); $i++) {
            $itemSQL = "SELECT * FROM mcc_install_item WHERE item_type=" . $types[$i]['item_type_id'] . " AND enable=1";
            $item = new InstallItem();
            $item->setName($types[$i]['item_type_name']);
            $item->setTypeId($types[$i]['item_type_id']);
//            $item->setAddMenuStatus(0);//默认不加入menu
            $temp = $this->db->query($itemSQL);
            for ($j = 0; $j < sizeof($temp); $j++) {
                $temp[$j]['add_menu'] = 0;
            }
            $item->setItems($temp);
            $result[$i] = $item;
//          $result[$types[$i]['item_type_name']] = $this->db->query($itemSQL);
        }
        return $result;
    }

    public function submitInstallOrder($deviceID, $spID, $basicItems, $otherItems, $installDate)
    {
        $util = new Util();
        //B01176117376
        $orderNum = "I" . $util->createOrderNm();
        $tran_result = true;
        $trans1 = M("device_install");
        $trans2 = M("install_basic_item_record");
        $trans3 = M("install_item_record");

        //多表操作而且数据互相依赖，必须用事务来处理，必须用InnoDB,不用MyISAM
        $trans1->startTrans();
        $trans2->startTrans();
        $trans3->startTrans();
        try {
            //device install
            $data['install_id'] = $orderNum;//调试的单号，前面加上“I”
            $data['device_sn'] = $deviceID;//设备ID
            $data['customer_id'] = $spID;//售后人员ID
            $data['install_plan_date'] = $installDate;//计划调试安装时间
            $data['create_date'] = date("Y-m-d H:i:s");//计划调试安装时间,'H'表示24小时制
            $data['status'] = 0;//待调试状态
            $tran_result1 = $trans1->data($data)->add();
            if (!$tran_result1) {
                throw new Exception();
            }

            //基本items
            $basicIDs = explode('|', $basicItems);
            $sql_basic = "INSERT INTO mcc_install_basic_item_record (record_id, basic_item_id, install_id, basic_item_value) VALUES ";
            for ($i = 0; $i < sizeof($basicIDs); $i++) {
                if ($i != sizeof($basicIDs) - 1) {
                    $sql_basic = $sql_basic . " ( " . " '', " . $basicIDs[$i] . ", '" . $orderNum . "', " . "0" . " ), ";
                } else {
                    $sql_basic = $sql_basic . " ( " . " '', " . $basicIDs[$i] . ", '" . $orderNum . "', " . "0" . " )";
                }
            }
            //print_r($sql_basic);
            $tran_result2 = $this->db->execute($sql_basic);
            if (!$tran_result2) {
                throw new Exception();
            }

            //other items
            $otherIDs = explode('|', $otherItems);
            $sql_other = "INSERT INTO mcc_install_item_record (record_id, item_id, install_id, item_value) VALUES ";
            for ($i = 0; $i < sizeof($otherIDs); $i++) {
                if ($i != sizeof($otherIDs) - 1) {
                    $sql_other = $sql_other . " ( " . " '', " . $otherIDs[$i] . ", '" . $orderNum . "', " . "0" . " ), ";
                } else {
                    $sql_other = $sql_other . " ( " . " '', " . $otherIDs[$i] . ", '" . $orderNum . "', " . "0" . " ) ";
                }
            }
            //print_r($sql_other);
            $tran_result3 = $this->db->execute($sql_other);
            if (!$tran_result3) {
                throw new Exception();
            }

        } catch (Exception $e) {
            $tran_result = false;
        }

        if (!$tran_result) {
            $trans1->rollback();
            $trans2->rollback();
            $trans3->rollback();
        } else {
            $trans1->commit();
            $trans2->commit();
            $trans3->commit();
        }
        return $tran_result;
    }

    public function getInstallRecords()
    {
        $sql = "SELECT di.install_id, di.device_sn, dt.type_name, c.fullname, cus.fullname AS service_name, ist.status_name, di.install_plan_date FROM mcc_device_install di LEFT JOIN mcc_device_info dif ON (di.device_sn = dif.device_sn) LEFT JOIN mcc_device_type dt ON (dif.device_type_id = dt.type_id) LEFT JOIN mcc_device_owner do ON (di.device_sn = do.device_sn) LEFT JOIN mcc_customer c ON (do.customer_id = c.customer_id) LEFT JOIN mcc_customer cus ON (di.customer_id = cus.customer_id) LEFT JOIN mcc_install_status ist ON (ist.status_id = di.status)";
        $result = $this->db->query($sql);
        //由于这里的客户名称和调试员都在customer表中，并且需要显示在同一列中，所以在返回前需要先进行处理
        //将调试员的column名称改为service_name
        return $result;
    }

    public function getInstallMenusByCondition($condition)
    {
        $sql = "
        SELECT di.install_id, di.device_sn, dt.type_name, c.fullname, cus.fullname AS service_name, ist.status_name, di.install_plan_date 
        FROM mcc_device_install di 
        LEFT JOIN mcc_device_info dif ON (di.device_sn = dif.device_sn) 
        LEFT JOIN mcc_device_type dt ON (dif.device_type_id = dt.type_id) 
        LEFT JOIN mcc_device_owner do ON (di.device_sn = do.device_sn) 
        LEFT JOIN mcc_customer c ON (do.customer_id = c.customer_id) 
        LEFT JOIN mcc_customer cus ON (di.customer_id = cus.customer_id) 
        LEFT JOIN mcc_install_status ist ON (ist.status_id = di.status)
         ";

        $conditionSQL = " WHERE 1 ";
        if ($condition['filter_id']) {
            $id = $condition['filter_id'];
            $str = "AND di.install_id like '%$id%' ";
            $conditionSQL .= $str;
        }

        if ($condition['filter_customer']) {
            $customer = $condition['filter_customer'];
            $str .= "AND c.fullname like '%$customer%' ";
            $conditionSQL .= $str;
        }
        if ($condition['filter_service']) {
            $service = $condition['filter_service'];
            $str .= "AND cus.fullname like '%$service%' ";
            $conditionSQL .= $str;
        }

        if ($condition['filter_type']) {
            $typeID = $condition['filter_type'];
            $str = "AND dt.type_id = '$typeID' ";
            $conditionSQL .= $str;
        }

        if ($condition['filter_status']) {
            $statusID = $condition['filter_status'];
            $str = "AND ist.status_id = '$statusID' ";
            $conditionSQL .= $str;
        }

        if ($condition['filter_begin_time']) {
            $beginDate = $condition['filter_begin_time'];
            $str = "AND di.install_plan_date >= '$beginDate' ";
            $conditionSQL .= $str;
        }
        if ($condition['filter_end_time']) {
            $endDate = $condition['filter_end_time'];
            $str = "AND di.install_plan_date <= '$endDate' ";
            $conditionSQL .= $str;
        }

        $sql .= $conditionSQL;
//        print_r($sql);
        $installMenus = $this->db->query($sql);
        return $installMenus;
    }
}