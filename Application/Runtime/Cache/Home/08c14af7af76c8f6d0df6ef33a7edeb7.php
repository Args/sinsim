<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
<head>
  <title>创建调试</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <script type='text/javascript' src='/service/Public/js/vue.js'></script>
  <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>
  <script type='text/javascript' src='/service/Public/js/bootstrap.min.js'></script>

</head>
<body>
<div class="container" style="margin-top: 15px">
  <div class="page-header">
    <h1 style="text-align: left; margin-left: 10px">创建调试</h1>
  </div>

  <div class="container-fluid">
    <div id="chooseDevice">
      <table width="100%">
        <td align="left" width="90%">
          <input class="form-control" v-model="content" type="search" placeholder="请输入机器编号创建调试..." style="height: 48px;"
                 v-on:keyup="device_search(content,$event)"
                 v-on:keyup.up.stop="keyUp"
                 v-on:keyup.down="keyDown"
                 v-on:keyup.enter="keyEnter">
        </td>
        <td align="right" style="text-align: right">
          <button class="btn btn-primary"  style="height: 48px">
            <span class="glyphicon glyphicon-ok" style="padding-right: 5px"></span>选择机器
          </button>
        </td>
      </table>
    </div>
    <div id="search" style="margin-bottom: 15px">
      <ul v-show="searchItems.length > 0" class="list-group" style="width: 90%;" >
        <li v-for="(item,index) in searchItems" class="list-group-item" style="border-radius: 0px"
            v-bind:class="{'list-group-item list-group-item-info': currentIndex == index} "
            v-on:mouseover="tryChoose(index)"
            v-on:click="choose(index)">{{item.device_sn}}</li>
      </ul>
    </div>
    <div id="basicInfo" class="panel panel-primary">
      <div class="panel-heading">
        <h2 class="panel-title" style="width: 100%; text-align: left; border-radius: 2px; font-size: 20px">设备信息</h2>
      </div>
      <div class="panel-body">
        <div class="row" >
          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label">设备 ID</label>
              <input type="text" v-model="deviceSN" placeholder="" class="form-control" disabled/>
            </div>
            <div class="form-group">
              <label class="control-label">设备类型</label>
              <input type="text" v-model="deviceType" placeholder="" class="form-control" disabled/>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label">出厂时间</label>
              <input type="text" v-model="productionDate" placeholder="" class="form-control" disabled/>
            </div>
            <div class="form-group">
              <label class="control-label">录入时间</label>
              <input type="text" v-model="addedDate" placeholder="" class="form-control" disabled/>
            </div>
            <!--<div class="form-group">-->
              <!--<label class="control-label">调试状态</label>-->
              <!--<select v-model="filters.filter_status"class="form-control">-->
                <!--<option v-for="status in statuses" v-bind:value="status.value">-->
                  <!--{{ status.text }}-->
                <!--</option>-->
              <!--</select>-->
            <!--</div>-->
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label">客户姓名</label>
              <input type="text" v-model="customerName" placeholder="" class="form-control" disabled/>
            </div>
            <div class="form-group">
              <label class="control-label">客户地址</label>
                <input type="text" v-model="customerAddress" placeholder="" class="form-control" disabled/>
            </div>
            <!--<div class="form-group">-->
              <!--<label class="control-label">调试时间 (晚于)</label>-->
              <!--<input type="date" v-model="filters.filter_time" placeholder="选择晚于某个时间" class="form-control" />-->
            <!--</div>-->
          </div>
        </div>
        <!--Delete item Modal -->
        <div class="modal fade" id="notifyUnbinded" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="padding:35px 50px;">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h2>提醒</h2>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                <!-- 加上<form>标签可以使得modal窗口在点击按钮都自动dismiss-->
                <form role="form">
                  <h4>请切换至“客户”页面，将机器绑定至指定客户，否则无法开始调试工作！</h4>
                  <button type="submit" style="margin-top: 50px" class="btn btn-danger btn-block">确定</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="basicItems" class="panel panel-primary" >
      <div class="panel-heading">
        <input type="checkbox" style="width: 18px; height: 18px; display: inline; float: right; margin-right: 10px" v-model="basicAllChecked" v-on:click="checkAllBasicItemOnOff">
        <div style="font-size: 20px" v-on:click="showOrHideItems">基本信息
          <span class="badge" style="vertical-align: middle">{{computeBasicItemNum}}</span>
        </div>
      </div>
      <div class="table-responsive" v-show="toggle">
        <table class="table table-hover table-bordered" align="center" style="table-layout: fixed">
          <thead>
          <tr>
            <!--<th width="30px" style="text-align: center">-->
              <!--<input type="checkbox" >-->
            <!--</th>-->
            <th width="15%" style="font-size: 15px; text-align: center">项目 ID</th>
            <th style="font-size: 15px; text-align: center">项目名称</th>
            <th width="6%" style="text-align: center">删除</th>
          </tr>
          </thead>
          <tbody >
          <tr v-for="(item, index) in basicItems">
            <!--<td width="30px" style="height: 32px; line-height: 32px;text-align: center">-->
              <!--<input type="checkbox">-->
            <!--</td>-->
            <td  style="height: 32px; line-height: 32px;text-align: center">{{item.basic_item_id}}</td>
            <td  style="height: 32px; line-height: 32px;text-align: center">{{item.basic_item_name}}</td>
            <td  style="height: 32px; line-height: 32px">
            <div class="text-center">
              <input type="checkbox" style="width: 16px; height: 16px;" v-model="item.add_menu">
            </div>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div id="others">
      <ul style="margin-left: 0px;padding-left: 0px;">
        <li v-for="(group, index) in groups" style="list-style-type:none; ">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <input type="checkbox" style="width: 18px; height: 18px; display: inline; float: right; margin-right: 10px" :checked="allCheckedStatus[index]" v-on:click="checkAllItemOnOff(index)">
              <div style="font-size: 20px"  v-on:click="showOrHideItems(index)">{{group.name}}
                <!--<span class="badge" style="vertical-align: middle" v-else>0</span>-->
                <span class="badge" style="vertical-align: middle">{{checkedNum[index]}}</span>
                <!--<span class="badge" style="vertical-align: middle" v-else>0</span>-->
              </div>
            </div>
            <div class="table-responsive" v-show="showStatus[index]">
              <table class="table table-hover table-bordered" v-show="group.items.length > 0" align="center" style="table-layout: fixed">
                <thead>
                <tr>
                  <th width="15%" style="font-size: 15px;text-align: center">项目 ID</th>
                  <th style="font-size: 15px;text-align: center">项目名称</th>
                  <th width="6%" style="text-align: center"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in group.items">
                  <td  style="height: 32px; line-height: 32px;text-align: center">{{item.item_id}}</td>
                  <td  style="height: 32px; line-height: 32px;text-align: center">{{item.item_name}}</td>
                  <td  style="height: 32px; line-height: 32px">
                  <div class="text-center">
                    <input type="checkbox" style="width: 16px; height: 16px;" v-model="item.add_menu" v-on:change="checkItem(index)">
                  </div>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <p v-show="showStatus[index] && group.items.length == 0" style="line-height: 100px; text-align: center; color: darkgray">暂无{{group.name}}信息项，请创建~~~</p>
          </div>
        </li>
      </ul>
    </div>
    <div id="service">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h2 class="panel-title" style="font-size: 20px">分派调试人员</h2>
        </div>
        <div class="table-responsive well well-sm">
          <select v-model="choosedPerson" class="form-control" style="height: 44px; font-size: 16px; font-weight: bold;">
          	<option v-bind:value="person" v-for="person in sevicePersons" style="font-size:16px">{{person.fullname}}</option>
          </select>
        </div>
      </div>

      <div class="panel panel-danger">
        <div class="panel-heading">
          <h2 class="panel-title" style="font-size: 20px">调试时间</h2>
        </div>
        <div class="table-responsive well well-sm">
          <input type="date" class="form-control" v-model="installDate" placeholder="输入调试日期" style="height: 44px; font-size: 16px;font-weight: bold;">
        </div>
      </div>
      <button style="height: 44px; text-align:center; font-size: 18px; margin-bottom: 50px" class="btn btn-primary btn-block" v-on:click="createInstallMenu">创 建</button>
      <!--Notify error Modal -->
      <div class="modal fade" id="notifyError" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header" style="padding:40px">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h2>提醒</h2>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <!-- 加上<form>标签可以使得modal窗口在点击按钮都自动dismiss-->
              <h4>{{errorMsg}}</h4>
              <button type="button" style="margin-top: 50px; font-size: 16px;" class="btn btn-danger btn-block" v-on:click="closeModal">确定</button>
            </div>
          </div>
        </div>
      </div>
      <!--Notify confirm Modal -->
      <div class="modal fade" id="notifyConfirm" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header" style="padding:40px">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h2>调试单</h2>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <!-- 加上<form>标签可以使得modal窗口在点击按钮都自动dismiss-->
              <h4>
                <p>机器编号：{{deviceSN}}</p>
                <p>客户：<b style="color: red">{{customerName}}</b></p>
                <p>基本调试项数：{{basicNum}}</p>
                <p>其他调试项数：{{otherNum}}</p>
                <p>调试员：<b style="color: red">{{choosedPerson.fullname}}</b></p>
                <p>调试日期：<b style="color: red">{{installDate}}</b></p>
              </h4>
              <button type="button" style="margin-top: 50px; font-size: 16px;" class="btn btn-danger btn-block" v-on:click="submitInstallMenu">确定</button>
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

  window.onload = function () {
//      for(var i=0; i < app2.groups.length; i++) {
//          app2.showStatus[i] = true;
//      }
    //通过数组直接赋值，无法更新UI
    for(var i=0; i < app5.groups.length; i++) {
      app5.$set(app5.showStatus,i,!app5.showStatus[i]);
      app5.$set(app5.checkedNum,i, 0);//初始化为0，用于保存子类中已经勾选的数目
      app5.$set(app5.allCheckedStatus,i, false);//初始化为0，用于保存某个子类是否被全选
    }

  }

//  function disableParentEvent(e){
//    //如果传入了事件对象.那么就是非IE浏览器
//    if (e && e.stopPropagation) {
//      //因此它支持W3C的stopPropation()方法
//      e.stopPropagation();
//    }
//    else {
//      //否则,我们得使用IE的方式来取消事件冒泡
//      window.event.cancelBubble = true;
//    }
//  }

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
        //填写机器及客户
        app3.deviceSN = app.searchItems[this.currentIndex]['device_sn'];
        app3.deviceType = app.searchItems[this.currentIndex]['type_name'];
        app3.productionDate = app.searchItems[this.currentIndex]['date_production'];
        app3.addedDate = app.searchItems[this.currentIndex]['date_added'];
        app3.customerName = app.searchItems[app.currentIndex]['fullname'];
        app3.customerAddress = app.searchItems[app.currentIndex]['address'];
        if(app3.customerName == null) {
          $("#notifyUnbinded").modal({
//            backdrop: false;点击对话框之外，不dismiss对话框，但是没有了遮盖
          });
        }
        app.searchItems = [];
      }
    },
  })

  var app2 = new Vue({
    el:'#chooseDevice',
    data: {
      content: '',
      searchUrl: "<?php echo U('machine/ajaxSearchUnInstalledDevice');?>",
    },
    methods: {
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
        //填写机器及客户
        app3.deviceSN = app.searchItems[app.currentIndex]['device_sn'];
        app3.deviceType = app.searchItems[app.currentIndex]['type_name'];
        app3.productionDate = app.searchItems[app.currentIndex]['date_production'];
        app3.addedDate = app.searchItems[app.currentIndex]['date_added'];
        app3.customerName = app.searchItems[app.currentIndex]['fullname'];
        if(app3.customerName == null) {
          $("#notifyUnbinded").modal();
        }
        app3.customerAddress = app.searchItems[app.currentIndex]['address'];

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
    el: '#basicInfo',
    data: {
      deviceSN: '',
      deviceType: '',
      productionDate: '',
      addedDate: '',
      customerName: '',
      customerAddress: '',
    }
  })

  var app4 = new Vue({
    el: '#basicItems',
    data: {
      basicItems:eval('(<?php echo json_encode($basicItems);?>)'),
      toggle: true,
    },
    methods: {
      showOrHideItems:function () {
        app4.toggle = !app4.toggle;
      },

      checkAllBasicItemOnOff: function (e) {
//        disableParentEvent(e);
        var allChecked = true;
        var temp = 0;
        //先检查接下来的动作是全部选上还是全部不选
        for(var i=0; i< this.basicItems.length; i++) {
          if(this.basicItems[i]["add_menu"] == 1){
            temp++;
          }
        }
        if(temp == this.basicItems.length) {
          allChecked = false;
        }
        if(!allChecked) {
          for(var i=0; i< this.basicItems.length; i++) {
            this.basicItems[i]["add_menu"] = 0;
          }
        } else {
          for(var i=0; i< this.basicItems.length; i++) {
            this.basicItems[i]["add_menu"] = 1;
          }
        }
      }
    },
    computed: {
      computeBasicItemNum: function () {
        var number = 0;
        //这里不能使用"app4"，只能使用"this"
        for(var i = 0; i<this.basicItems.length; i++) {
          if(this.basicItems[i]['add_menu'] == 1) {
            number++;
          }
        }
        return number;
      },
      basicAllChecked: function () {
        return this.computeBasicItemNum == this.basicItems.length;
      }
    },
  })

  var app5 = new Vue({
    el: '#others',
    data: {
      groups:eval('('+'<?php echo json_encode($otherItems);?>'+')'),
      showStatus:[],
      checkedNum:[],
      allCheckedStatus:[]
    },
    methods: {
      showOrHideItems: function (index) {
        app5.$set(app5.showStatus,index,!app5.showStatus[index]);
      },
      checkItem: function (index) {
          var num = 0;
        for(var i=0; i< this.groups[index]['items'].length; i++) {
          if(this.groups[index]['items'][i]['add_menu'] == 1) {
              num++;
          }
        }
        app5.$set(app5.checkedNum,index,num);
        if( num == this.groups[index]['items'].length ) {
            this.allCheckedStatus[index] = true;
        }else {
            this.allCheckedStatus[index] = false;
        }
      },

      checkAllItemOnOff: function (index) {
//        disableParentEvent(e);
        var allChecked = true;
        var temp = 0;
        //先检查接下来的动作是全部选上还是全部不选
        for(var i=0; i< this.groups[index]['items'].length; i++) {
          if(this.groups[index]['items'][i]["add_menu"] == 1){
            temp++;
          }
        }
        if(temp == this.groups[index]['items'].length) {
          allChecked = false;
        }
        if(!allChecked) {
          for(var j=0; j< this.groups[index]['items'].length; j++) {
            this.groups[index]['items'][j]["add_menu"] = 0;
          }
          app5.$set(app5.checkedNum,index,0);
          this.allCheckedStatus[index] = false;
        } else {
          for(var k=0; k< this.groups[index]['items'].length; k++) {
            this.groups[index]['items'][k]["add_menu"] = 1;
          }
          app5.$set(app5.checkedNum,index,this.groups[index]['items'].length);
          this.allCheckedStatus[index] = true;
        }
      }
    }
  })

  var app6 = new Vue({
    el: '#service',
    data: {
      submitInstallURL:"<?php echo U('install/submitInstallOrder');?>",
      choosedPerson: {},
      sevicePersons: eval('(<?php echo json_encode($servicePersons);?>)'),
      installDate: '',
      errorMsg: '',
      deviceSN: '',
      basicNum: '',
      basicIDs: [],
      otherNum: '',
      otherIDs: [],
      customerName: ''
    },
    methods: {
      createInstallMenu: function () {
        var showError = false;

        //基本项检查
        var basicNum = 0;
        for(var i = 0; i<app4.basicItems.length; i++) {
          if(app4.basicItems[i]['add_menu'] == 1) {
            this.basicIDs.push(app4.basicItems[i]['basic_item_id']);
            basicNum++;
          }
        }

        //基本项以外的其他项检查
        var otherNum = 0;
        for(var j = 0; j<app5.groups.length; j++) {
          for(var k =0; k<app5.groups[j]['items'].length; k++)
          if(app5.groups[j]['items'][k]['add_menu'] == 1) {
            this.otherIDs.push(app5.groups[j]['items'][k]['item_id']);
            otherNum++;
          }
        }

        if(app3.customerName == "" || app3.customerName == null) {
          this.errorMsg = "请先将机器绑定至指定客户！";
          showError = true;
        } else if(basicNum == 0){
          this.errorMsg = "基本项不能为空，请勾选需要的基本调试项！";
          showError = true;
        } else if(otherNum == 0) {
          this.errorMsg = "其他项（平绣等）不能为空，请勾选需要的调试项！";
          showError = true;
        }else if(this.choosedPerson.fullname == null || this.choosedPerson.fullname == "") {
          this.errorMsg = "请指派调试员！";
          showError = true;
        } else if( this.installDate == null || this.installDate == "") {
          this.errorMsg = "请选择调试时间！";
          showError = true;
        }
        if( showError) {
          $("#notifyError").modal();
        } else {
          app6.basicNum = basicNum;
          app6.otherNum = otherNum;
          app6.deviceSN = app3.deviceSN;
          app6.customerName = app3.customerName;

          $("#notifyConfirm").modal();
        }
      },
      closeModal: function () {
        $("#notifyError").modal('hide');
      },
      submitInstallMenu: function () {
        var basicItemsStr = '';
        for(var i=0; i< this.basicIDs.length; i++){
          if( i == this.basicIDs.length -1) {
            basicItemsStr = basicItemsStr + this.basicIDs[i];
          } else {
            basicItemsStr = basicItemsStr + this.basicIDs[i] + "|";
          }
        }

        var otherItemsStr = '';
        for(var j=0; j< this.otherIDs.length; j++){
          if( j == this.otherIDs.length -1) {
            otherItemsStr = otherItemsStr + this.otherIDs[j];
          } else {
            otherItemsStr = otherItemsStr + this.otherIDs[j] + "|";
          }
        }
        $.ajax({

          //必须在Ajax中进行URL的组装，否则app2.content等参数会为空或者未定义
          url: this.submitInstallURL + "?deviceID=" + app3.deviceSN +
              "&spID=" + this.choosedPerson.customer_id +
              "&basicItems=" + basicItemsStr +
              "&otherItems=" + otherItemsStr +
              "&installDate=" + this.installDate,
          type: 'GET',
          success: function(data) {
            if(data.status) {
              alert(data.info);
            } else {
              alert(data.info);
            }
          },
          error: function(data) {
            alert(data);
          }
        })

        $("#notifyConfirm").modal('hide');
      }
    }
  })


</script>