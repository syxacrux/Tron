<template>
    <div>
        <div class="m-b-20">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/admin/taches/list' }">环节管理</el-breadcrumb-item>
                <el-breadcrumb-item>编辑环节</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="m-l-50 m-t-30 w-900">
            <el-form ref="form" :model="form" :rules="rules" label-width="130px">
                <el-form-item label="环节名称" prop="tache_name">
                    <el-input v-model.trim="form.tache_name" class="h-40 w-200"></el-input>
                </el-form-item>
								 <el-form-item label="排序" prop="sort">
                    <el-input v-model.trim="form.sort" class="h-40 w-200"></el-input>
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
  import _g from '../../../../assets/js/global'

  export default {
	data() {
	  return {
		isLoading: false,
		form: {
		  tache_name: '',
		  explain: '',
			sort:''
		},
		rules: {
		  tache_name: [
			{required: true, message: '请输入环节', trigger: 'blur'}
			],
			sort: [
			{required: true, message: '请输入排序', trigger: 'blur'}
		  ]
		}
	  }
	},
	methods: {
	  /*
      * 工作室编辑
      * @params
      *   form:{
      *     tache_name  工作室名称（string）
      *     explain      备注（string）
      *   }
      * */
	  edit(form) {
		this.$refs[form].validate((valid) => {
		  if (valid) {
			this.isLoading = !this.isLoading
			this.apiPut('admin/taches/', this.id, this.form).then((res) => {
			  this.handelResponse(res, (data) => {
				_g.toastMsg('success', '编辑成功')
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
//      获取工作室信息
	  getTacheInfo() {
		this.form.id = this.$route.params.id        //环节id
		this.apiGet('admin/taches/' + this.form.id).then((res) => {
		  this.handelResponse(res, (data) => {
			this.form.id = data.id
			this.form.tache_name = data.tache_name
			this.form.explain = data.explain
			this.form.sort = data.sort
		  })
		})
	  }
	},
	created() {
	  this.id = this.$route.params.id
	  this.getTacheInfo()
	},
	mixins: [http, fomrMixin]
  }
</script>