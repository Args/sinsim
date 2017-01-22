<?php
/**
 * Created by PhpStorm.
 * User: PC-LHF
 * Date: 2017-01-03
 * Time: 11:22
 */

namespace Home\Controller;

use Think\Controller;

class DeviceProblemController extends Controller
{
    #region For Problem page data
    public function Problem()
    {
        //print_r("isset:".isset($_SESSION["problemStatus"]));
        function getAllStatus()
        {
            $statusList = D("device_problem")->getAllStatus();
            for ($i = 0; $i < count($statusList); $i++) {

                $allStatus[$i]["text"] = $statusList[$i]["status_name"];
                $allStatus[$i]["value"] = $statusList[$i]["status_id"];
            }
            return $allStatus;
        }

        function getAllDeviceType()
        {
            $deviceTypeList = D("device_problem")->getAllDeviceType();
            //print_r($deviceTypeList);
            for ($i = 0; $i < count($deviceTypeList); $i++) {
                $alldeviceType[$i]["text"] = $deviceTypeList[$i]["type_name"];
                $alldeviceType[$i]["value"] = $deviceTypeList[$i]["type_id"];
            }
            return $alldeviceType;
        }


        $problems = D("device_problem")->getAllProblems();
        $_SESSION["problems"] = $problems;

        $problemStatus = getAllStatus();
        $_SESSION["problemStatus"] = $problemStatus;

        $alldeviceTypeList = getAllDeviceType();
        $_SESSION["deviceTypeList"] = $alldeviceTypeList;

        $this->assign("allStatus", $problemStatus);
        $this->assign("allDeviceType", $alldeviceTypeList);
        $this->assign("problems", $problems);
        $this->display();
    }

    public function getData()
    {
        $data = $_SESSION["problems"];
        $this->success($data);
    }

    public function ajaxSearch()
    {
        $condition['problem_id'] = $_POST['problem_id'];
        $condition['type_id'] = $_POST['type_id'];
        $condition['status_id'] = $_POST['status_id'];
        $condition['device_sn'] = $_POST['device_sn'];
        $condition['owner_name'] = $_POST['owner_name'];
        $condition['service_staff_name'] = $_POST['service_staff_name'];
        $condition['start_time'] = $_POST['start_time'];
        $condition['end_time'] = $_POST['end_time'];

        $problems = D("device_problem")->getProblemsByCondition($condition);
//        $_SESSION["problems"] = $problems;
//  $this->assign("problems", $problems);
        if (isset($problems)) {
            $this->success($problems);
        } else {
            $this->error("NO data");
        }
    }
    #endregion

    #region For AssignProblem page data
    //initial page data

    public function AssignProblem($index)
    {
        $problemdata = null;
        $problems = $_SESSION["problems"];

        if (isset($problems) && is_array($problems)) {
            if ($index >= 0 && $index < count($problems))
                $problemdata = $problems[$index];
        }

        function getServiceStaff()
        {
            $data = D("device_problem")->getServiceStaff();
            for ($i = 0; $i < count($data); $i++) {

                $dataList[$i]["text"] = $data[$i]["fullname"];
                $dataList[$i]["value"] = $data[$i]["customer_id"];
            }
            return $dataList;
        }

        function getProblemDesImage($problem_id)
        {

            $problemImages = D("device_problem")->getProblemDesImage($problem_id);
//            for ($i = 0; $i < 4; $i++) {
//                $problemImages[$i] = Array
//                (
//                    'id'       => $problem_id,
//                    'imageUrl' => __ROOT__ . '/Resource/Problem/Des/test.jpg',
//                );
//            }

            for ($i = 0; $i < count($problemImages); $i++) {
                if ($problemImages[$i]['image']) {
                    $problemImages[$i]['image'] = __ROOT__ . '/Resource/' . $problemImages[$i]['image'];
                }
            }
            return $problemImages;
        }

        function getProblemDesVoice($problem_id)
        {
            $problemVoices = D("device_problem")->getProblemDesVoice($problem_id);
            for ($i = 0; $i < count($problemVoices); $i++) {
                if ($problemVoices[$i]['voice']) {
                    $problemVoices[$i]['voice'] = __ROOT__ . '/Resource/' . $problemVoices[$i]['voice'];
                }
            }
            return $problemVoices;
        }

        $problemImages = getProblemDesImage($problemdata['problem_id']);
        $this->assign("problemImages", $problemImages);

        $problemVoices = getProblemDesVoice($problemdata['problem_id']);
        $this->assign("problemVoices", $problemVoices);


        $allStatus = $_SESSION["problemStatus"];
        //print_r($allStatus);
        $this->assign("allStatus", $allStatus);
        $serviceStaff = getServiceStaff();
        //print_r($serviceStaff);
        $this->assign("allStaff", $serviceStaff);


        $this->assign("problemdata", $problemdata);

        $this->display();
//        {
//            $url = "{:U('DeviceProblem/AssignProblem')}";
//            //echo "<script language="JavaScript" type="text / javascript"> window.location.back();</script>";
//        }

    }

    public function ajaxSubmit()
    {
        //Dispose the session resource
        function dispose()
        {
            unset($_SESSION["problems"]);
            unset($_SESSION["problemStatus"]);
            unset($_SESSION["deviceTypeList"]);
        }

        dispose();
        $data['problem_id'] = $_POST['problem_id'];
        $data['staff_id'] = $_POST['staff_id'];
        $data['status_id'] = $_POST['status_id'];
        //$this->error(json_encode($data));
        $result = D("device_problem")->updateStaffInfo($data);
        $this->success($result);
    }

    #endregion
}
