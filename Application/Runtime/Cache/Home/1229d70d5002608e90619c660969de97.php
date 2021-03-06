<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en"
      xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns="http://www.w3.org/1999/html" xmlns:v-show="http://www.w3.org/1999/xhtml"
      xmlns:v-click="http://www.w3.org/1999/xhtml" xmlns:v-model="http://www.w3.org/1999/xhtml"
      xmlns:vo-on="http://www.w3.org/1999/xhtml" xmlns:v-for="http://www.w3.org/1999/xhtml" >
<head >
    <title >故障任务分配</title >
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css" >
    <script type="text/javascript" src="/service/Public/js/vue.js" ></script >
    <script type="text/javascript" src="/service/Public/js/bootstrap.min.js" ></script >
    <script type="text/javascript" src="/service/Public/js/jquery-3.1.1.min.js" ></script >

    <style >
        table thead tr th {
            text-align: center
        }

        table tbody tr td {
            text-align: center;
            height: 48px;
        }
    </style >
</head >

<body >
<div class="container" >
    <div class="page-header" >
        <h1 style="text-align: left; margin-left: 10px" >故障信息</h1 >
    </div >

    <div class="container-fluid" id="app" >
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <div style="font-size: 20px" v-on:click="showOrHideItems" >基本信息
                                                                           <!--<span class="badge" style="vertical-align: middle" >-->
                                                                           <!--3-->
                                                                           <!--</span >-->
                </div >
            </div >
            <div class="table-responsive" v-show="toggle" >
                <form class="form-horizontal" role="form"
                      style="margin-top: 30px;margin-left:-40px; margin-right: 20px; font-size: 16px" >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >故障编号:</label >
                        <div class="col-sm-10" >
                            <b ><input type="text" disabled="true" class="form-control"
                                       v-model="submitModel.problem_id"
                                       style="height: 48px;" />
                            </b >
                        </div >
                    </div >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >设备ID:</label >
                        <div class="col-sm-10" >
                            <label disabled="true" type="text" class="form-control"
                                   style="height: 48px;float:right;" ><?php echo ($problemdata['device_sn']); ?></label >
                        </div >
                    </div >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >设备名称:</label >
                        <div class="col-sm-10" >
                            <label disabled="true" type="text" class="form-control"
                                   style="height: 48px;float:right;" ><?php echo ($problemdata['device_des']); ?></label >
                        </div >
                    </div >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >设备拥有者:</label >
                        <div class="col-sm-10" >
                            <label disabled="true" type="text" class="form-control"
                                   style="height: 48px; float:right" ><?php echo ($problemdata['owner_name']); ?></label >
                        </div >
                    </div >
                </form >
            </div >

        </div >

        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <div style="font-size: 20px" v-on:click="showOrHideItems2" >故障描述信息
                                                                            <!--<span class="badge" style="vertical-align: middle" >-->
                                                                            <!--3-->
                                                                            <!--</span >-->
                </div >
            </div >
            <div class="table-responsive" v-show="toggle2" >
                <form class="form-horizontal" role="form"
                      style="margin-top: 30px;margin-left:-40px; margin-right: 20px; font-size: 16px" >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >故障描述:</label >
                        <div class="col-sm-10" >
                            <label disabled="true" type="text" class="form-control"
                                   style="height: 80px; text-align:left;text-wrap: normal" ><?php echo ($problemdata['problem_des']); ?></label >
                        </div >
                    </div >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >音频文件描述:</label >
                        <div class="col-sm-10" v-if="problemVoices.length > 0" >
                            <div v-for="item in problemVoices"
                                 style="float:inherit;align-items: center; align-content: center;alignment: center;horiz-align: center;" >
                                <div style="float: left; border: solid;  border-width: 2px;border-color: #d9edf7;margin: 10px" >
                                    <img src="../../../Resource/Problem/Des_Voice/voice.png" class="img-responsive"
                                         alt="Chania"
                                         style="width:150px;height: 100px; margin: 3px;" >
                                    </img>
                                </div >
                            </div >
                        </div >
                        <div v-else style="color: red;vertical-align: bottom; margin-top: 10px;" >
                            <b >无</b >
                        </div >
                    </div >
                    <div class="form-group" >
                        <label class="control-label col-sm-2" >故障图片:</label >
                        <div class="col-sm-10" v-if="problemPictures.length > 0" >
                            <!--<table>-->
                            <!--<tr>-->
                            <!--<td v-for="itemPicture in problemPictures">-->
                            <!--<div style="border: solid; width:310px;height: 210px; border-width: 2px;border-color: #d9edf7;margin: 10px">-->
                            <!--<img v-bind:src="itemPicture.imageUrl" class="img-responsive" alt="Chania"-->
                            <!--style="width:300px;height: 200px; margin: 3px;">-->
                            <!--</img>-->
                            <!--</div>-->
                            <!--</td>-->
                            <!--</tr>-->
                            <!--</table>-->

                            <div v-for="itemPicture in problemPictures"
                                 style="float:inherit;align-items: center; align-content: center;alignment: center;horiz-align: center;" >
                                <div style="float: left; border: solid;  border-width: 2px;border-color: #d9edf7;margin: 10px" >
                                    <img v-bind:src="itemPicture.image" class="img-responsive" alt="Chania"
                                         style="width:300px;height: 200px; margin: 3px;" >
                                    </img>
                                </div >
                            </div >

                        </div >
                        <div v-else style="color: red;vertical-align: bottom; margin-top: 10px;" >
                            <b >无</b >
                        </div >
                    </div >
                </form >
            </div >

        </div >

        <div >
            <div class="panel panel-primary" >
                <div class="panel-heading" >
                    <h2 class="panel-title" style="font-size: 20px" >分配维护人员</h2 >
                </div >
                <div class="table-responsive well well-sm" >
                    <select v-model="submitModel.staff_id" class="form-control" placeholder="分配维护人员"
                            style="height: 48px; font-size: 16px; font-weight: bold;" >
                        <option v-bind:value="option.value" v-for="option in staffOptions"
                                style="float:right; font-size: 16px; margin-top: 5px;margin-bottom: 5px" >{{option.text}}
                        </option >
                    </select >
                </div >
            </div >

            <div class="panel panel-primary" >
                <div class="panel-heading" >
                    <h2 class="panel-title" style="font-size: 20px" >维修状态</h2 >
                </div >
                <div class="table-responsive well well-sm" >
                    <select class="form-control" v-model="submitModel.status_id"
                            style="height: 48px; font-size: 16px; font-weight: bold;" >
                        <option v-for="option in statusOptions" v-bind:value="option.value"
                                style="float:right; font-size: 16px; margin-top: 5px;margin-bottom: 5px" >
                            {{ option.text }}
                        </option >
                    </select >
                </div >
            </div >

            <div class="col-sm-offset-2 col-sm-10" style="text-align: right;margin-top:20px;margin-bottom:20px;" >
                <button type="button" class="btn btn-primary" v-on:click="submit" style="width: 100px; height: 36px" >
                    提交
                </button >
            </div >


        </div >
    </div >

</div >

</body >
</html >
<script >
    window.onload = function () {
        app.statusOptions = JSON.parse('<?php echo json_encode($allStatus);?>');
        app.staffOptions = JSON.parse('<?php echo json_encode($allStaff);?>');

        app.submitModel.problem_id = '<?php echo ($problemdata["problem_id"]); ?>';
        app.submitModel.staff_id = '<?php echo ($problemdata["service_staff_id"]); ?>';
        app.submitModel.status_id = '<?php echo ($problemdata["status"]); ?>';
        app.problemPictures = eval('<?php echo json_encode($problemImages);?>');
        app.problemVoices = eval('<?php echo json_encode($problemVoices);?>');

        //alert(app.problemPictures.length);
    };

    //Div 的不ID不能嵌套访问。如果要定义多个Vue对象，必须保证div的id是独立关系，不能是包含嵌套的关系
    var app = new Vue({
        el: '#app',
        data: {
            voiceImg: '../../../Resource/Problem/Des_Voice/voice.png',
            submitUrl: "<?php echo U('DeviceProblem/ajaxSubmit');?>",
            submitModel: {
                problem_id: "",
                staff_id: "",
                status_id: "",
            },
            toggle: true,
            toggle2: true,
            statusOptions: [],
            staffOptions: [],
            problemPictures: [],
            problemVoices: [],
        },
        methods: {
            showOrHideItems: function () {
                this.toggle = !this.toggle;
            },
            showOrHideItems2: function () {
                this.toggle2 = !this.toggle2;
            },
            submit: function () {
                //this.submitModel.problem_id = document.getElementById("problem_id").innerHTML;

                $.ajax({
                    url: this.submitUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: this.submitModel,
                    success: function (data) {
                        if (data.status) {
                            //forward
                            window.location.href = "<?php echo U('DeviceProblem/Problem');?>";
                        } else {
                            alert(data.info);
                        }
                    },
                    error: function (data) {
                        alert(data.info);
                    }
                })
            },

        },
    });

</script >