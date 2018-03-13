<template>
    <div>
        <div class="m-b-20">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/home/studios/list' }">工作室管理</el-breadcrumb-item>
                <el-breadcrumb-item>编辑工作室</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="m-l-50 m-t-30 w-900">
            <el-form ref="form" :model="form" :rules="rules" label-width="130px">
                <el-form-item label="工作室名称" prop="studio_name">
                    <el-input v-model.trim="form.studio_name" class="h-40 w-200"></el-input>
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
                    studio_name: '',
                    explain: ''
                },
                rules: {
                    studio_name: [
                        {required: true, message: '请输入工作室', trigger: 'blur'}
                    ]
                }
            }
        },
        methods: {
            /*
            * 工作室编辑
            * @params
            *   form:{
            *     studio_name  工作室名称（string）
            *     explain      备注（string）
            *   }
            * */
            edit(form) {
                this.$refs[form].validate((valid) => {
                    if (valid) {
                        this.isLoading = !this.isLoading
                        this.apiPut('admin/studios/', this.id, this.form).then((res) => {
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
            getStudioInfo() {
                this.form.id = this.$route.params.id        //工作室id
                this.apiGet('admin/studios/' + this.form.id).then((res) => {
                    this.handelResponse(res, (data) => {
                    this.form.id = data.id
                this.form.studio_name = data.studio_name
                this.form.explain = data.explain
            })
            })
            }
        },
        created() {
            this.id = this.$route.params.id
            this.getStudioInfo()
        },
        mixins: [http, fomrMixin]
    }
</script>