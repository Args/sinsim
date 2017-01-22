<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en"
      xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns="http://www.w3.org/1999/html" xmlns:v-show="http://www.w3.org/1999/xhtml"
      xmlns:v-click="http://www.w3.org/1999/xhtml" xmlns:v-model="http://www.w3.org/1999/xhtml"
      xmlns:vo-on="http://www.w3.org/1999/xhtml" xmlns:v-for="http://www.w3.org/1999/xhtml">
<head>
  <title>调试详情</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <!--<link rel="stylesheet" href="/service/Public/css/font-awesome.min.css">-->
  <script type="text/javascript" src="/service/Public/js/vue.js"></script>
  <script type="text/javascript" src="/service/Public/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/service/Public/js/jquery-3.1.1.min.js"></script>

</head>

<body>
<div id="saveChange" class="page-header container">
  <div class="nav navbar-nav navbar-left">
    <h1>调试详情</h1>
  </div>
  <div class="nav navbar-nav navbar-right">
    <i class="fa fa-star"></i>
    <button class="btn btn-primary" v-show="!showSaveBtn" v-on:click="addBasic"
            style="width: 96px; height: 44px; margin-top: 25px; margin-right: 15px; font-size: 16px">
      <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>基本项
    </button>
    <button class="btn btn-primary" v-show="!showSaveBtn" v-on:click="addOther"
            style="width: 96px; height: 44px; margin-top: 25px; margin-right: 15px; font-size: 16px">
      <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>其他项
    </button>
    <button class="btn btn-danger" v-show="showSaveBtn" v-on:click="save"
            style="width: 96px; height: 44px; margin-top: 25px; margin-right: 15px; font-size: 16px">
      <span class="glyphicon glyphicon-upload" style="padding-right: 5px"></span>提交
    </button>
  </div>

  <!--Save Modal -->
  <div class="modal fade" id="saveModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h2>更改提醒</h2>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <!-- 加上<form>标签可以使得modal窗口在点击按钮都自动dismiss-->
          <form role="form">
            <h3>请确认以下改动：</h3>
            <button type="submit" style="margin-top: 50px" class="btn btn-danger btn-block">确定</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="deviceInfo" class="container-fluid container">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2 class="panel-title" style="width: 100%; text-align: left; border-radius: 2px; font-size: 20px">设备信息</h2>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="control-label">设备 ID</label>
            <input type="text" v-model="deviceDate[0].device_sn" placeholder="" class="form-control" disabled/>
          </div>
          <div class="form-group">
            <label class="control-label">设备类型</label>
            <input type="text" v-model="deviceDate[0].type_name" placeholder="" class="form-control" disabled/>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="control-label">出厂时间</label>
            <input type="text" v-model="deviceDate[0].date_production" placeholder="" class="form-control" disabled/>
          </div>
          <div class="form-group">
            <label class="control-label">录入时间</label>
            <input type="text" v-model="deviceDate[0].date_added" placeholder="" class="form-control" disabled/>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label">客户姓名</label>
            <input type="text" v-model="deviceDate[0].fullname" placeholder="" class="form-control" disabled/>
          </div>
          <div class="form-group">
            <label class="control-label">客户地址</label>
            <input type="text" v-model="deviceDate[0].address" placeholder="" class="form-control" disabled/>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="installInfo" class="container-fluid container">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <div style="font-size: 20px">调试信息</div>
    </div>
    <div class="table-responsive">
      <form class="form-horizontal" role="form"
            style="margin-top: 30px;margin-left:-40px; margin-right: 20px; font-size: 16px">
        <div class="form-group">
          <label class="control-label col-sm-2">调试编号:</label>
          <div class="col-sm-10">
            <b><input type="text" disabled="true" class="form-control" value="<?php echo ($installID); ?>" style="height: 44px;"/></b>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2">调试员:</label>
          <div class="col-sm-10">
            <select v-model="installInfo.customer_id" class="form-control"
                    style="height: 44px; font-size: 16px; font-weight: bold; "
                    v-bind:style="{'border-color': oldInstallInfo.customer_id != installInfo.customer_id ? 'red': '' }">
              <option v-bind:value="person.customer_id" v-for="person in sevicePersons" style="font-size:16px">
                {{person.fullname}}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2">调试状态:</label>
          <div class="col-sm-10">
            <select v-model="installInfo.status" class="form-control"
                    style="height: 44px; font-size: 16px; font-weight: bold;"
                    v-bind:style="{'border-color': installInfo.status != oldInstallInfo.status ? 'red':''}">
              <option v-bind:value="status.status_id" v-for="status in installStatus"
                      style="float:right; font-size: 16px; margin-top: 5px;margin-bottom: 5px">
                {{status.status_name}}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2">调试时间:</label>
          <div class="col-sm-10">
            <input type="date" class="form-control" v-model="installInfo.install_plan_date" placeholder="输入调试日期"
                   style="height: 44px; font-size: 16px;font-weight: bold;"
                   v-bind:style="{'border-color': installInfo.install_plan_date != oldInstallInfo.install_plan_date ? 'red': ''}">
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="panel-title" style="font-size: 20px">
        基本调试项 <span class="badge" style="vertical-align: middle">{{basicItems.length}}</span>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered" align="center" style="table-layout: fixed">
        <thead>
        <tr>
          <th width="36px" style="text-align: center"></th>
          <th width="15%" style="font-size: 15px; text-align: center">项目 ID</th>
          <th style="font-size: 15px; text-align: center">项目名称</th>
          <th width="10%" style="text-align: center">数值</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in basicItems"
            v-bind:style="{'color': oldBasicItems[index].basic_item_value != item.basic_item_value? 'red':''}">
          <td>
            <button class="btn btn-danger btn-xs"
                    style="width:20px; height: 20px; line-height: 20px; margin-top: 8px; text-align: center"
                    v-on:click="removeBasic(item)">
              <span class="glyphicon glyphicon-minus "
                    style="font-size: 11px;vertical-align: 2px; margin-left: -1.5px"></span>
            </button>
          </td>
          <td style="height: 32px; line-height: 32px;text-align: center">{{item.basic_item_id}}</td>
          <td style="height: 32px; line-height: 32px;text-align: center">{{item.basic_item_name}}</td>
          <td style="height: 32px; line-height: 32px" width="10%">
            <div class="text-center">
              <input type="text" width="50px"
                     style="border-left: hidden; border-right: hidden; border-top: hidden;
                  border-bottom-color: #337ab7;text-align: center; border-bottom-width: 1px; font-weight: bold; width:60px"
                     v-model="item.basic_item_value"
              >
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
  <div>
    <ul style="margin-left: 0px;padding-left: 0px;">
      <li v-for="(group, index) in groups" style="list-style-type:none; ">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <div style="font-size: 20px">{{group.name}}
              <span class="badge" style="vertical-align: middle">{{group.items.length}}</span>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered" v-show="group.items.length > 0" align="center"
                   style="table-layout: fixed">
              <thead>
              <tr>
                <th width="15%" style="font-size: 15px;text-align: center">项目 ID</th>
                <th style="font-size: 15px;text-align: center">项目名称</th>
                <th width="10%" style="text-align: center"></th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item, i) in group.items"
                  v-bind:style="{'color': item.item_value != oldGroups[index].items[i].item_value ? 'red':''}">
                <td style="height: 32px; line-height: 32px;text-align: center">{{item.item_id}}</td>
                <td style="height: 32px; line-height: 32px;text-align: center">{{item.item_name}}</td>
                <td style="height: 32px; line-height: 32px">
                  <div class="text-center danger">
                    <input type="checkbox"
                           style="width: 20px; height: 20px;" :checked="parseInt(item.item_value)"
                           v-on:change="changeCheckStatus(index, i)">
                  </div>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2 class="panel-title" style="font-size: 20px">调试备注</h2>
    </div>
    <div class="table-responsive">
    </div>

  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h2 class="panel-title" style="font-size: 20px">客户评价</h2>
    </div>
    <div class="table-responsive">
    </div>

  </div>
</div>

</body>
</html>
<script>

  var app2 = new Vue({
    el: '#deviceInfo',
    data: {
      deviceDate: eval('(<?php echo json_encode($deviceData);?>)'),
    },
    methods: {}
  });

  var app3 = new Vue({
    el: '#installInfo',
    data: {
      //全部调试状态
      installStatus: eval('(<?php echo json_encode($statuses);?>)'),
      //全部调试员
      sevicePersons: eval('(<?php echo json_encode($servicePersons);?>)'),

      oldInstallInfo: eval('(<?php echo json_encode($installInfo);?>)'),//保存之前的值，用于对比是否被修改
      installInfo: eval('(<?php echo json_encode($installInfo);?>)'),

      oldBasicItems: eval('(<?php echo json_encode($basicItems);?>)'),//保存之前的值，用于对比是否被修改
      basicItems: eval('(<?php echo json_encode($basicItems);?>)'),

      oldGroups: eval('(' + '<?php echo json_encode($otherItems);?>' + ')'),//保存之前的值，用于对比是否被修改
      groups: eval('(' + '<?php echo json_encode($otherItems);?>' + ')'),

    },
    methods: {
      changeCheckStatus: function (parentIndex, index) {
        if (this.groups[parentIndex].items[index].item_value == 0) {
          this.groups[parentIndex].items[index].item_value = 1;
        } else {
          this.groups[parentIndex].items[index].item_value = 0;
        }
      },
      removeBasic: function (item) {
        alert(item.basic_item_name);
      }

    },
    computed: {}
  });

  //Div 的不ID不能嵌套访问。如果要定义多个Vue对象，必须保证div的id是独立关系，不能是包含嵌套的关系
  var app = new Vue({
    el: '#saveChange',
    data: {
      changeMsg: ''
    },
    methods: {
      save: function () {

      },
      addBasic: function () {

      },
      addOther: function () {

      }

    },
    computed: {
      showSaveBtn: function () {
        //对过对比old和new,检查调试信息是否改动
        var key1;
        for (key1 in app3.oldInstallInfo) {
          if (app3.oldInstallInfo[key1] != app3.installInfo[key1]) {
            return true;
          }
        }

        for (var i = 0; i < app3.oldBasicItems.length; i++) {
          if (app3.oldBasicItems[i].basic_item_value != app3.basicItems[i].basic_item_value) {
            return true;
          }
        }

        for (var j = 0; j < app3.oldGroups.length; j++) {
          for (var k = 0; k < app3.oldGroups[j].items.length; k++) {
            if (app3.oldGroups[j].items[k].item_value != app3.groups[j].items[k].item_value) {
              return true;
            }
          }
        }

        return false;
      }
    }
  });


</script>