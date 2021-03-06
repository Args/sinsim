<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns="http://www.w3.org/1999/html" xmlns:v-show="http://www.w3.org/1999/xhtml"
      xmlns:v-click="http://www.w3.org/1999/xhtml" xmlns:v-model="http://www.w3.org/1999/xhtml"
      xmlns:vo-on="http://www.w3.org/1999/xhtml">
  <head>
    <title>保养</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="/service/Public/css/mystyle.css"rel="stylesheet"/>
    <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
    <script type='text/javascript' src='/service/Public/js/vue.js'></script>
    <script type='text/javascript' src='/service/Public/s/jquery-3.1.1.min.js'></script>
    <style>

    </style>
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header"  style="margin-top: 5px">
          <a class="sinsim-logo" href="#">SinSim</a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="<?php echo U('index/index');?>">客户管理</a></li>
          <li><a href="<?php echo U('machine/index');?>">机器管理</a></li>
          <li><a href="<?php echo U('install/index');?>">安装调试</a></li>
          <li class="active"><a href="<?php echo U('maintain/index');?>">设备保养</a></li>
          <li><a href="<?php echo U('DeviceProblem/Problem');?>">设备维修</a></li>
          <li><a href="#">配件更换</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span>登录</a></li>
        </ul>
      </div>
    </nav>
    <div id="filter">

      <div style="margin: 15px">
          <button class="btn btn-primary" v-on:click="create" style="height: 44px;">
            <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>新建保养
          </button>
      </div>
      <div class="container-fluid">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">筛选列表</h3>
          </div>
          <div class="panel-body">
            <div class="well">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label">设备 ID</label>
                    <input type="text" v-model="filters.filter_id" placeholder="设备 ID" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">设备类型</label>
                    <select v-model="filters.filter_type" class="form-control">
                      <option v-for="type in machineType" v-bind:value="type.value">
                        {{ type.text }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label">客户</label>
                    <input type="text" v-model="filters.filter_customer" placeholder="姓名" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">调试状态</label>
                    <select v-model="filters.filter_status"class="form-control">
                      <option v-for="status in statuses" v-bind:value="status.value">
                        {{ status.text }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label">调试员</label>
                    <input type="text" v-model="filters.filter_service" placeholder="姓名" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">调试时间</label>
                    <input type="date" v-model="filters.filter_time" placeholder="选择晚于某个时间" class="form-control" />
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group" style="text-align: center;padding-top: 60px">
                    <button type="button" class="btn btn-primary" style="width: 100px">筛选</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<script>
  var app = new Vue({
      el: '#filter',
      data: {
        filters : {
          filter_id: '',
          filter_type: '',
          filter_customer: '',
          filter_status: '',
          filter_service: '',
          filter_time: '',
        },
        //机器名称，即种类即名称
        statuses: [
          {text:"待调试", value:'1'},
          {text:"调试中", value:'2'},
          {text:"调试确认", value:'3'},
          {text:"调试完成", value:'4'}
        ],
        machineType: [
          {text:"平绣", value:'1'},
          {text:"特种装备", value:'2'},
          {text:"毛巾绣", value:'3'},
        ]
      },

      methods: {
        create: function () {
          
        }
      }
  })
</script>