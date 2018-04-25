<template>
  <div class="approvals_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/approvals/list' }">审批管理</el-breadcrumb-item>
        <el-breadcrumb-item>审批列表</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 ovf-hd">
      <div class="fl w-1500">
        <!-- <template> -->
        <el-row :gutter="10" class="m-b-5">
          <el-col :span="4">
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="screenChange(1)">
              <el-option
                v-for="item in screeningProject"
                :key="item.id"
                :label="item.project_name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="4">
            <el-select v-model="search.type"  style="margin-left: 10px;" placeholder="请选择类型" @change="screenChange(2)">
              <el-option label="镜头" value="1"></el-option>
              <el-option label="资产" value="2"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id"  style="margin-left: 10px;" placeholder="请选择场号或资产类型" @change="screenChange(3)">
              <el-option
                v-for="item in screeningSite"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.shot_id"  style="margin-left: 10px;" placeholder="请选择镜头号或资产简称" @change="screenChange()">
              <el-option
                v-for="item in screeningShot"
                :key="item.id"
                :label="item.shot_number"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
        </el-row> 
        <!-- </template> -->
      </div>
    </div>
    <div>
      <el-row>
        <el-col :span="14">
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
            </el-col>
        </el-col>
        <el-col :span="10">
            <div class="imagebox dailies-video" id="signx">
			      	<!-- <img id="imgs" src="../../../../assets/images/bg1.jpg" > -->
               

                <img id="imgs" src="http://127.0.0.1:8888/uploads/Projects/images/20180424/699b8793314dc874d0e66748ff4a97c9.jpg">
		        </div>
            <button id="capture">Capture</button>
            <div id="output"></div>
            <!-- <canvas id="myCanvas" width="895"  height="517">
			</canvas>  -->
      <!--整个画布-->
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
//   import $ from 'jquery'
  import '@/assets/js/jquery-1.11.1.min.js'
  import '@/assets/js/jquery-sign.js'
  import '@/assets/js/html2canvas.js'
  import '@/assets/css/signStyle.css'

   export default {
       data () {
        return{
          screeningProject:[],//项目下拉列表数据
        // screeningType:[],//类型
          screeningSite:[],//场号下拉列表数据
          screeningShot:[],
          search:{
            project_id:'',//项目下拉框选中的值
            type:'',//类型
            field_id:'',//场号下拉框选中的值
            shot_id:'',//镜头选中的值
            shot_number:''//输入狂的值
          },
        } 
       },
       created() {
           $.sign.bindSign('#signx');//初始化
          
           
       },
       mounted(){
           $('#capture').click(function(){

               console.log(122)
               html2canvas(document.getElementById('signx'), {
                allowTaint: true,
                taintTest: false,
                onrendered: function (canvas) {
                  console.log(canvas)
                    var imgData = canvas.toDataURL("png");
                    var img=new Image();
                    img.src=imgData;
                    console.log(imgData)
                    document.getElementById("output").appendChild(img);
                    // canvas.id = "mycanvas";    
                    // //生成base64图片数据 
                    // // canvas.setAttribute('crossOrigin', 'anonymous');   
                    // var dataUrl = canvas.toDataURL();    
                    // console.log(dataUrl)
                    // var newImg = document.createElement("img");    
                    // newImg.src =  dataUrl;    
                    // document.getElementById("output").appendChild(newImg);    
                }
            });
           })
          // function getBase64Image(img) {
          //   console.log(img)
          //         var canvas = document.createElement("canvas");
          //         canvas.width = img.width;
          //         canvas.height = img.height;
          //         var ctx = canvas.getContext("2d");
          //         ctx.drawImage(img, 0, 0, img.width, img.height);
                  
          //         // var ext = img.src.substring(img.src.lastIndexOf(".")+1).toLowerCase();
          //         // console.log(ext)
          //         var dataURL = canvas.toDataURL();
          //         console.log(dataURL,123)
          //         return dataURL;
          // }
          // $('#capture').click(function(){
          //   var image = new Image();
          //   image.crossOrigin = 'anonymous';
          //   image.src = $('#imgs').attr('src');
          //   var base64 = getBase64Image(image);
          //   console.log(base64);
          // })
           	// (function() {
            //     "use strict";
            //     var video, $capture;
            //     var scale = 0.25;
            //     var initialize = function() {
            //         $capture = $("#capture");
            //         // video = $("#video").get(0);
            //         video=$('.dailies-video>img').get(0)
            //         $("#capture").click(captureImage);        
            //     };
            //     var captureImage = function() {
            //         var canvas = document.createElement("canvas");
            //         var str=$('.dailies-video>img').css('width').substr(0,$('.dailies-video>img').css('width').length-2)
            //         // canvas.width = video.videoWidth * scale;
            //         // canvas.height = video.videoHeight * scale;
            //         canvas.height = 517;
            //         canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            //         var img = document.createElement("img");
            //         img.src = canvas.toDataURL();
            //         $capture.prepend(img);
            //         var ce=document.getElementById("myCanvas");
            //         var ctx=ce.getContext("2d");
            //         var img=new Image();
            //         ctx.clearRect(0,0,895,canvas.height);
            //         img.onload = function(){
            //         ctx.drawImage(img,0,0,canvas.width,canvas.height);
            //         };
            //         img.src=canvas.toDataURL();
            //     };
            //     $(initialize);      
            // }());
        
       },
       methods: {

       }
   }
</script>
<style>
body,html,div,ul,li,a{
	margin:0;
	padding:0;
	}
.imagebox{
	width:600px;
    height:auto;
    margin:0 auto;
    position:relative;
}
.imagebox img{
	width:100%;
}
#myCanvas{
    display: none;
}
</style>

