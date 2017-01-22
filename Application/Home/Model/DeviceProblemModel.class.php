<?php
/**
 * Created by PhpStorm.
 * User: PC-LHF
 * Date: 2017-01-03
 * Time: 11:16
 */

namespace Home\Model;

use Think\Model;
use Home\Common\Util;

class DeviceProblemModel extends Model
{

    public function getAllProblems()
    {
        $sql = "
        SELECT V.*,mcc_customer.fullname as owner_name FROM 
        (SELECT
        mcc_device_problem.*, 
        mcc_device_owner.customer_id as owner_id,
        mcc_device_info.device_des,
        mcc_device_info.device_type_id,
        mcc_device_type.type_name,
        mcc_customer.fullname as service_staff_name,
        mcc_problem_status.status_name
        FROM
        mcc_device_problem
        LEFT JOIN mcc_device_info ON mcc_device_problem.device_sn = mcc_device_info.device_sn
        LEFT JOIN mcc_device_owner ON mcc_device_owner.device_sn = mcc_device_problem.device_sn
        LEFT JOIN mcc_customer ON mcc_device_problem.service_staff_id = mcc_customer.customer_id
        LEFT JOIN mcc_device_type ON mcc_device_type.type_id = mcc_device_info.device_type_id
        LEFT JOIN mcc_problem_status ON mcc_device_problem.`status` = mcc_problem_status.status_id
        ) as V
        LEFT JOIN mcc_customer ON V.owner_id = mcc_customer.customer_id";

        $problems = $this->db->query($sql);
        return $problems;
    }


    public function getProblemsByCondition($condition)
    {
        $sql = "
        SELECT V.*,mcc_customer.fullname as owner_name FROM 
        (SELECT
        mcc_device_problem.*, 
        mcc_device_owner.customer_id as owner_id,
        mcc_device_info.device_des,
        mcc_device_info.device_type_id,
        mcc_device_type.type_name,
        mcc_customer.fullname as service_staff_name,
        mcc_problem_status.status_name
        FROM
        mcc_device_problem
        LEFT JOIN mcc_device_info ON mcc_device_problem.device_sn = mcc_device_info.device_sn
        LEFT JOIN mcc_device_owner ON mcc_device_owner.device_sn = mcc_device_problem.device_sn
        LEFT JOIN mcc_customer ON mcc_device_problem.service_staff_id = mcc_customer.customer_id
        LEFT JOIN mcc_device_type ON mcc_device_type.type_id = mcc_device_info.device_type_id
        LEFT JOIN mcc_problem_status ON mcc_device_problem.`status` = mcc_problem_status.status_id
        ) as V 
        LEFT JOIN mcc_customer ON V.owner_id = mcc_customer.customer_id 
        ";
        $whereSql = ' WHERE 1 ';
        if ($condition['problem_id']) {
            $str = $condition['problem_id'];
            $whereSql .= "AND V.problem_id like '%$str%' ";
        }
        if ($condition['status_id']) {
            $str = $condition['status_id'];
            $whereSql .= "AND V.status like '%$str%' ";
        }

        if ($condition['type_id']) {
            $str = $condition['type_id'];
            $whereSql .= "AND V.device_type_id like '%$str%' ";
        }

        if ($condition['device_sn']) {
            $str = $condition['device_sn'];
            $whereSql .= "AND V.device_sn like '%$str%' ";
        }

        if ($condition['owner_name']) {
            $str = $condition['owner_name'];
            $whereSql .= "AND mcc_customer.fullname like '%$str%' ";
        }

        if ($condition['service_staff_name']) {
            $str = $condition['service_staff_name'];
            $whereSql .= "AND V.service_staff_name like '%$str%' ";
        }

        if ($condition['start_time']) {
            $str = $condition['start_time'];
            $whereSql .= "AND V.report_time >= '$str' ";
        }

        if ($condition['end_time']) {
            $str = $condition['end_time'];
            $whereSql .= "AND V.report_time <= '$str' ";
        }

        $sql .= $whereSql;
        $problems = $this->db->query($sql);
        return $problems;
    }

    public function getProblemDesImage($problem_id)
    {
        $whereSql = "problem_id='$problem_id'";
        $data = M('problem_des_image')->where($whereSql)->order('sort_order')->select();
        return $data;
    }

    public function getProblemDesVoice($problem_id)
    {
        $whereSql = "problem_id='$problem_id'";
        $data = M('problem_des_voice')->where($whereSql)->order('sort_order')->select();
        return $data;
    }


    public function getServiceStaff()
    {
        $id = "'4'";
        $sql = "SELECT mcc_customer.customer_id,mcc_customer.fullname FROM mcc_customer WHERE mcc_customer.customer_group_id=$id";
        $staffList = $this->db->query($sql);
        return $staffList;
    }

    public function getAllDeviceType()
    {
        $data = M('device_type')->select();
        return $data;
    }

    public function getAllStatus()
    {
//        $sql = "SELECT * FROM mcc_problem_status";
//        $allStatus = $this->db->query($sql);
        $allStatus = M('problem_status')->select();
        return $allStatus;
    }

    public function updateStaffInfo($data)
    {
        $sqlData["problem_id"] = $data["problem_id"];
        $sqlData["service_staff_id"] = $data["staff_id"];
        $sqlData["status"] = $data["status_id"];
        $result = M('device_problem')->data($sqlData)->save();
        return $result;
    }

}