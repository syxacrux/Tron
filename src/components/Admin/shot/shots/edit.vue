<template>
  <el-dialog title="编辑镜头" :visible.sync="dialogFormVisible" width="75%">
    <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="shot_add">
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="项目名称:" prop="project_id">
              <el-select v-model="form.project_id" placeholder="请选择项目" @change="getAllFields">
                <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                           :key="item.id"></el-option>
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
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="资产:" prop="asset_ids">
              <el-select v-model="form.asset_ids" multiple collapse-tags placeholder="请选择资产" class="h-40 w-200">
                <el-option label="我是资产1" value="1"></el-option>
                <el-option label="我是资产2" value="2"></el-option>
              </el-select>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item class="is-required" label="镜头缩略图:" prop="image">
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
      <el-row :gutter="20">
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
      </el-row>
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="是否暂停:" prop="is_parse">
              <el-select v-model="form.is_parse" placeholder="请选择是否暂停" class="h-40 w-200">
                <el-option label="非暂停" value="1"></el-option>
                <el-option label="暂停" value="2"></el-option>
              </el-select>
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
      </el-row>
      <el-row :gutter="20">
        <el-col :span="24">
          <div class="grid-content">
            <el-form-item label="计划起止时间:" prop="plan_time" class="is-required">
              <el-date-picker v-model="plan_time" type="datetimerange" range-separator="至" start-placeholder="计划开始时间"
                              end-placeholder="计划结束时间">
              </el-date-picker>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
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
            <el-form-item label="手柄帧:" prop="handle_frame">
              <el-input v-model.trim="form.handle_frame" class="h-40 w-80"></el-input>
              -
              <el-input v-model.trim="form.handle_frame" class="h-40 w-80"></el-input>
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
      </el-row>
      <el-row :gutter="20">
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
            <el-form-item label="剪辑帧长:" prop="clip_frame_length">
              <el-input v-model.trim="form.clip_frame_length" class="h-40 w-200"></el-input>
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
      </el-row>
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="素材号:" prop="material_number">
              <el-select v-model="form.material_number" placeholder="请选择素材号" class="h-40 w-200">
                <el-option label="我是素材号1" value="1"></el-option>
                <el-option label="我是素材号2" value="2"></el-option>
              </el-select>
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
      </el-row>
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="镜头备注:" prop="shot_explain">
              <el-input type="textarea" :rows="3" placeholder="请输入镜头备注" v-model="form.shot_explain"
                        class="h-40 w-200"></el-input>
            </el-form-item>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="变速信息:" prop="charge_speed_info">
              <el-input type="textarea" :rows="3" placeholder="请输入变速信息" v-model="form.charge_speed_info"
                        class="h-40 w-200"></el-input>
            </el-form-item>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="制作要求:" prop="make_demand">
              <el-input type="textarea" :rows="3" placeholder="请输入制作要求" v-model="form.make_demand"
                        class="h-40 w-200"></el-input>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="goback()">取 消</el-button>
      <el-button type="primary" @click="edit('form')" :loading="isLoading">确 定</el-button>
    </div>
  </el-dialog>
</template>
<style>
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
    display: block;
    width: 100% !important;
    height: 100% !important;
  }
</style>
<script>
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        dialogFormVisible: false,
        uploadImageUrl: window.HOST + '/admin/upload_image',
        projectList: [],
        fieldList: [],
        form: {
          project_id: '',    //所属项目id
          field_id: '',     //场号id
          shot_image: '',    //镜头缩略图地址
          shot_number: '',    //镜头编号
          shot_byname: '',    //镜头简称
          shot_name: '',    //镜头名称
          time: '',    //时刻
          ambient: '',    //环境
          plan_start_timestamp: '',    //计划开始时间
          plan_end_timestamp: '',    //计划结束时间
          is_parse: '',    //是否暂停   以上为必填项↑
          shot_explain: '',    //镜头备注
          clip_frame_length: '',    //剪辑帧长
          frame_range: '',    //帧数范围
          priority_level: '',    //镜头优先级
          difficulty: '',    //镜头难度
          handle_frame: '',    //手柄帧
          material_frame_length: '',    //素材帧长
          charge_speed_info: '',    //变速信息
          material_number: '',    //素材号
          second_company: '',    //二级公司
          make_demand: '',    //制作要求
          status: 1    //镜头状态
        },
        image: '',
        plan_time: '',
        rules: {
          project_id: [{required: true, message: '请选择项目'}],
          field_id: [{required: true, message: '请选择场号'}],
          shot_image: [{required: true, message: '请插入镜头缩略图'}],
          shot_number: [{required: true, message: '请输入镜头编号'}, , {min: 3, max: 6, message: '长度在3到6个字符'}, {
            pattern: /^[0-9]+$/,
            message: '镜头编号必须为数字'
          }],
          shot_byname: [
            {required: true, message: '请输入镜头简称'}, {pattern: /^[a-zA-Z]+$/, message: '镜头简称必须为字母'}
          ],
          shot_name: [{required: true, message: '请输入镜头名称'}, {pattern: /^[\u4E00-\u9FA5]+$/, message: '镜头名称必须为汉字'}],
          time: [{required: true, message: '请选择时刻'}],
          ambient: [{required: true, message: '请选择环境'}],
          is_parse: [{required: true, message: '请选择是否暂停'}]
        }
      }
    },
    methods: {
      open() {
        this.dialogFormVisible = true
      },
//      close() {
//        this.dialogToggle = false
//      },
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
      edit(form) {
        if (!this.form.shot_image) {
          _g.toastMsg('warning', '请插入镜头缩略图')
          return
        }
        if (this.plan_time.length === 0) {
          _g.toastMsg('warning', '请输入计划起止时间')
          return
        }
        this.form.project_id = parseInt(this.form.project_id)
        this.form.field_id = parseInt(this.form.field_id)
        this.form.time = parseInt(this.form.time)
        this.form.ambient = parseInt(this.form.ambient)
        this.form.is_parse = parseInt(this.form.is_parse)
        this.form.plan_start_timestamp = _g.j2time(this.plan_time[0])
        this.form.plan_end_timestamp = _g.j2time(this.plan_time[1])
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPut('admin/shots/', this.id, this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '编辑成功')
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
      //获取工作室
      getAllProjects() {
        return new Promise((resolve, reject) => {
          this.apiGet('admin/projects').then((res) => {
            this.handelResponse(res, (data) => {
              resolve(data.list)
            })
          })
        })
      },
      getAllFields() {
        const data = {
          params: {
            project_id: this.form.project_id
          }
        }
        this.apiGet('admin/get_fields', data).then((res) => {
          this.handelResponse(res, (data) => {
            resolve(data.list)
          })
        })
      },
      async getCompleteData() {
        this.projectList = await this.getAllProjects()
//        this.apiGet('admin/shots/' + this.id).then((res) => {
//          this.handelResponse(res, (data) => {
//            this.form.project_name = data.project_name
//            this.form.project_byname = data.project_byname
//            this.form.project_explain = data.project_explain
//            this.form.project_image = this.image = window.baseUrl + '/' + data.project_image
//            console.log(this.image)
//            this.form.handle_frame.handle_frame1 = data.handle_frame.split(',')[0]
//            this.form.handle_frame.handle_frame2 = data.handle_frame.split(',')[1]
//            this.form.movies_type = data.movies_type.toString()
//            this.form.resolutic = data.resolutic.toString()
//            this.form.frame_rate = data.frame_rate.toString()
//            this.form.aspect_ratio = data.aspect_ratio.toString()
//
//            function str2num(str) {
//              let arr = str.split(',')
//              let temp = []
//              _(arr).forEach((key, index) => {
//                temp.push(parseInt(key))
//              })
//              return temp
//            }
//
//            this.studio_ids = str2num(data.studio_ids)
//            this.scene_director = str2num(data.scene_director)
//            this.producer = str2num(data.producer)
//            this.scene_producer = str2num(data.scene_producer)
//            this.visual_effects_boss = str2num(data.visual_effects_boss)
//            this.visual_effects_producer = str2num(data.visual_effects_producer)
//            this.second_company_producer = str2num(data.second_company_producer)
//            this.inside_coordinate = str2num(data.inside_coordinate)
//            this.plan_time = [new Date(data.plan_start_timestamp * 1000), new Date(data.plan_end_timestamp * 1000)]
//          })
//        })
      }
    },
    created() {
//      this.form.auth_key = Lockr.get('authKey')
      this.getCompleteData()
    },
    mixins: [http]
  }
</script>