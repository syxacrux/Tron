<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/shots/list' }">镜头管理</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/shots/list' }">镜头汇总</el-breadcrumb-item>
        <el-breadcrumb-item>添加镜头</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-l-50 m-t-30 w-1000">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="shot_add">
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="项目名称:" prop="project_id">
                <el-select v-model="form.project_id" placeholder="请选择项目名称">
                  <el-option label="我是项目名称" value="1"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="场号:" prop="field_id">
                <el-select v-model="form.field_id" placeholder="请选择场号">
                  <el-option label="我是场号1" value="1"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <el-form-item class="is-required" label="项目缩略图:" prop="image">
              <!-- 上传照片地址 action="" -->
              <el-upload
                  class="avatar-uploader"
                  :action="uploadImageUrl"
                  :show-file-list="false"
                  :on-success="handleAvatarSuccess"
                  :before-upload="beforeAvatarUpload">
                <img v-if="form.shot_image" :src="image" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头编号:" prop="shot_number">
                <el-input v-model.trim="form.shot_number" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头简称:" prop="shot_byname">
                <el-input v-model.trim="form.shot_byname" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头名称:" prop="shot_name">
                <el-input v-model.trim="form.shot_name" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头备注:" prop="shot_explain">
                <el-input type="textarea" :rows="2" placeholder="请输入镜头备注" v-model="form.shot_explain"
                          class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="剪辑帧长:" prop="clip_frame_length">
                <el-input v-model.trim="form.clip_frame_length" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="帧长范围:" prop="frame_range">
                <el-input v-model.trim="form.frame_range" class="h-40 w-80"></el-input>
                -
                <el-input v-model.trim="form.frame_range" class="h-40 w-80"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头优先级:" prop="priority_level">
                <el-select v-model="form.priority_level" placeholder="请选择镜头优先级" class="h-40 w-200">
                  <el-option label="A" value="4"></el-option>
                  <el-option label="B" value="3"></el-option>
                  <el-option label="C" value="2"></el-option>
                  <el-option label="D" value="1"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="镜头难度:" prop="difficulty">
                <el-select v-model="form.difficulty" placeholder="请选择镜头难度" class="h-40 w-200">
                  <el-option label="S" value="5"></el-option>
                  <el-option label="A" value="4"></el-option>
                  <el-option label="B" value="3"></el-option>
                  <el-option label="C" value="2"></el-option>
                  <el-option label="D" value="1"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="手柄帧:" prop="handle_frame">
                <el-input v-model.trim="form.handle_frame" class="h-40 w-80"></el-input>
                <el-input v-model.trim="form.handle_frame" class="h-40 w-80"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="素材帧长:" prop="material_frame_length">
                <el-input v-model.trim="form.material_frame_length" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="变速信息:" prop="charge_speed_info">
                <el-input type="textarea" :rows="2" placeholder="请输入变速信息" v-model="form.charge_speed_info"
                          class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="时刻:" prop="time">
                <el-select v-model="form.time" placeholder="请选择时刻" class="h-40 w-200">
                  <el-option label="白天" value="1"></el-option>
                  <el-option label="夜晚" value="2"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="环境:" prop="ambient">
                <el-select v-model="form.ambient" placeholder="请选择环境" class="h-40 w-200">
                  <el-option label="室外" value="1"></el-option>
                  <el-option label="室内" value="2"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="素材号:" prop="material_ids">
                <el-select v-model="form.material_ids" multiple collapse-tags placeholder="请选择素材号" class="h-40 w-200">
                  <el-option label="我是素材号1" value="1"></el-option>
                  <el-option label="我是素材号2" value="2"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="相机型号:" prop="camera_model">
                <el-input v-model.trim="form.camera_model" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="相机捕捉:" prop="camera_catch">
                <el-input v-model.trim="form.camera_catch" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="相机运动:" prop="camera_motion">
                <el-select v-model="form.camera_motion" placeholder="请选择环境" class="h-40 w-200">
                  <el-option label="匀速" value="1"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="相机高度:" prop="camera_height">
                <el-input v-model.trim="form.camera_height" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="相机焦距:" prop="camera_focus">
                <el-input v-model.trim="form.camera_focus" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="二级公司:" prop="second_company">
                <el-select v-model="form.second_company" multiple collapse-tags placeholder="请选择二级公司"
                           class="h-40 w-200">
                  <el-option label="我是二级公司1" value="1"></el-option>
                  <el-option label="我是二级公司2" value="2"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="制作要求:" prop="make_demand">
                <el-input type="textarea" :rows="2" placeholder="请输入制作要求" v-model="form.make_demand"
                          class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="计划起止时间:" prop="plan_time" class="is-required">
                <el-date-picker v-model="plan_time" type="datetimerange" range-separator="至" start-placeholder="计划开始时间"
                                end-placeholder="计划结束时间">
                </el-date-picker>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-form-item>
          <el-button type="primary" @click="add('form')" :loading="isLoading">提交</el-button>
          <el-button @click="goback()">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<style type="text/css">
  .form-checkbox:first-child {
    margin-left: 15px;
  }

  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .el-upload {
    width: 300px;
  }

  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }

  .avatar {
    /*width: 178px;*/
    /*height: 178px;*/
    display: block;
    width: 100% !important;
    height: 100% !important;
  }

  .shot_add .el-form-item {
    display: inline-block;
  }

  .el-transfer-panel {
    width: 150px;
  }

  .el-transfer__buttons {
    padding: 0 15px;
  }

  .el-textarea__inner {
    resize: none;
  }

  .form_studio_id {
    width: 350px;
  }

  .block {
    /* margin-top: 20px; */
  }
</style>
<script>
  import fomrMixin from '@/assets/js/form_com'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        uploadImageUrl: window.HOST + '/admin/upload_project_image',
        form: {
          project_id: '',    //所属项目id
          field_id: '',     //场号id
          shot_image: '',    //镜头缩略图地址
          shot_number: '',    //镜头编号
          shot_byname: '',    //镜头简称
          shot_name: '',    //镜头名称
          shot_explain: '',    //镜头备注
          clip_frame_length: '',    //剪辑帧长
          frame_range: '',    //帧数范围
          priority_level: '',    //镜头优先级
          difficulty: '',    //镜头难度
          handle_frame: '',    //手柄帧
          material_frame_length: '',    //素材帧长
          charge_speed_info: '',    //变速信息
          time: '',    //时刻
          ambient: '',    //环境
          material_ids: '',    //素材号
          camera_model: '',    //相机型号
          camera_catch: '',    //相机捕捉
          camera_motion: '',    //相机运动
          camera_height: '',    //相机高度
          camera_focus: '',    //相机焦距
          second_company: '',    //二级公司
          make_demand: '',    //制作要求
          stauts: 1,    //镜头状态
          plan_start_timestamp: '',    //计划开始时间
          plan_end_timestamp: ''    //计划结束时间
        },
        image: '',
        plan_time: '',
        rules: {
          project_byname: [
            {required: true, message: '请输入项目简称'}, {min: 3, max: 5, message: '长度在 3 到 5 个字符'}, {
              pattern: /^[A-Z]+$/,
              message: '项目简称必须为大写字母'
            }
          ]
        }
      }
    },
    methods: {
      handleAvatarSuccess(res, file) {
        this.image = URL.createObjectURL(file.raw);
        this.form.shot_image = res.data;
      },
      beforeAvatarUpload(file) {
        //  const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 0.195;
        //  if (!isJPG) {
        //    this.$message.error('上传图片只能是 JPG 格式!');
        //  }
        if (!isLt2M) {
          this.$message.error('上传图片大小不能超过 200KB!');
        }
        return isLt2M;
      },
      add(form) {
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPost('admin/shots', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                _g.clearVuex('setUsers')
                setTimeout(() => {
                  this.goback()
                }, 1500)
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
          }
        })
      },
    },
    created() {

    },
    mixins: [http, fomrMixin]
  }
</script>