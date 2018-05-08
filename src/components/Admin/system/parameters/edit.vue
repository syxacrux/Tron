<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/deploys/list' }">系统配置项管理</el-breadcrumb-item>
        <el-breadcrumb-item>编辑系统配置项</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-l-50 m-t-30 w-900">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px">
        <el-form-item label="配置项名称" prop="name">
          <el-input v-model.trim="form.name" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item label="父级配置项" prop="pid">
          <el-select v-model="form.pid" placeholder="请选择父级配置项" class="w-200">
            <el-option label="无" value="0"></el-option>
            <el-option v-for="item in options" :label="item.name" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="排序">
          <el-input v-model="form.sort" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item label="备注" prop="explain">
          <el-input v-model.trim="form.explain" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="edit('form')" :loading="isLoading">提交</el-button>
          <el-button @click="goback()">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import fomrMixin from '../../../../assets/js/form_com'
  import _ from 'lodash'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        form: {
          id: '',
          name: '',
          pid: null,
          sort: '',
          explain: ''
        },
        options: [],
        rules: {
          name: [
            {required: true, message: '请输入配置项名称', trigger: 'blur'}
          ],
          pid: [
            {required: true, message: '请选择父级部门', trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      edit(form) {
        this.$refs[form].validate((valid) => {
          if (valid) {
            this.isLoading = true
            this.apiPut('admin/parameters/', this.form.id, this.form).then((res) => {
              this.handelResponse(res, (data) => {
                this.isLoading = false
                _g.toastMsg('success', '编辑成功')
                setTimeout(() => {
                  this.goback()
                }, 1500)
              }, () => {
                this.isLoading = false
              })
            })
          }
        })
      },
      getStructures() {
        this.apiGet('admin/parameters').then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
      },
      getSystemDeploysInfo() {
        this.form.id = this.$route.params.id
        this.apiGet('admin/parameters/' + this.form.id).then((res) => {
          this.handelResponse(res, (data) => {
            this.form.name = data.name
            this.form.pid = data.pid.toString()
            this.form.sort = data.sort
            this.form.explain = data.explain
          })
        })
      }
    },
    created() {
      this.getStructures()
      this.getSystemDeploysInfo()
    },
    mixins: [http, fomrMixin]
  }
</script>