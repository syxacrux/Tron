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
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="screenChange()">
              <el-option
                v-for="item in screeningProject"
                :key="item.id"
                :label="item.project_name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="4">
            <el-select v-model="search.type"  style="margin-left: 10px;" placeholder="请选择类型" @change="typeChange()">
              <el-option label="镜头" value="1"></el-option>
              <el-option label="资产" value="2"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id"  style="margin-left: 10px;" placeholder="请选择场号或资产类型" @change="siteChange()">
              <el-option
                v-for="item in screeningSite"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.shot_id"  style="margin-left: 10px;" placeholder="请选择镜头号或资产简称" @change="lensChange()">
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
            <el-col :span="6" class="parent"  v-for="(data , index) in array" :key="data.id">
                <div class="grid-content p-b-5 ">
                  <el-card class="ovf-hd picture">
                    {{data}}
                  </el-card>
                </div>
                <div v-if="type === 'A'" class="grid-content p-b-5 subelement">
                  <!-- <el-card class="ovf-hd picture"> -->
                   审核通过
                  <!-- </el-card> -->
                </div>
                <div v-else-if="type === 'B'" class="grid-content p-b-5 subelement">
                  <!-- <el-card class="ovf-hd picture"> -->
                   客户通过
                  <!-- </el-card> -->
                </div>
                <div v-else class="grid-content p-b-5 subelement">
                  <!-- <el-card class="ovf-hd picture"> -->
                   已审核
                  <!-- </el-card> -->
                </div>
            </el-col>
            <el-col :span="6">
                <div class="grid-content p-b-5">
                  <el-card class="ovf-hd picture">
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
            <el-row class="dailies-state ">
              <el-button type="info">复原</el-button>
              <el-button type="primary"> 审核通过</el-button>
              <el-button type="success">客户通过</el-button>
              <el-button type="warning">已审核</el-button>
            </el-row>
            <!-- <div class="imagebox dailies-video" id="signx">
			      	<img id="imgs" src="../../../../assets/images/bg1.jpg" >
		        </div> -->
            <div class="imagebox1 dailies-video">
			      	<!-- <img id="imgs" src="../../../../assets/images/bg1.jpg" > -->
              <video id='playVideo' width="600" controls src="../../../../assets/images/h264_32.3.mp4"></video>
		        </div>
            <div>
              <button id="capture" @click="isTaskDetailShow = true">截图</button>
              <!-- <button id="play">播放/暂停</button> -->
              <!-- <input type="range" min="0" value="0" id="range" step="0.1"/> -->
            </div>
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
            <el-collapse-item title="反馈信息" name="2">
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
    <el-card class="box-card"  v-show="isTaskDetailShow">
      <div slot="header" class="clearfix">
        <span>截图</span>
        <i class="el-icon-close fr pointer" @click="isTaskDetailShow = !isTaskDetailShow"></i>
      </div>
      <div class="text item">
            <img :src="fieldForm.images">
            <div class="imagebox" id="signx">
              <canvas class="sketchpad" id="sketchpad"></canvas>
            </div>
            <div style="text-align: center">
                <!-- <button id='undo'>undo</button>
                <button id='redo'>redo</button> -->
                <input id="color-picker" type="color">
                <input id="size-picker" type="range" min="1" max="50">
                <!-- <button id='animateSketchpad'>animate</button> -->
              </div>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogVisible = false">取 消</el-button>
              <el-button type="primary" @click="dialogVisible = false">上传</el-button>
              <el-button  id='tg_tijiao12'>生成图片</el-button>
            </span>
      </div>
    </el-card>
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

  import '@/assets/css/normalize.css'
  // import '@/assets/css/htmleaf-demo.css'
  import '@/assets/css/sketchpad.css'
  import '@/assets/js/sketchpad.js'
  

   export default {
       data () {
        return{
          limit: 10,
          currentPage: 1,
          dataCount: null,
          activeNames: ['1'],
          dialogVisible: false,
          isTaskDetailShow:false,
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
          array:[
            '12335234234234431233',
            '12335234234234431233',
          ],
          fieldForm:{
            images:''
          },
          num:0,//计时器
          Counter:0,//计时器状态
          type:'C',
          int:null,
        } 
       },
       mounted(){
          let thiss=this
          let int
          $('#capture').click(function(){
              /* html2canvas(document.getElementById('signx'), {
                // allowTaint: true,
                taintTest: false,
                onrendered: function (canvas) {
                  console.log(canvas)
                  canvas.crossOrigin = "Anonymous"
                    let imgData = canvas.toDataURL("png");
                    var img=new Image();
                    img.src=imgData;
                    thiss.fieldForm.images=imgData
                    $('.signIndex,.hintBox').remove()
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
              }); */
              // var str=$('.dailies-video>img').css('width').substr(0,$('.dailies-video>img').css('width').length-2)
              // var str1=$('.dailies-video>img').css('height').substr(0,$('.dailies-video>img').css('height').length-2)
              var str=$('.dailies-video>video').css('width').substr(0,$('.dailies-video>video').css('width').length-2)
              var str1=$('.dailies-video>video').css('height').substr(0,$('.dailies-video>video').css('height').length-2)
              sketchpad = new Sketchpad({
                element: '#sketchpad',
                width: str,
                height: str1
              });
              sketchpad.penSize = 1
          });
              //视屏播放器的控制设置
          var videos=document.getElementById('playVideo')
            $(document).keydown(function(e){
                var key=e.which
                if(key==32){
                  e.preventDefault();
                    if(videos.paused){
                videos.play();
              }else{
                videos.pause();
              }
              return false;
                }
                if(key==39){
                  videos.pause();
              videos.currentTime+=0.042
            }
            if(key==37 && videos.currentTime!=0){
              videos.pause();
              videos.currentTime-=0.042;
            }
            });
          (function() {
            "use strict";
            var video, $capture;
            var scale = 0.3;
            var initialize = function() {
              $capture = $("#capture");
              // video = $("#video").get(0);
              // video=$('.dailies-video>img').get(0);
              video=$('.dailies-video>video').get(0)
              $("#capture").click(captureImage);        
            };
            var captureImage = function() {
              var canvas = document.createElement("canvas");
              // canvas.width = video.videoWidth * scale;
              // canvas.height = video.videoHeight * scale;
              // var str=$('.dailies-video>img').css('width').substr(0,$('.dailies-video>img').css('width').length-2)
               var str=$('.dailies-video>video').css('width').substr(0,$('.dailies-video>video').css('width').length-2)
               canvas.width =str * 1;
              // var str1=$('.dailies-video>img').css('height').substr(0,$('.dailies-video>img').css('height').length-2)
              var str1=$('.dailies-video>video').css('height').substr(0,$('.dailies-video>video').css('height').length-2)
		     	    canvas.height =str1 * 1;
              canvas.getContext('2d')
                .drawImage(video, 0, 0, canvas.width, canvas.height);
              var img = document.createElement("img");
              img.src = canvas.toDataURL();
              // $output.prepend(img);
              var ce=document.getElementById("sketchpad");
              var ctx=ce.getContext("2d");
              var img=new Image();
                img.onload = function(){
                ctx.drawImage(img,0,0,canvas.width,canvas.height);
                };
                img.src=canvas.toDataURL();;
            };
            $(initialize);      
          }());
          //播放暂停
          $('#play').click(function(){
            clearTimeout(int);
            if(thiss.Counter == 0){
              int=setInterval(function(){
                thiss.num=thiss.num+1
                console.log(thiss.num)
                // $('#imgs').attr('src',as[1])
                thiss.Counter=1
              },1000)
            }else{
              clearTimeout(int);
              thiss.Counter = 0
            }
          });
          
          function Sketchpad(config) {
            // Enforces the context for all functions
            for (var key in this.constructor.prototype) {
              this[key] = this[key].bind(this);
            }

            // Warn the user if no DOM element was selected
            if (!config.hasOwnProperty('element')) {
              console.error('SKETCHPAD ERROR: No element selected');
              return;
            }

            if (typeof(config.element) === 'string') {
              this.element = $(config.element);
            }
            else {
              this.element = config.element;
            }

            // Width can be defined on the HTML or programatically
            this._width = config.width || this.element.attr('data-width') || 0;
            this._height = config.height || this.element.attr('data-height') || 0;

            // Pen attributes
            this.color = config.color || this.element.attr('data-color') || '#000000';
            this.penSize = config.penSize || this.element.attr('data-penSize') || 5;

            // ReadOnly sketchpads may not be modified
            this.readOnly = config.readOnly ||
                            this.element.attr('data-readOnly') ||
                            false;
            if (!this.readOnly) {
                this.element.css({cursor: 'crosshair'});
            }

            // Stroke control variables
            this.strokes = config.strokes || [];
            this._currentStroke = {
              color: null,
              size: null,
              lines: [],
            };

            // Undo History
            this.undoHistory = config.undoHistory || [];

            // Animation function calls
            this.animateIds = [];

            // Set sketching state
            this._sketching = false;

            // Setup canvas sketching listeners
            this.reset();
          }

          //
          // Private API
          //

          Sketchpad.prototype._cursorPosition = function(event) {
            return {
              x: event.pageX - $(this.canvas).offset().left,
              y: event.pageY - $(this.canvas).offset().top,
            };
          };

          Sketchpad.prototype._draw = function(start, end, color, size) {
            this._stroke(start, end, color, size, 'source-over');
          };

          Sketchpad.prototype._erase = function(start, end, color, size) {
            this._stroke(start, end, color, size, 'destination-out');
          };

          Sketchpad.prototype._stroke = function(start, end, color, size, compositeOperation) {
            this.context.save();
            this.context.lineJoin = 'round';
            this.context.lineCap = 'round';
            this.context.strokeStyle = color;
            this.context.lineWidth = size;
            this.context.globalCompositeOperation = compositeOperation;
            this.context.beginPath();
            this.context.moveTo(start.x, start.y);
            this.context.lineTo(end.x, end.y);
            this.context.closePath();
            this.context.stroke();

            this.context.restore();
          };

          //
          // Callback Handlers
          //

          Sketchpad.prototype._mouseDown = function(event) {
            this._lastPosition = this._cursorPosition(event);
            this._currentStroke.color = this.color;
            this._currentStroke.size = this.penSize;
            this._currentStroke.lines = [];
            this._sketching = true;
            this.canvas.addEventListener('mousemove', this._mouseMove);
          };

          Sketchpad.prototype._mouseUp = function(event) {
            if (this._sketching) {
              this.strokes.push($.extend(true, {}, this._currentStroke));
              this._sketching = false;
            }
            this.canvas.removeEventListener('mousemove', this._mouseMove);
          };

          Sketchpad.prototype._mouseMove = function(event) {
            var currentPosition = this._cursorPosition(event);

            this._draw(this._lastPosition, currentPosition, this.color, this.penSize);
            this._currentStroke.lines.push({
              start: $.extend(true, {}, this._lastPosition),
              end: $.extend(true, {}, currentPosition),
            });

            this._lastPosition = currentPosition;
          };

          Sketchpad.prototype._touchStart = function(event) {
            event.preventDefault();
            if (this._sketching) {
              return;
            }
            this._lastPosition = this._cursorPosition(event.changedTouches[0]);
            this._currentStroke.color = this.color;
            this._currentStroke.size = this.penSize;
            this._currentStroke.lines = [];
            this._sketching = true;
            this.canvas.addEventListener('touchmove', this._touchMove, false);
          };

          Sketchpad.prototype._touchEnd = function(event) {
            event.preventDefault();
            if (this._sketching) {
              this.strokes.push($.extend(true, {}, this._currentStroke));
              this._sketching = false;
            }
            this.canvas.removeEventListener('touchmove', this._touchMove);
          };

          Sketchpad.prototype._touchCancel = function(event) {
            event.preventDefault();
            if (this._sketching) {
              this.strokes.push($.extend(true, {}, this._currentStroke));
              this._sketching = false;
            }
            this.canvas.removeEventListener('touchmove', this._touchMove);
          };

          Sketchpad.prototype._touchLeave = function(event) {
            event.preventDefault();
            if (this._sketching) {
              this.strokes.push($.extend(true, {}, this._currentStroke));
              this._sketching = false;
            }
            this.canvas.removeEventListener('touchmove', this._touchMove);
          };

          Sketchpad.prototype._touchMove = function(event) {
            event.preventDefault();
            var currentPosition = this._cursorPosition(event.changedTouches[0]);

            this._draw(this._lastPosition, currentPosition, this.color, this.penSize);
            this._currentStroke.lines.push({
              start: $.extend(true, {}, this._lastPosition),
              end: $.extend(true, {}, currentPosition),
            });

            this._lastPosition = currentPosition;
          };

          //
          // Public API
          //

          Sketchpad.prototype.reset = function() {
            // Set attributes
            this.canvas = this.element[0];
            this.canvas.width = this._width;
            this.canvas.height = this._height;
            this.context = this.canvas.getContext('2d');

            // Setup event listeners
            this.redraw(this.strokes);

            if (this.readOnly) {
              return;
            }

            // Mouse
            this.canvas.addEventListener('mousedown', this._mouseDown);
            this.canvas.addEventListener('mouseout', this._mouseUp);
            this.canvas.addEventListener('mouseup', this._mouseUp);

            // Touch
            this.canvas.addEventListener('touchstart', this._touchStart);
            this.canvas.addEventListener('touchend', this._touchEnd);
            this.canvas.addEventListener('touchcancel', this._touchCancel);
            this.canvas.addEventListener('touchleave', this._touchLeave);
          };

          Sketchpad.prototype.drawStroke = function(stroke) {
            for (var j = 0; j < stroke.lines.length; j++) {
              var line = stroke.lines[j];
              this._draw(line.start, line.end, stroke.color, stroke.size);
            }
          };

          Sketchpad.prototype.redraw = function(strokes) {
            for (var i = 0; i < strokes.length; i++) {
              this.drawStroke(strokes[i]);
            }
          };

          Sketchpad.prototype.toObject = function() {
            return {
              width: this.canvas.width,
              height: this.canvas.height,
              strokes: this.strokes,
              undoHistory: this.undoHistory,
            };
          };

          Sketchpad.prototype.toJSON = function() {
            return JSON.stringify(this.toObject());
          };

          Sketchpad.prototype.animate = function(ms, loop, loopDelay) {
            this.clear();
            var delay = ms;
            var callback = null;
            for (var i = 0; i < this.strokes.length; i++) {
              var stroke = this.strokes[i];
              for (var j = 0; j < stroke.lines.length; j++) {
                var line = stroke.lines[j];
                callback = this._draw.bind(this, line.start, line.end,
                                          stroke.color, stroke.size);
                this.animateIds.push(setTimeout(callback, delay));
                delay += ms;
              }
            }
            if (loop) {
              loopDelay = loopDelay || 0;
              callback = this.animate.bind(this, ms, loop, loopDelay);
              this.animateIds.push(setTimeout(callback, delay + loopDelay));
            }
          };

          Sketchpad.prototype.cancelAnimation = function() {
            for (var i = 0; i < this.animateIds.length; i++) {
              clearTimeout(this.animateIds[i]);
            }
          };

          Sketchpad.prototype.clear = function() {
            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
          };

          Sketchpad.prototype.undo = function() {
            this.clear();
            var stroke = this.strokes.pop();
            if (stroke) {
              this.undoHistory.push(stroke);
              this.redraw(this.strokes);
            }
          };

          Sketchpad.prototype.redo = function() {
            var stroke = this.undoHistory.pop();
            if (stroke) {
              this.strokes.push(stroke);
              this.drawStroke(stroke);
            }
          };
          
          var sketchpad;
          $(document).ready(function() {
            // sketchpad = new Sketchpad({
            //   element: '#sketchpad',
            //   width: 400,
            //   height: 400
            // });
            $('#color-picker').change(color);
            $('#color-picker').val('#000');
            $('#size-picker').change(size);
            $('#size-picker').val(1);
          });
          $('#undo').click(function(){
            sketchpad.undo();
          })
          // function undo() {
          //   sketchpad.undo();
          // }
          $('#redo').click(function(){
            sketchpad.redo();
          })
          // function redo() {
          //   sketchpad.redo();
          // }
          function color(event) {
            sketchpad.color = $(event.target).val();
          }
          
          function size(event) {
            sketchpad.penSize = $(event.target).val();
          }
          $('#animateSketchpad').click(function(){
            sketchpad.animate(10);
          })
          // function animateSketchpad() {
          //   sketchpad.animate(10);
          // }

	      function recover(event) {
					var settings = sketchpad.toObject();
					console.log(settings)
	        settings.element = '#other-sketchpad';
					var otherSketchpad = new Sketchpad(settings);
					// console.log(otherSketchpad)
	        // $('#recover-button').hide();
        }
        $("#tg_tijiao12").on("click", function(event) {
            event.preventDefault();   
            html2canvas(document.getElementById("signx"), {    
              allowTaint: true,    
              taintTest: false,    
              onrendered: function(canvas) {    
                canvas.id = "sketchpad";    
                                                //document.body.appendChild(canvas);    
                                                //生成base64图片数据    
                var dataUrl = canvas.toDataURL();
                console.log(dataUrl)
                 thiss.fieldForm.images=dataUrl

              }    
            }); 
          
        }); 

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
          //开始
        //提交截图
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
        getsprojects(){
          // this.apiGet('admin/projects')
          this.apiGet('admin/projects').then((res) => {
            this.handelResponse(res, (data) => {
              this.screeningProject = data.list
            })
          })
        },
        screenChange(){
          this.search.field_id = ''
          this.search.shot_id = ''
          const data = {
            params: {
              project_id: this.search.project_id
            }
          }
          this.apiGet('admin/get_fields', data).then((res) => {//请求项目下的场号
            this.handelResponse(res, (data) => {
              // this.screeningSite = data
            })
          })
        },
        typeChange(){
          this.search.field_id = ''
          this.search.shot_id = ''
          const datatype = {
            params: {
              project_id: this.search.project_id,
              type: this.search.type
            }
          }
          this.apiGet('admin/get_fields', datatype).then((res) => {//请求项目下的场号
            this.handelResponse(res, (data) => {
             this.screeningSite = data
              
            })
          })
        },
        siteChange(){
          this.search.shot_id = ''
            const datas = {
              params: {
                field_id: this.search.field_id
              }
            }
            this.apiGet('admin/get_shot_num', datas).then((res) => {//请求项目下的场号
              this.handelResponse(res, (data) => {
                this.screeningShot = data
              })
            })
        },
        lensChange(){

        },
        init() {
          this.getsprojects()
        },
       },
      created() {
        this.init()
         $.sign.bindSign('#signx');//初始化
      },
      mixins: [http, fomrMixin],
   }
</script>
<style>
body,html,div,ul,li,a{
	margin:0;
	padding:0;
	}
.approvals_list .imagebox,.approvals_list .imagebox1{
	width:600px;
    height:auto;
    margin:5px auto;
    position:relative;
}
.approvals_list .imagebox1 img{
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
.approvals_list .parent{
  position: relative;
}
.approvals_list .subelement{
  position: absolute;
  top: 3px;
  text-align: center;
  background-color: rgba(0,0,0,0.5);
  color: #67c23a;
  /* height: 148px; */
  line-height: 140px;
  width: 99%;
  border-radius: 4px;
}
.approvals_list .subelement div{
  background-color: rgba(0,0,0,0.3);
  color: #67c23a;
}
.approvals_list .picture{
  height: 148px;
}

.sketchpad {
    background: #FFF;
    /* width: 400px;
    height: 400px; */
    border-radius: 2px;
    -webkit-box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
    -moz-box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
    box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
  }

.approvals_list .text {
    font-size: 14px;
  }

.approvals_list .item {
    margin-bottom: 18px;
  }

.approvals_list .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
.approvals_list  .clearfix:after {
    clear: both
  }

.approvals_list  .box-card {
    width: 40%;
    position:fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
  }
</style>

