<template>
  <div class="help_edit">
    <el-dialog title="问题反馈" :visible.sync="isEditHelps" width="30%">
      <el-form :model="form" label-width="120px" :rules="rules">
        <el-form-item label="反馈类型：" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择问题类型">
            <el-option label="电脑" value="1"></el-option>
            <el-option label="服务器" value="2"></el-option>
            <el-option label="会议室" value="3"></el-option>
            <el-option label="其他" value="4"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="反馈内容：">
          <el-input placeholder="请输入反馈内容" v-model="form.question" class="w-200"></el-input>
        </el-form-item>
        <el-form-item label="回复反馈：">
          <el-input
              type="textarea"
              :autosize="{ minRows: 2, maxRows: 4}"
              placeholder="请回复反馈"
              v-model="form.answer">
          </el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="">取 消</el-button>
        <el-button type="primary" @click="">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  export default {
    data() {
      return {
        isEditHelps: false,
        form: {
          category_id: '',
          title: '',
          content: '',
          keywords: '',
          explain: ''
        },
        rules: {

        }
      }
    },
    methods: {
//      编辑镜头
      edit(form) {
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPut('admin/shots/', this.id, this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '编辑成功')
//                this.$emit('updataHelpDetail', this.id)
                setTimeout(() => {
                  this.isLoading = !this.isLoading
                }, 1500)
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
          }
        })
      },
      async getCompleteData() {

      }
    },
    created() {
      this.getCompleteData()
    },
    mixins: [http],
    props: ['message'],
    watch: {
      message: function(data, o) {

      }
    },
    computed: {

    }
  }
</script>