<template>
  <el-dialog class="workbenches-edit" title="任务编辑" :visible.sync="dialogFormVisible" width="60%">
    <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="shot_add">
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <!-- <el-form-item label="任务名称:" prop="field_id">
              <el-input v-model="form.field_id" placeholder="请选择制作人">
              </el-input>
            </el-form-item> -->
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item class="is-required" label="任务缩略图:" prop="image">
            <el-upload
                class="avatar-uploader"
                :action="uploadImageUrl"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload">
              <img v-if="form.task_image" :src="image" class="avatar">
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-form-item>
        </el-col>
      </el-row>
       <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="任务简称:" prop="task_priority_level">
              <el-input  v-model="form.task_byname" class="h-40 w-200"></el-input>
            </el-form-item>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="制作人:" prop="field_id">
              <el-select v-model="field_id" multiple placeholder="请选择制作人">
                <el-option v-for="item in options"
                  :key="item.id"
                  :label="item.real_name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="优先级:" prop="task_priority_level">
              <el-select v-model="form.task_priority_level" placeholder="请选择优先级" class="h-40 w-200">
                <el-option label="D" value="1"></el-option>
                <el-option label="C" value="2"></el-option>
                <el-option label="B" value="3"></el-option>
                <el-option label="A" value="4"></el-option>
              </el-select>
            </el-form-item>
          </div>
        </el-col>
         <el-col :span="8">
          <div class="grid-content">
            <el-form-item label="难度:" prop="difficulty">
              <el-select v-model="form.difficulty" placeholder="请选择难度" class="h-40 w-200">
                <el-option label="D" value="1"></el-option>
                <el-option label="C" value="2"></el-option>
                <el-option label="B" value="3"></el-option>
                <el-option label="A" value="4"></el-option>
                <el-option label="S" value="5"></el-option> 
              </el-select>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="24">
          <div class="grid-content">
            <el-form-item label="计划起止时间:" prop="plan_time" class="is-required">
              <el-date-picker
                v-model="plan_time"
                type="datetimerange"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期">
            </el-date-picker>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
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
      <el-button @click="dialogFormVisible = !dialogFormVisible">取 消</el-button>
      <el-button type="primary" @click="edit('form')" :loading="isLoading">确 定</el-button>
    </div>
  </el-dialog>
</template>
<script>
import http from '../../../../assets/js/http'
import _g from '@/assets/js/global'

export default {
  data() {
    return{
        dialogFormVisible: false,
        isLoading: false,
        uploadImageUrl: window.HOST + '/admin/upload_image',
        plan_time:[],
        image:'',
        id:0,
        field_id:[],
        options:[],
        form: {
          group_id:null,//角色ID
          user_id:'',//制作人
          project_id:2,//所属项目ID
          field_id:3,//	场号/集号ID
          shot_id:null,//镜头ID 根据任务类型存值
          assets_id:null,//资产ID 根据任务类型存值
          tache_id:1,//环节ID
          tache_sort:null,//环节序号
          studio_id:2,//工作室ID
          task_type:'',//任务类型
          task_image:'',//镜头缩略图
          task_byname:'FUY',//镜头简称
          make_demand:'',//制作要求
          shot_number:'001',//镜头
          task_priority_level:'A',//优先级
          difficulty:'S',//难度
          second_company:'',//二级公司(相当于其他工作室ID )
          task_is_status_time:'32',//任务在此状态时间 分
          task_allocated_time:'9',//任务分配时间 小时
          plan_start_timestamp:'',//计划开始时间
          plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
          actually_start_timestamp:'',//任务实际开始时间
          actually_end_timestamp:'',//任务实际结时时间	
          finish_degree:'',//完成度
          task_status:1,//任务状态 1等待制作 5制作中 10等待审核 15 镜头反馈中 16 资产反馈中 20内部审核通过 25完成 30客户通过
          is_pause:1,//是否暂停 1 非暂停 2暂停
          camera_model:'',//相机型号
          camera_catch:'',//相机捕捉	
          camera_motion:1,//相机运动 1匀速
          camera_height:null,//相机高度
          camera_focus:'',//相机焦距
          focus_distance:'',//对焦距离
          depth_of_field:'',//景深
          pid:1,//所属任务主键
          create_time:111,//创建时间
          update_time:222,//改变状态时更新时间
        },
        rules: {
          is_parse: [{required: true, message: '请选择是否暂停'}]
        }
    }
  },
  methods: {
      open() {
        this.dialogFormVisible = true
      },
      handleAvatarSuccess(res, file) {
        this.image = URL.createObjectURL(file.raw);
        this.form.task_image = res.data;
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
      //编辑任务
      edit(form){
        this.form.plan_start_time = _g.j2time(this.plan_time[0])
        this.form.plan_end_time = _g.j2time(this.plan_time[1])
        this.form.difficulty = this.form.difficulty ? parseInt(this.form.difficulty) : 1
        this.form.task_priority_level = this.form.task_priority_level ? parseInt(this.form.task_priority_level) : 1
        this.form.user_id = this.field_id.join(',')
        this.form.task_image = this.form.task_image.slice(this.form.task_image.indexOf('uploads'))
        this.$refs[form].validate((valid) => {
          if (valid) {
          this.isLoading = !this.isLoading
          console.log(this.form)
          this.apiPut('admin/workbenches/', this.id, this.form ).then((res) => {
            this.handelResponse(res, (data) => {
            _g.toastMsg('success', '编辑成功')
            this.$emit('updataTaskdetail', this.id)
            setTimeout(() => {
              // this.goback()
               this.dialogFormVisible = false
               this.isLoading = !this.isLoading
            }, 1500)
            }, () => {
            this.isLoading = !this.isLoading
            })
          })
          }
        })
      }
  },
  mixins: [http],
  props: ['message'],
  watch: {
    message: function(data, o) {
        this.field_id = []
        this.apiGet(`task/get_user?task_id=${data.id}`).then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
        this.id = data.id
        this.form=data
        this.form.task_image = this.image = window.baseUrl + '/' + data.task_image
        this.form.task_priority_level = data.task_priority_level.toString()
        this.form.difficulty = data.difficulty.toString()
        this.plan_time = [new Date(data.plan_start_timestamp * 1000), new Date(data.plan_end_timestamp * 1000)]
        
    }
  }
}
</script>
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
  .workbenches-edit .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .workbenches-edit .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .workbenches-edit .el-upload {
    width: 300px;
  }

  .workbenches-edit .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
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

  .shot_edit .el-tag__close{
    display: none;
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

