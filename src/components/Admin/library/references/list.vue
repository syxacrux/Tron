<template>
  <div class="shot_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/references/list' }">库管理</el-breadcrumb-item>
        <el-breadcrumb-item>参考库管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <router-link class="btn-link-large add-btn" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加资料
      </router-link>
    </div>
    <div class="ovf-hd">
      <!--<el-tabs v-if="!isList" v-model="activeName" @tab-click="tabClick" class="fl">-->
        <!--<el-tab-pane label="项目" name="project">-->
          <!--<div class="ovf-hd">-->
            <!--<el-col :span="12" v-for="item in waitingList" :key="item.id">-->
              <!--<div class="grid-content p-b-5" @click="assetDetail(item.id)">-->
                <!--<el-card class="ovf-hd">-->
                <!--</el-card>-->
              <!--</div>-->
            <!--</el-col>-->
          <!--</div>-->
          <!--<div class="pos-rel p-t-20" v-if="projectList.length">-->
            <!--<div class="block tx-r">-->
              <!--<el-pagination-->
                  <!--@current-change="projectCurrentChange"-->
                  <!--:current-page.sync="currentPage"-->
                  <!--:page-size="10"-->
                  <!--layout="prev, pager, next, jumper"-->
                  <!--:total="projectDataCount">-->
              <!--</el-pagination>-->
            <!--</div>-->
          <!--</div>-->
        <!--</el-tab-pane>-->
        <!--<el-tab-pane label="公共" name="common">-->

        <!--</el-tab-pane>-->
      <!--</el-tabs>-->
    </div>
  </div>
</template>
<script>

  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {
        activeName: 'project',
        projectCurrentChange: 1,
        currentPage: 1,
        projectDataCount: 0,
        projectList: [],

      }
    },
    methods: {
      /*
       * 切换资产tab方法
       *   params: {
       *     tab: 传入当前点击tab信息
       *   }
       * */
      tabClick (tab, event) {
        this.init(tab.name)
      },
      /*
       * 初始化资产看板内容
       * params: {
       *   tab_name : 当点击切换资产tab时的传入值
       * }
       * */
      init (tab_name) {
        switch (tab_name) {
          case 'project':
            this.getAssets('feedback', 1)
            break;
          case 'common':
            this.getAssets('waiting_assets', 1)
            break;
        }
      }
    },
    created () {
      this.init(this.activeName)
    },
    components: {

    },
    mixins: [http],
    computed: {

    }
  }
</script>
<style>

</style>

