<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
<head>
  <title>SinSim</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" href="/service/Public/css/mystyle.css"rel="stylesheet"/>
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <script type='text/javascript' src='/service/Public/js/vue.js'></script>
  <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>

</head>
<body style="zoom: 100%">

 <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header" style="margin-top: 5px">
      <a class="sinsim-logo"   href="#">SinSim</a>
    </div>
<!--     <div id="app">
      {{ message }}
    </div> -->
    <ul class="nav navbar-nav">
      <!-- <li><a href="#">Home</a></li> -->
      <li class="active"><a href="#">客户管理</a></li>
      <li><a href="<?php echo U('machine/index');?>">机器管理</a></li>
      <li><a href="<?php echo U('install/index');?>">安装调试</a></li>
      <li><a href="<?php echo U('maintain/index');?>">设备保养</a></li>
      <li><a href="<?php echo U('DeviceProblem/Problem');?>">设备维修</a></li>
      <li><a href="#">配件更换</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span>登录</a></li>
    </ul>
  </div>
</nav>
 <div style="margin-bottom: 10px" id="button-click" class="container-fluid">
   <button type="button" class="btn btn-primary" style="height: 44px;" v-on:click="add_customer">
     <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>创建客户</button>
   <div style="display: inline; margin-left:10px" v-show="show">
     <button type="button" class="btn btn-danger" style="height: 44px;" v-on:click="disable_customer">
       <span class="glyphicon glyphicon-remove" style="padding-right: 5px"></span>禁用客户</button>
   </div>
 </div>

 <div id="users" class="container-fluid">
  <TABLE BORDER=1 WIDTH=100% align="center" style="table-layout: fixed" >
    <thead style="background-color: SkyBlue; height: 48px; line-height: 48px">
      <TR>
        <th style="width: 36px; text-align: center"></th>
        <th style="width: 120px;text-align: center">会员姓名</th>
        <th style="text-align: center">电话</th>
        <th  style="text-align: center">地址</th>
        <th  style="text-align: center">Email</th>
        <th  style="text-align: center">添加时间</th>
        <th style="width: 72px;text-align: center">修改</th>
      </TR>
    </thead>
    <TBODY>
      <?php if(is_array($users)): $k = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($k % 2 );++$k;?><TR style="height: 36px; text-align: center">
          <td>
            <input type="checkbox" value="<?php echo ($user['fullname']); ?>" v-model="checkedNames" v-on:change="add_disable_list">
          </td>
          <TD>
            <div v-on:click="user_detail(<?php echo ($user['customer_id']); ?>)" style="font-weight: bold" class="btn btn-link"><?php echo ($user['fullname']); ?> </div>
          </TD>
          <TD>
            <div><?php echo ($user['telephone']); ?></div>
          </TD>
          <TD>
            <div><?php echo ($user['address']); ?></div>
          </TD>
          <TD>
            <div><?php echo ($user['email']); ?></div>
          </TD>
          <TD>
            <div><?php echo ($user['date_added']); ?></div>
          </TD>
          <td>
            <button class="btn btn-primary btn-sm">
              <span class="glyphicon glyphicon-edit"></span></button>
          </td>
        </TR><?php endforeach; endif; else: echo "" ;endif; ?>
    </TBODY>
  </TABLE>
 </div>

</body>
</html>

<script>
  var app = new Vue({
    el: '#button-click',
    data: {
      show: false,
      deleteUrl: "<?php echo U('index/ajaxDisableCustomer');?>",
      deleteItems: {
        "ids":[]
      },
    },
    methods: {
      add_customer: function () {
        window.location.href="<?php echo U('index/addCustomer');?>";
      },
      disable_customer: function () {
//          alert(app2.checkedNames);
        var sure = confirm("确认禁用客户吗？");
        if(sure) {
          //组装JSON
          app.deleteItems.ids = app2.checkedNames;
          $.ajax({
            url: app.deleteUrl,
            type: 'POST',
            dataType: 'json',
            data: app.deleteItems,
            success: function(data) {
              if(data.status) {
                window.location.href ="<?php echo U('index/index');?>";
              } else {
                alert(data.info);
              }
            },
          })
        }
      }
    },
  })

  var app2 = new Vue({
    el: '#users',
    data: {
      checkedNames: [],
      frontSize:'15'
    },

    methods: {
      add_disable_list: function () {
        if(app2.checkedNames.length > 0) {
          app.show = true;
        } else {
          app.show = false;
        }
      },

      user_detail:function (id) {
        window.location.href ="<?php echo U('index/checkCustomerDevice');?>?customerID=" + id ;
      },
    }
  })
</script>