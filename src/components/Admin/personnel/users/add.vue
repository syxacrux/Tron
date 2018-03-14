<template>
	<div>
		<div class="m-b-20">
			<el-breadcrumb separator="/">
				<el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
				<el-breadcrumb-item :to="{ path: '/admin/users/list' }">成员管理</el-breadcrumb-item>
				<el-breadcrumb-item>添加成员</el-breadcrumb-item>
			</el-breadcrumb>
		</div>
		<div class="m-l-50 m-t-30 w-800">
			<el-form ref="form" :model="form" :rules="rules" label-width="130px">
				<el-form-item label="用户名" prop="username">
					<el-input v-model.trim="form.username" class="h-40 w-200" :maxlength=12></el-input>
				</el-form-item>
				<el-form-item label="密码" prop="password">
					<el-input v-model.trim="form.password" class="h-40 w-200"></el-input>
				</el-form-item>
				<el-form-item label="真实姓名" prop="realname">
					<el-input v-model.trim="form.realname" class="h-40 w-200"></el-input>
				</el-form-item>
				<el-form-item label="所属工作室" prop="studio_ids">
					<el-checkbox-group v-model="form.studio_ids" size="small">
						<el-checkbox-button v-for="item in studiosOptions" :label="item.name" :value="item.id" :key="item.id"></el-checkbox-button>
					</el-checkbox-group>
				</el-form-item>
				<el-form-item label="环节" prop="tache_ids">
					<el-checkbox-group v-model="form.tache_ids" size="small">
						<el-checkbox-button v-for="item in tachesOptions" :label="item.explain" :value="item.id" :key="item.id">{{item.explain}}</el-checkbox-button>
					</el-checkbox-group>
				</el-form-item>
				<el-form-item label="角色" prop="group_id">
					<el-select v-model="form.group_id" placeholder="请选择角色">
						<el-option v-for="item in groupsOptions" :label="item.remark" :value="item.id" :key="item.id"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="备注">
					<el-input v-model.trim="form.remark" class="h-40 w-200"></el-input>
				</el-form-item>
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
</style>
<script>
  import http from '../../../../assets/js/http'
  import fomrMixin from '../../../../assets/js/form_com'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        form: {
          username: '',
          password: '',
          realname: '',
          studio_ids: [],
          tache_ids: [],
          group_id: '',
          remark: ''
        },
        studiosOptions: [],
        tachesOptions: [],
        groupsOptions: [],
        selectedStudios: [],
        selectedTaches: [],
        selectedStudioIds: [],
        selectedTacheIds: [],
        rules: {
          username: [
            {required: true, message: '请输入用户名'}
          ],
          password: [
            {required: true, message: '请输入密码'}
          ],
          realname: [
            {required: true, message: '请输入真实姓名'}
          ],
          studio_ids: [
            {required: true, message: '请选择至少一个工作室'}
          ],
          tache_ids: [
            {required: true, message: '请选择至少一个环节'}
          ],
          group_id: [
            {required: true, message: '请选择角色'}
          ]
        }
      }
    },
    methods: {
//      检查工作室复选框
      selectStudiosCheckbox() {
        let temp = false
        this.selectedStudios = this.form.studio_ids
        this.selectedStudioIds = []
        _(this.studiosOptions).forEach((res) => {
          if (this.selectedStudios.toString().indexOf(res.name) > -1) {
            this.selectedStudioIds.push(res.id)
          }
        })
        if (this.selectedStudioIds.length) {
          this.form.studio_ids = _.cloneDeep(this.selectedStudioIds)
          temp = true
        }
        this.selectedStudioIds = []
        return temp
      },
//			检查环节复选框
      selectTachesCheckbox() {
        let temp = false
        this.selectedTaches = this.form.tache_ids
        this.selectedTacheIds = []
        _(this.tachesOptions).forEach((res) => {
          if (this.selectedTaches.toString().indexOf(res.explain) > -1) {
            this.selectedTacheIds.push(res.id)
          }
        })
        if (this.selectedTacheIds.length) {
          this.form.tache_ids = _.cloneDeep(this.selectedTacheIds)
          temp = true
        }
        this.selectedTacheIds = []
        return temp
      },
      add(form) {
        if (!this.selectStudiosCheckbox()) {
          _g.toastMsg('warning', '请选择至少一个工作室')
          return
        }
        if (!this.selectTachesCheckbox()) {
          _g.toastMsg('warning', '请选择至少一个环节')
          return
        }
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPost('admin/users', this.form).then((res) => {
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
//			获取所有角色
      getAllGroups() {
        let data = store.state.Groups
        if (data.list && data.list.length) {
          this.groupsOptions = data.list
        } else {
          this.apiGet('admin/groups').then((res) => {
            this.handelResponse(res, (data) => {
              this.groupsOptions = data.list
            })
          })
        }
      },
//			获取所有工作室
      getAllStudios() {
        let data = store.state.Studios
        if (data.list && data.list.length) {
          this.studiosOptions = data.list
        } else {
          this.apiGet('admin/studios').then((res) => {
            this.handelResponse(res, (data) => {
              this.studiosOptions = data.list
            })
          })}
      },
//			获取所有环节
      getAllTaches() {
        let data = store.state.Taches
        if (data.list && data.list.length) {
          this.tachesOptions = data.list
        } else {
          this.apiGet('admin/taches').then((res) => {
            this.handelResponse(res, (data) => {
              this.tachesOptions = data.list
            })
          })}
      }
    },
    created() {
      this.getAllGroups()
      this.getAllStudios()
      this.getAllTaches()
    },
    mixins: [http, fomrMixin]
  }
</script>