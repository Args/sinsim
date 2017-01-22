<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml"
      xmlns:v-if="http://www.w3.org/1999/xhtml">
<head>
  <title>机器管理</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" href="/service/Public/css/mystyle.css"rel="stylesheet"/>
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <script type='text/javascript' src='/service/Public/js/vue.js'></script>
  <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>

</head>
<body>
 <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header" style="margin-top: 5px">
      <a class="sinsim-logo" href="#">SinSim</a>
    </div>
    <ul class="nav navbar-nav">
      <!-- <li><a href="#">Home</a></li> -->
      <li><a href="<?php echo U('index/index');?>">客户管理</a></li>
      <li class="active"><a href="#">机器管理</a></li>
      <li><a href="<?php echo U('install/index');?>">安装调试</a></li>
      <li><a href="<?php echo U('maintain/index');?>">设备保养</a></li>
      <li><a href="<?php echo U('DeviceProblem/Problem');?>">设备维修</a></li>
      <li><a href="#">配件更换</a></li>
    </ul>
  </div>
</nav>
 <div id="button-click" style=" margin-bottom: 10px" class="container-fluid">
   <button type="button" class="btn btn-primary"  v-on:click="add_device" style="height: 44px;">
     <span class="glyphicon glyphicon-plus" style="padding-right: 5px"></span>添加机器</button>
   <div style="display: inline; margin-left:10px" v-if="show">
     <button type="button" class="btn btn-danger"  v-on:click="delete_device" style="height: 44px;">
       <span class="glyphicon glyphicon-minus" style="padding-right: 5px"></span>删除机器</button>
   </div>
 </div>
 <div id="delete-machines" class="container-fluid">
   <TABLE BORDER=1 WIDTH=100% align="center">
     <thead>
       <TR style="background-color: SkyBlue; height: 48px; text-align: center" >
         <th style="width: 36px"></th>
         <th style="text-align: center">设备ID</th>
         <th style="text-align: center">设备名称</th>
         <th style="text-align: center">设备概述</th>
         <th style="text-align: center">出厂日期</th>
         <th style="text-align: center">设备录入日期</th>
       </TR>
     </thead>
     <TBODY>
       <?php if(is_array($machines)): $i = 0; $__LIST__ = $machines;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$machine): $mod = ($i % 2 );++$i;?><TR style="height: 36px; text-align: center">
           <td>
             <input type="checkbox" name="deleteItem" id="<?php echo ($machine['device_sn']); ?>" value="<?php echo ($machine['device_sn']); ?>" v-model="checkedNames" v-on:change="add_delete_list">
           </td>
           <TD>
             <div><?php echo ($machine['device_sn']); ?></div>
           </TD>
           <TD>
             <div><?php echo ($machine['type_name']); ?></div>
           </TD>
           <TD>
             <div><?php echo ($machine['device_des']); ?></div>
           </TD>
           <TD>
             <div><?php echo ($machine['date_production']); ?></div>
           </TD>
           <TD>
             <div><?php echo ($machine['date_added']); ?></div>
           </TD>
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
      deleteUrl: "<?php echo U('machine/ajaxDelete');?>",
      deleteItems: {
        "ids":[]
      }
    },
    methods: {
       add_device: function () {
           window.location.href="<?php echo U('machine/add');?>";
       },
      delete_device: function () {
//          alert(app2.checkedNames);
        var sure = confirm("确认删除选择机器？");
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
                window.location.href ="<?php echo U('machine/index');?>";
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
    el: '#delete-machines',
    data: {
      checkedNames: [],
    },
    methods: {
      add_delete_list: function () {
        if(app2.checkedNames.length > 0) {
          app.show = true;
        } else {
          app.show = false;
        }
      }
    }
  })
</script>