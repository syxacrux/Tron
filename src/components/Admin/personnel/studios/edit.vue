<template>
  <div class="m-l-50 m-t-30 w-900">
    <el-form ref="form" :model="form" :rules="rules" label-width="130px">
      <el-form-item label="部门名称" prop="name">
        <el-input v-model.trim="form.name" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="父级部门" prop="pid">
        <el-select v-model="form.pid" placeholder="父级部门" class="w-200">
          <el-option label="无" value="0"></el-option>
          <el-option v-for="item in options" :label="item.name" :value="item.id" :key="item.id"></el-option>
        </el-select>
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
          explain: ''
        },
        options: [],
        rules: {
          name: [
            {required: true, message: '请输入部门名称', trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      edit(form) {
        this.$refs[form].validate((valid) => {
          if (valid) {
            this.isLoading = true
            this.apiPut('admin/studios/', this.form.id, this.form).then((res) => {
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
        this.apiGet('admin/studios').then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
      },
      getStudiosInfo() {
        this.form.id = this.$route.params.id
        this.apiGet('admin/studios/' + this.form.id).then((res) => {
          this.handelResponse(res, (data) => {
            this.form.name = data.name
            this.form.pid = data.pid
            this.form.explain = data.explain
          })
        })
      }
    },
    created() {
      this.getStructures()
      this.getStudiosInfo()
    },
    mixins: [http, fomrMixin]
  }
</script>