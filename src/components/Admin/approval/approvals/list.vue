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
          <div class="ovf-hd">
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
          </div>
          <div class="pos-rel p-t-20">
              <div class="block tx-r">
                <el-pagination
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage"
                    :page-size="limit"
                    layout="prev, pager, next, jumper"
                    :total="dataCount">
                </el-pagination>
              </div>
            </div>
        </el-col>
        <el-col :span="10">
            <el-row class="dailies-state">
              <el-button type="primary"> 审核通过</el-button>
              <el-button type="success">客户通过</el-button>
              <el-button type="warning">已审核</el-button>
            </el-row>
          <!-- <el-row :gutter="20" class="dailies-el"> -->
            <div class="imagebox dailies-video" id="signx">
			      	<img id="imgs" src="../../../../assets/images/bg1.jpg" >
		        </div>
            <div>
              <button id="capture" @click="dialogVisible = true">截图</button>
              <button id="play">播放/暂停</button>
              <input type="range" min="0" value="0" id="range" step="0.1"/>
            </div>
            <!-- <div id="output"></div> -->
            <!-- <div @click="addField">1222</div> -->
          <!-- </el-row> -->
          <!-- <el-row :gutter="20" class="m-b-5 dailies-information">
              <el-col :span="24">
                <p class="m-0">Dailies<span>11</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务优先级：</span><span>11</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务优先级：</span><span>11</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
          </el-row> -->
          <el-collapse v-model="activeNames" @change="handleChange">
            <el-collapse-item title="Dailies" name="1">
              <el-col :span="12">
                <p class="m-0"><span>任务优先级：</span><span>11</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务优先级：</span><span>11</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0"><span>任务难度：</span><span>222</span></p>
              </el-col>
            </el-collapse-item>
            <el-collapse-item title="反馈信息" name="1">
              <div class="Dailies-text">
                <el-col :span="6">
                  <img class="Dailies-headPortrait" src="../../../../assets/images/bg1.jpg">
                </el-col>
                <el-col :span="18">
                  <p class="m-0"><span>赵九四:这张图片是错的</span><span></span></p>
                  <p class="m-0"><span>赵九1:这张图片是错的</span><span></span></p>
                </el-col>
              </div>
              <div class="Dailies-text">
                <el-col :span="6">
                  <img class="Dailies-headPortrait" src="../../../../assets/images/bg1.jpg">
                </el-col>
                <el-col :span="18">
                  <p class="m-0"><span>赵九四:这张图片是错的</span><span></span></p>
                  <p class="m-0"><span>赵九1:这张图片是错的</span><span></span></p>
                </el-col>
              </div>
            </el-collapse-item>
          </el-collapse>
        </el-col>
      </el-row>
    </div>
    <!-- <div class=""> -->
      <el-dialog
          title="提示"
          :visible.sync="dialogVisible"
          width="30%"
          :before-close="handleClose">
          <span>这是一段信息</span>
          <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="dialogVisible = false">上传</el-button>
          </span>
        </el-dialog>
    <!-- </div> -->
  </div>
</template>
<script>
  import fomrMixin from '@/assets/js/form_com'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  import '@/assets/js/jquery-1.11.1.min.js'
  import '@/assets/js/jquery-sign.js'
  import '@/assets/js/html2canvas.js'
  import '@/assets/css/signStyle.css'

   export default {
       data () {
        return{
          limit: 10,
          currentPage: 1,
          dataCount: null,
          activeNames: ['1'],
          dialogVisible: false,
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
          form:{
            shot_number:'12'
          },

        } 
       },
       created() {
           $.sign.bindSign('#signx');//初始化
          
           
       },
       mounted(){
          let thiss=this
           $('#capture').click(function(){

               console.log(122)
               html2canvas(document.getElementById('signx'), {
                // allowTaint: true,
                taintTest: false,
                onrendered: function (canvas) {
                  console.log(canvas)
                  canvas.crossOrigin = "Anonymous"
                    var imgData = canvas.toDataURL("png");
                    var img=new Image();
                    img.src=imgData;
                    console.log(imgData)
                    thiss.fieldForm.images=imgData
                    // document.getElementById("output").appendChild(img);
                    // canvas.id = "mycanvas";    
                    // //生成base64图片数据 
                    // // canvas.setAttribute('crossOrigin', 'anonymous');   
                    // var dataUrl = canvas.toDataURL();    
                    // console.log(dataUrl)
                    // var newImg = document.createElement("img");    
                    // newImg.src =  dataUrl;    
                    // document.getElementById("output").appendChild(newImg);    
                },useCORS:true
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
          handleChange(val) {
            console.log(val);
          },
         //      切换页码
          handleCurrentChange(page) {
            this.getAllStudios(page)
          },
          handleClose(done) {
            this.$confirm('确认关闭？')
              .then(_ => {
                done();
              })
              .catch(_ => {});
          },
         addField(form) {
           console.log(this.fieldForm)
            this.apiPost('admin/image_base64', this.fieldForm).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                setTimeout(() => {
                  this.isAddField = false
                  this.getFields()
                }, 1500)
                this.isLoading = !this.isLoading
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
        },
       },
      mixins: [http, fomrMixin],
   }
</script>
<style>
body,html,div,ul,li,a{
	margin:0;
	padding:0;
	}
.approvals_list .imagebox{
	width:600px;
    height:auto;
    margin:0 auto;
    position:relative;
}
.approvals_list .imagebox img{
	width:100%;
}
.approvals_list #myCanvas{
    display: none;
}
.approvals_list .dailies-information{
  padding: 20px;
}
.approvals_list .Dailies-text{
  height: 150px;
  padding: 10px;
}
.approvals_list .Dailies-headPortrait{
 width: 100%;
}
.approvals_list .dailies-el{
  margin-left:0px;
}
.approvals_list .dailies-state {
  text-align: right;
}
</style>

