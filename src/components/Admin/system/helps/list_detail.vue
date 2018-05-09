<template>
  <div class="help_detail">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin' }">系统管理</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/helps/list' }">问题反馈</el-breadcrumb-item>
        <el-breadcrumb-item>文章详情</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{ helpDetail.title }}</span>
      </div>
      <div>
        {{ helpDetail.content }}
        <p class="tx-r">
          <span>{{ helpDetail.create_time }}</span>
          <el-button type="text" size="mini" class="m-0" @click="">回复</el-button>
        </p>
      </div>
      <div class="help_publish">
        <el-form>
          <el-form-item label="发表内容" prop="explain">
            <el-input
                type="textarea"
                :autosize="{ minRows: 2, maxRows: 4}"
                placeholder="请输入内容"
                v-model="publish_content">
            </el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitHelp" size="mini" :loading="isLoading">提交</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form.vue";
  export default {
    data () {
      return {
        isLoading: false,
        helpDetail: {},
        id: '',
        publish_content: ''
      }
    },
    methods: {
      submitHelp() {
        let _this = this
        const data = {
          id: this.id,
          content: this.publish_content
        }
        this.apiPost('help/add_answer', data).then((res) => {
          this.handelResponse(res, (data) => {
            _g.toastMsg('success', '回复成功')
            setTimeout(() => {
              _this.getAnswerList()
            }, 1500)
          }, () => {
            this.isLoading = !this.isLoading
          })
        })
      },
      getAnswerList() {
        this.apiGet('help/answer_list/' + this.id).then((res) => {
          this.handelResponse(res, (data) => {
            console.log(data)
          })
        })
      },
      getHelpDetail(id) {
        this.apiGet('admin/helps/' + this.id).then((res) => {
          this.handelResponse(res, (data) => {
            this.helpDetail = data
          })
        })
      }
    },
    created () {
      this.id = this.$route.params.id
      this.getHelpDetail(this.id)
    },
    computed: {

    },
    components: {
      ElForm

    },
    mixins: [http]
  }
</script>
<style>
  .help_detail .help_publish {
    border-top: 1px solid #eee;
  }
</style>