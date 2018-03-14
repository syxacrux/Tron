<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/home/studios/list' }">工作室管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加工作室</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-l-50 m-t-30 w-900">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px">
        <el-form-item label="工作室名称" prop="name">
          <el-input v-model.trim="form.name" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item label="父级" prop="pid">
          <el-select v-model="form.pid" placeholder="请选择父级" class="w-200">
            <el-option label="无" value="0"></el-option>
            <el-option v-for="item in options" :label="item.name" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="备注" prop="explain">
          <el-input v-model.trim="form.explain" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="add('form')" :loading="isLoading">提交</el-button>
          <el-button @click="goback()">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import fomrMixin from '../../../../assets/js/form_com'
  import _g from '../../../../assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        form: {
          name: '',
          pid: '',
          explain: ''
        },
        options: [],
        rules: {
          name: [
            {required: true, message: '请输入工作室名称', trigger: 'blur'}
          ],
          pid: [
            {required: true, message: '请选择父级部门', trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      /*
        * 工作室添加
        * @params
        *   form:{
        *     name          工作室名称（string）
        *     pid           父级
        *     explain       备注（string）
        *   }
        * */
      add(form) {
        this.$refs[form].validate((valid) => {
          if (valid) {
            this.isLoading = !this.isLoading
            this.apiPost('admin/studios', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
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
//      获取工作室列表
      getStudios() {
        this.apiGet('admin/studios').then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
      },
      init() {
        this.getStudios()
      }
    },
    created() {
//      关闭全局loading
      _g.closeGlobalLoading()
      this.init()
    },
    mixins: [http, fomrMixin]
  }
</script>