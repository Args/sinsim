<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns="http://www.w3.org/1999/html" xmlns:v-show="http://www.w3.org/1999/xhtml"
      xmlns:v-click="http://www.w3.org/1999/xhtml" xmlns:v-model="http://www.w3.org/1999/xhtml"
      xmlns:vo-on="http://www.w3.org/1999/xhtml">
  <head>
    <title>安装</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
    <link type="text/css" href="/service/Public/css/mystyle.css"rel="stylesheet"/>
    <script type='text/javascript' src='/service/Public/js/vue.js'></script>
    <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>
    <style>
      table thead tr th {
        text-align: center
      }
      table tbody tr td {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header" style="margin-top: 5px">
          <a class="sinsim-logo"   href="#">SinSim</a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="<?php echo U('index/index');?>">客户管理</a></li>
          <li><a href="<?php echo U('machine/index');?>">机器管理</a></li>
          <li class="active"><a href="#">安装调试</a></li>
          <li><a href="<?php echo U('maintain/index');?>">设备保养</a></li>
          <li><a href="<?php echo U('DeviceProblem/Problem');?>">设备维修</a></li>
          <li><a href="#">配件更换</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span>登录</a></li>
        </ul>
      </div>
    </nav>
    <div id="filter">
      <div class="container-fluid">
        <div style="margin-bottom: 15px">
          <button class="btn btn-primary" v-on:click="modify" style="height: 44px;">
            <span class="glyphicon glyphicon-edit" style="padding-right: 5px"></span>调试项
          </button>
          <button class="btn btn-primary" v-on:click="create" style="height: 44px;text-align:right; display:inline; margin-left: 15px">
            <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>新建调试
          </button>
        </div>
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
                      <option v-for="type in machineTypes" v-bind:value="type.type_id">
                        {{ type.type_name }}
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
                      <option v-for="status in statuses" v-bind:value="status.status_id">
                        {{ status.status_name}}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label">调试时间 (起始)</label>
                    <input type="date" v-model="filters.filter_begin_time" placeholder="起始时间" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">调试时间 (终止)</label>
                    <input type="date" v-model="filters.filter_end_time" placeholder="终止时间" class="form-control" />
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label">调试员</label>
                    <input type="text" v-model="filters.filter_service" placeholder="姓名" class="form-control" />
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-primary" style="width: 100px; margin-top: 25px" v-on:click="filter">筛选</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="install" class="container-fluid" style="margin-bottom: 15px">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">调试记录
          <span class="badge" style="margin-left: 5px">{{records.length}}</span>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" v-show="records.length >= 0" align="center" style="table-layout: fixed">
            <thead>
            <tr>
              <th>调试号</th>
              <th>设备编号</th>
              <th>设备类型</th>
              <th>客户姓名</th>
              <th>调试员</th>
              <th>调试状态</th>
              <th>调试时间</th>
            </tr>
            </thead>
            <tbody >
              <tr v-for="(record, index) in records">
                <td style="vertical-align: middle">
                  <div style="font-weight: bold" class="btn btn-link" v-on:click="installDetail(record.install_id,record.device_sn)">
                    {{record.install_id}}
                  </div>
                </td>
                <td style="vertical-align: middle">{{record.device_sn}}</td>
                <td style="vertical-align: middle">{{record.type_name}}</td>
                <td style="vertical-align: middle">{{record.fullname}}</td>
                <td style="vertical-align: middle">{{record.service_name}}</td>
                <td style="vertical-align: middle">{{record.status_name}}</td>
                <td style="vertical-align: middle">{{record.install_plan_date}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
        <div style="text-align: center">
          <ul class="pagination">
            <li v-if="pageNum > 10" v-on:click="prePage"><a href="#">上一页</a></li>
            <li v-for="n in pageNum" v-bind:class="{'active': activePageIndex == n}" v-on:click="choosePage(n)" v-if="pageNum <= 10"><a href="#">{{n}}</a></li>
            <li v-for="n in 10" v-bind:class="{'active':  activePageIndex == indexShowFirst + n}" v-on:click="choosePage(indexShowFirst + n)" v-if="pageNum > 10"><a href="#">{{indexShowFirst + n}}</a></li>
            <li v-if="pageNum > 10" v-on:click="nextPage"><a href="#">下一页</a></li>
          </ul>
          <div>
            <select v-model="activePageIndex" v-on:change="selectPage" v-show="pageNum > 10" >
              <option v-for="n in pageNum">{{n}}</option>
            </select>
          </div>

        </div>
    </div>
  </body>
</html>
<script>
  var app = new Vue({
      el: '#filter',
      data: {
        filterURL: "<?php echo U('install/filterInstallMenu');?>",
        filters: {
          filter_id: '',
          filter_type: '',
          filter_customer: '',
          filter_status: '',
          filter_service: '',
          filter_begin_time: '',
          filter_end_time: ''
        },
        //调试状态
        statuses: eval('(<?php echo json_encode($statuses);?>)'),
        //设备类型
        machineTypes: eval('(<?php echo json_encode($machineTypes);?>)'),
      },

      methods: {
        create: function () {
          window.location.href ="<?php echo U('install/create');?>";
        },
        modify: function () {
          window.location.href ="<?php echo U('install/modifyRecordItem');?>";
        },
        filter: function () {
          $.ajax({

            //必须在Ajax中进行URL的组装，否则app2.content等参数会为空或者未定义
            url: this.filterURL,
            type: 'POST',
            dataType: 'JSON',
            data: app.filters,
            success: function(data) {
              if(data.status) {
                app2.records = data.info;
              } else {
                alert(data.info);
              }
            },
            error: function(data) {
              alert(data);
            }
          })
        }
      }
  })

  var app2 = new Vue({
    el: '#install',
    data: {
      records:eval('(<?php echo json_encode($installRecords);?>)'),
      activePageIndex: 1,//当前active的page index
      indexShowFirst:0,
    },
    methods: {
      installDetail: function (installID, deviceID) {
        window.location.href ="<?php echo U('install/InstallDetail');?>?installID=" + installID + "&deviceID=" + deviceID;
      },
      choosePage: function (index) {
        this.activePageIndex = index;
        //TODO:从服务器获取指定page的内容，更新records
      },
      prePage: function () {
        if(this.activePageIndex > 1) {
          this.activePageIndex = parseInt(this.activePageIndex) -1;
        }else {
          this.activePageIndex = 1;
        }
        if(this.pageNum > 10 ) {
          this.indexShowFirst = this.activePageIndex >=10 ? this.activePageIndex -10 : 0;
        }
      },
      nextPage: function () {
        if(this.activePageIndex == this.pageNum ) {
        }else {
          this.activePageIndex = parseInt(this.activePageIndex) + 1;
        }
        if(this.pageNum > 10) {
          this.indexShowFirst = this.activePageIndex >10 ? this.activePageIndex -10 : 0;
        }
      },
      selectPage: function () {
        if(this.pageNum > 10) {
          this.indexShowFirst = this.activePageIndex >10 ? this.activePageIndex -10 : 0;
        }
      }
    },
    computed: {
      pageNum: function() {
         return Math.ceil(this.records.length/20)//当pager indicator超过10的时候显示
//        return 20;
      }
    }
  })
</script>