<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
<head>
  <title>添加客户</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/service/Public/css/bootstrap.min.css">
  <script type='text/javascript' src='/service/Public/js/vue.js'></script>
  <script type='text/javascript' src='/service/Public/js/jquery-3.1.1.min.js'></script>
  <!--<script type='text/javascript' src='/service/Public/js/vue-upload-component/webpack.config.build.min'></script>-->

</head>
<body>
<div class="container" id="app">
  <div class="jumbotron">
    <h1 style="text-align: center">添加客户</h1>
  </div>
  <form class="form-horizontal" role="form" id="form">
    <div class="form-group">
      <label class="control-label col-sm-2" >姓名:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" v-model="registerModel.name" placeholder="输入姓名" style="height: 48px">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">地址:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" v-model="registerModel.address" placeholder="输入地址" style="height: 48px">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">联系方式:</label>
      <div class="col-sm-10">
        <input type="number" class="form-control" v-model="registerModel.phone" placeholder="输入电话" style="height: 48px">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2 bold">邮箱:</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" v-model="registerModel.email" placeholder="输入邮箱地址" style="height: 48px">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >客户类型:</label>
      <div class="col-sm-10">
        <!--<input type="text" class="form-control" v-model="registerModel.device_name" placeholder="输入设备名称" style="height: 48px;float:right">-->
        <select class="form-control" v-model="registerModel.type" style="float:right;width: 100%; height: 48px">
          <option v-for="option in options" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>

      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10" style="text-align: right">
        <button type="button" class="btn btn-primary" v-on:click="register" style="width: 100px; height: 36px">提交</button>
      </div>
    </div>
  </form>

</div>

</body>
</html>
<script>
  var app = new Vue({
    el: '#app',
    data: {
      registerUrl: "<?php echo U('index/ajaxAddCustomer');?>",
      registerModel: {
        name: "",
        address: "",
        phone: "",
        email: "",
        type: ""
      },
      //机器名称，即种类即名称
      options: [
        {text:"客户", value:'3'},
      ]
    },
    methods: {
      register: function() {
        var vm = this

        //验证表单
        var pass = true;
        if(this.registerModel.name.length == 0) {
          alert("姓名不能为空！");
          pass = false;
        }
        if(pass && this.registerModel.address.length == 0) {
          alert("地址不能为空！");
          pass = false;
        }
        if(pass && this.registerModel.phone.length == 0) {
          alert("电话不能为空！");
          pass = false;
        } else if(pass){
          var n = 0;
          var type = typeof this.registerModel.phone.toString();
          //做是否为number的判断是因为在html 中设置了input的类型为number
          //所以这里处理的是number对象
          if(type = "number") {
            n = this.registerModel.phone.toString().length;
          }else if(type == "string") {
              n = this.registerModel.phone.length;
          }
          if( n != 11 && n != 12) {
            alert("请输入11位或者12位号码");
            pass = false;
          }
        }
        if(pass && this.registerModel.email.length == 0) {
          alert("Email不能为空！");
          pass = false;
        } else {
          var temps = this.registerModel.email.split("@",2);

          if(pass &&  temps.length != 2) {
            alert("邮箱格式不正确！");
            pass = false;
          }
        }
        if(pass && this.registerModel.type.length == 0) {
          alert("请选择客户类型！");
          pass = false;
        }

        if( pass ) {
          $.ajax({
            url: vm.registerUrl,
            type: 'POST',
            dataType: 'json',
            data: vm.registerModel,
            success: function(data) {
              if(data.status) {
                window.location.href ="<?php echo U('index/index');?>";
              } else {
                alert(data.info);
              }
            },
            error: function () {
              alert("连接错误！");
            }
          })

        }
      },
    }

  })
</script>