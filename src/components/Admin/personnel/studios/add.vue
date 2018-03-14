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
                <el-form-item label="工作室名称" prop="studio_name">
                    <el-input v-model.trim="form.name" class="h-40 w-200"></el-input>
                </el-form-item>
                <el-form-item label="父级" prop="pid">
                    <el-select v-model="form.pid" placeholder="父级" class="w-200">
                        <el-option v-for="item in options" :label="item.title" :value="item.id" :key="item.id"></el-option>
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
		  explain: '',
		  pid: ''
		},
		options: [{ pid: 0, title: '无' }],
		rules: {
		  name: [
			{required: true, message: '请输入工作室名称', trigger: 'blur'}
		  ]
		}
	  }
	},
	methods: {
	  /*
      * 工作室添加
      * @params
      *   form:{
      *     studio_name  工作室名称（string）
      *     explain      备注（string）
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
	  }
	},
	created() {
//      关闭全局loading
	  _g.closeGlobalLoading()
	},
	mixins: [http, fomrMixin]
  }
</script>