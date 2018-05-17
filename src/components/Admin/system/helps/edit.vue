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
      <div class="help_content">
        <pre class="m-0" v-html>{{ helpDetail.content }}</pre>
        <p class="tx-r">
          <span>{{ helpDetail.create_time }}</span>
        </p>
      </div>
      <div class="help_publish m-t-30">
        <el-button type="primary" @click="" size="mini" :loading="isLoading">已解决</el-button>
        <el-button @click="$router.go(-1)" size="mini">未解决</el-button>
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
        isLoading: false,
        helpDetail: {},
        id: '',
      }
    },
    methods: {
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

    },
    mixins: [http],
    computed: {
//      删除反馈按钮
      deleteShow () {
        return _g.getHasRule('helps-delete')
      }
    }
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