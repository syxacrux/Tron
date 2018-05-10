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
      <div v-if="helpDetail.type === 1" slot="header" class="clearfix">
        <span>{{ helpDetail.title }}</span>
      </div>
      <div class="help_content">
        <pre class="m-0" v-html>{{ helpDetail.content }}</pre>
        <p class="tx-r">
          <span>{{ helpDetail.create_time }}</span>
          <el-button type="text" size="mini" class="m-0" @click="publishFocus">回复</el-button>
        </p>
      </div>
      <ul class="p-l-20 p-r-20 p-b-10 p-t-10">
        <li v-for="item in answerList" class="p-t-5 p-b-5">
          <span class="fz-14 c-black">{{ item.user_name }}</span>：
          <span>{{ item.content }}</span>
          <p class="fz-14 tx-r m-0 p-b-5 p-r-10 bor-b-gray">{{ item.create_time }}</p>
        </li>
      </ul>
      <div class="help_publish m-t-30">
        <el-form ref="form">
          <el-form-item label="回复内容：" prop="publish_content">
            <el-input
                ref="publishInput"
                type="textarea"
                placeholder="请输入回复内容"
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
  export default {
    data () {
      return {
        show2: false,
        isLoading: false,
        helpDetail: {},
        answerList: [],
        id: '',
        publish_content: ''
      }
    },
    methods: {
      submitHelp() {
        let _this = this
        if(!this.publish_content) {
          _g.toastMsg('warning', '请填写发表内容')
          return
        }
        const data = {
          help_id: this.id,
          content: this.publish_content
        }
        this.apiPost('help/add_answer', data).then((res) => {
          this.handelResponse(res, (data) => {
            _g.toastMsg('success', '回复成功')
            this.publish_content = ''
            setTimeout(() => {
              _this.getAnswerList()
            }, 1500)
          }, () => {
            this.isLoading = !this.isLoading
          })
        })
      },
      publishFocus() {
        $('html,body').animate({scrollTop:$('.help_publish').offset().top}, 800);
        this.$refs.publishInput.focus();
      },
      getAnswerList() {
        const data = {
          params: {
            help_id: this.id
          }
        }
        this.apiGet('help/answer_list',  data).then((res) => {
          this.handelResponse(res, (data) => {
            this.answerList = data
          })
        })
      },
      getHelpDetail(id) {
        this.apiGet('admin/helps/' + this.id).then((res) => {
          this.handelResponse(res, (data) => {
            this.helpDetail = data
            this.getAnswerList()
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

    },
    mixins: [http]
  }
</script>
<style>
  .help_detail .help_publish {
    border-top: 1px solid #eee;
  }
  .help_detail ul {
    background: #f7f8fa;
  }
  .help_detail .help_content {
    font-size: 14px;
    line-height: 26px;
    padding: 5px 20px;
  }
  .help_detail .help_content pre{
    font-family: Microsoft YaHei;
    white-space: pre-line;
  }

</style>