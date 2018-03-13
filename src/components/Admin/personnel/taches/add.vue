<template>
    <div>
        <div class="m-b-20">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/home/taches/list' }">环节管理</el-breadcrumb-item>
                <el-breadcrumb-item>添加环节</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="m-l-50 m-t-30 w-900">
            <el-form ref="form" :model="form" :rules="rules" label-width="130px">
                <el-form-item label="环节名称" prop="tache_name">
                    <el-input v-model.trim="form.tache_name" class="h-40 w-200"></el-input>
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
		  explain: '',
		  tache_name: ''
		},
		rules: {
		  tache_name: [
			{required: true, message: '请输入择环节'}
		  ]
		}
	  }
	},
	methods: {
	  /*
      * 环节添加
      * @params
      *   form:{
      *     tache_name  环节名称（string）
      *     explain      备注（string）
      *   }
      * */
	  add(form) {
		this.$refs[form].validate((valid) => {
		  if (valid) {
			this.isLoading = !this.isLoading
			this.apiPost('admin/taches', this.form).then((res) => {
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
	},
	created() {
	  //      关闭全局loading
	  _g.closeGlobalLoading()
	},
	mixins: [http, fomrMixin]
  }
</script>