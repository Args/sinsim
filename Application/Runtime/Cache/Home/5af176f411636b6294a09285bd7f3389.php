<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns="http://www.w3.org/1999/html" xmlns:v-show="http://www.w3.org/1999/xhtml"
      xmlns:v-click="http://www.w3.org/1999/xhtml" xmlns:v-model="http://www.w3.org/1999/xhtml"
      xmlns:vo-on="http://www.w3.org/1999/xhtml">
<head>
  <title>客户机器</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
  <!-- <script src="https://ajax.googleapis.bootcss.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> -->
  <!-- <script src="https://unpkg.com/vue/dist/vue.js"></script> -->
    <!-- <script src="http://maxcdn.bootstrapcdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <script type='text/javascript' src='/service/Public/js/vue.js'></script>
  <!--<script type='text/javascript' src='/service/Public/js/bootstrap.min.js'></script>-->
  <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>
  <style>
    table thead tr th {
      text-align: center
    }
    table tbody tr td {
      text-align: center;
      height: 48px;
    }
  </style>
</head>
<body class="container" style="zoom: 100%;margin-top: 36px;">
  <div id="userBind" class="panel panel-primary" style="width: 100%; text-align: start; border-radius: 2px;">
    <div class="panel-heading">
      <h2>{{user.fullname}}</h2>
    </div>
    <div class="panel-body" style="background-color: whitesmoke">
      <div>{{user.email}}</div>
      <div style="margin-top: 10px">{{user.telephone}}</div>
      <div style="margin-top: 10px">{{user.address}}</div>
    </div>
  </div>

  <div id="bindDevice">
    <table width="100%" border="0" style="margin-top: 20px">
      <tr>
        <td align="left" width="90%">
          <input class="form-control" v-model="content" type="search" placeholder="请输入机器编号进行绑定..." style="height: 48px;"
            v-on:keyup="device_search(content,$event)"
            v-on:keyup.up.stop="keyUp"
            v-on:keyup.down="keyDown"
            v-on:keyup.enter="keyEnter">
        </td>
        <td align="right" style="text-align: right">
          <button class="btn btn-primary" v-on:click="bind" style="height: 48px">
            <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>绑定机器
          </button>
        </td>
      </tr>
    </table>
  </div>
  <div id="search">
    <ul v-show="searchItems.length > 0" class="list-group" style="width: 90%;" >
      <li v-for="(item,index) in searchItems" class="list-group-item" style="border-radius: 0px"
          v-bind:class="{'list-group-item list-group-item-info': currentIndex == index} "
          v-on:mouseover="tryChoose(index)"
          v-on:click="choose(index)">{{item.device_sn}}</li>
    </ul>
  </div>
  <div id="machine" style="margin-top: 20px" class="panel panel-primary">
    <div class="panel-heading" style="width: 100%; text-align: left; border-radius: 2px">已绑定机器
      <span class="badge" style="margin-left: 5px">{{bindedDevices.length}}</span>
    </div>
    <div class="table-responsive">
      <table class="table table-hover" v-show="bindedDevices.length > 0" align="center" style="table-layout: fixed">
        <thead>
          <tr>
            <th>设备编号</th>
            <th>设备名称</th>
            <th style="width: 30%">设备概述</th>
            <th>出厂日期</th>
            <th>调试日期</th>
            <th>保养次数</th>
            <th>维修次数</th>
            <th></th>
          </tr>
        </thead>
        <tbody >
          <tr v-for="(device, index) in bindedDevices" style="height: 48px; line-height: 48px">
            <td>{{device.device_sn}}</td>
            <td>{{device.type_name}}</td>
            <td>{{device.device_des}}</td>
            <td>{{device.date_production}}</td>
            <td>{{device.date_production}}</td>
            <td>0</td>
            <td>0</td>
            <td>
              <div class="col-sm-offset-2 col-sm-10" >
                <button class="btn btn-danger" style="height: 25px; font-size: smaller" v-on:click="unbind(index)">解绑</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p v-show="bindedDevices.length == 0" style="width: 100%; height: 100px; line-height: 100px; text-align: center; color: darkgray">暂无绑定机器~~~</p>
  </div>

  </body>
</html>

<script>

  var getBindDevices = function () {
      $.ajax({
          //searchContent后面必须直接跟“=”， 不然会出错
          url: "<?php echo U('machine/ajaxGetBindedDevices');?>" + "?customerID=" + "<?php echo ($user['customer_id']); ?>",
          type: 'GET',
          success: function(data) {
              if(data.status) {
                  //TODO:
                  app3.bindedDevices = data.info;
              } else {
                  alert(data.info);
              }
          },
          error: function(data) {
              alert(data.info);
          }
      })
  };

  window.onload = function () {
      getBindDevices();

      var user = '<?php echo json_encode($user);?>';
      var jsonobj=eval('('+user+')');
//      alert(jsonobj.fullname);
//      alert(jsonobj.telephone);
  };


  var app5 = new Vue({
    el: '#userBind',
    data: {
      user: eval('('+'<?php echo json_encode($user);?>'+')')
    },
    methods: {

    },
  })


  var app = new Vue({
    el: '#search',
    data: {
      searchItems: [
//          {'device_sn':'hello'},
//          {'device_sn':'world'},
      ],
      currentIndex: -1,

    },
    methods: {
        tryChoose: function (index) {
          this.currentIndex = index;
        },
        choose: function (index) {
          this.currentIndex = index;
          app2.content = app.searchItems[this.currentIndex]['device_sn'];
          app.searchItems = [];
        }
    },
  })

  var app2 = new Vue({
    el:'#bindDevice',
    data: {
      content: '',
      searchUrl: "<?php echo U('machine/ajaxSearchUnbindedDevice');?>",
      bindUrl: "<?php echo U('machine/bindDevice');?>",
    },
    methods: {
      bind: function () {
          if(app2.content == null || app2.content == "") {
              alert("设备编号为空！");
          } else if(app2.content.length != 12) {
              alert("设备编号格式不正确！");
          } else {
              $.ajax({
                  //必须在Ajax中进行URL的组装，否则app2.content等参数会为空或者未定义
                  url: this.bindUrl+ "?deviceID=" + app2.content + "&customerID=" + "<?php echo ($user['customer_id']); ?>" ,
                  type: 'GET',
                  success: function(data) {
                      if(data.status) {
                          //更新bind的机器数
                          getBindDevices();
                          //刷新bind的机器列表
                          
                      } else {
                          alert(data.info);
                      }
                  },
                  error: function(data) {
                      alert(data);
                  }
              })
          }
      },
      keyDown: function () {
        if(app.currentIndex == app.searchItems.length -1){
            app.currentIndex = 0;
        } else {
            app.currentIndex = app.currentIndex +1;
        }
      },
      keyUp: function () {
        if(app.currentIndex == 0){
            app.currentIndex = app.searchItems.length -1;
        } else {
            app.currentIndex = app.currentIndex -1;
        }
      },
      keyEnter:function () {
        this.content = app.searchItems[app.currentIndex]['device_sn'];
        app.searchItems = [];
      },
      device_search: function (content, e) {
//        alert(content);
        if(e != null && e.keyCode == 13) return;
        //当content为空时，将app.currentIndex置为-1
        if(content.length == 0) {
          app.currentIndex = -1;
        }
        $.ajax({
          //searchContent后面必须直接跟“=”， 不然会出错
          url: this.searchUrl + "?searchContent=" + this.content,
          type: 'GET',
          success: function(data) {
            if(data.status) {
              //TODO:
              app.searchItems = data.info;
            } else {
              alert(data.info);
            }
          },
          error: function(data) {
              alert(data.info);
          }
        })
      }
    }
  })

  var app3 = new Vue({
    el: '#machine',
    data: {
      unbindUrl: "<?php echo U('machine/unBindDevice');?>",
      bindedDevices: []
    },
    methods: {
      unbind : function (index) {
        //alert(app3.bindedDevices[index]['device_sn']);
        //先解绑
        if(app3.bindedDevices[index]['device_sn'] != null) {
          $.ajax({
            //searchContent后面必须直接跟“=”， 不然会出错
            url: this.unbindUrl + "?deviceID=" + app3.bindedDevices[index]['device_sn'],
            type: 'GET',
            success: function(data) {
              if(data.status) {
                //刷新bindedDevcies数组
                app3.bindedDevices.pop(index);
              } else {
                alert(data.info);
              }
            },
            error: function(data) {
              alert(data.info);
            }
          })
        } else {
          alert("解绑出错！");
        }
      }
    }
  })
</script>