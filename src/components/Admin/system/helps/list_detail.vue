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
          <el-button type="text" size="mini" class="m-0" @click="publishFocus">回复</el-button>
          <!--<transition name="el-zoom-in-top">-->
            <!--<div v-show="show2" class="transition-box">-->
              <!--<el-input-->
                  <!--type="textarea"-->
                  <!--placeholder="请输入内容"-->
                  <!--v-model="publish_content">-->
              <!--</el-input>-->
            <!--</div>-->
          <!--</transition>-->
        </p>
      </div>
      <ul>
        <li v-for="item in answerList">{{ item.user_name }}：{{ item.content }}{{ item.create_time }}</li>
      </ul>
      <div class="help_publish">
        <el-form ref="form" :rules="rule">
          <el-form-item label="发表内容" prop="publish_content">
            <el-input
                ref="publishInput"
                type="textarea"
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
//  import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form.vue";
  export default {
    data () {
      return {
        show2: false,
        isLoading: false,
        helpDetail: {},
        answerList: [],
        id: '',
        publish_content: '',
        rule: {
          publish_content: [
            {required: true, message: '请填写发表内容', trigger: 'blur'}
          ]
        }
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
//      ElForm

    },
    mixins: [http]
  }
</script>
<style>
  .help_detail .help_publish {
    border-top: 1px solid #eee;
  }
</style>