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
        <el-row>
          <el-col :span="8"><div class="grid-content bg-purple">
            <el-form-item label="项目名称:" prop="project_name">
              <el-input v-model.trim="form.project_name" class="h-40 w-200"></el-input>
            </el-form-item>
          </div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple-light">
            <el-form-item label="项目名称:" prop="project_name">
              <el-input v-model.trim="form.project_name" class="h-40 w-200"></el-input>
            </el-form-item>
          </div></el-col>
          <el-col :span="8"><div class="grid-content bg-purple">
            <el-form-item label="项目名称:" prop="project_name">
              <el-input v-model.trim="form.project_name" class="h-40 w-200"></el-input>
            </el-form-item>
          </div></el-col>
        </el-row>
        <el-row :gutter="20">
          <el-form-item label="项目名称:" prop="project_name">
            <el-input v-model.trim="form.project_name" class="h-40 w-200"></el-input>
          </el-form-item>
          <el-form-item label="项目简称:" prop="project_byname">
            <el-input v-model.trim="form.project_byname" class="h-40 w-200"></el-input>
          </el-form-item>
          <el-form-item label="影视类型:" prop="movies_type">
            <el-select v-model="form.movies_type" placeholder="请选择">
              <!--<el-option label="请选择" value=""></el-option>-->
              <el-option label="电影" value="1"></el-option>
              <el-option label="电视剧" value="2"></el-option>
            </el-select>
          </el-form-item>
        </el-row>
        <el-row :gutter="20">
          <el-form-item class="is-required" label="项目缩略图:" prop="image">
            <!-- 上传照片地址 action="" -->
            <el-upload
                class="avatar-uploader"
                :action="uploadImageUrl"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload">
              <img v-if="form.project_image" :src="image" class="avatar">
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-form-item>
        </el-row>
        <el-row :gutter="20">
          <el-form-item label="项目说明:" prop="project_explain">
            <el-input
                type="textarea"
                :rows="2"
                v-model.trim="form.project_explain"
                class="h-40 w-800"
                style="resize: none;">
            </el-input>
          </el-form-item>
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
          project_image: ''
        },
        image: '',
        filterMethod(query, item) {
          return item.pinyin.indexOf(query) > -1;
        },
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
        this.form.project_image = res.data;
      },
      beforeAvatarUpload(file) {
        //  const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 0.195;
        //  if (!isJPG) {
        //    this.$message.error('上传头像图片只能是 JPG 格式!');
        //  }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 200KB!');
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