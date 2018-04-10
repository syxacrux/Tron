<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/shots/list' }">镜头管理</el-breadcrumb-item>
        <el-breadcrumb-item>镜头列表</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <div class="tx-r">
        <router-link :to="{ name: 'shotsListDetail', query: { type: 'shotsInDevelopment', list: false }}">
          <el-tooltip effect="dark" content="镜头进度" placement="bottom-start">
            <el-button type="primary" plain size="mini"><i class="el-icon-menu"></i></el-button>
          </el-tooltip>
        </router-link>
        <router-link :to="{ name: 'shotsListDetail', query: { type: 'list', list: true }}">
          <el-tooltip effect="dark" content="镜头列表" placement="bottom-start">
            <el-button type="primary" plain size="mini"><i class="el-icon-document"></i></el-button>
          </el-tooltip>
        </router-link>
      </div>
    </div>
    <div class="m-b-20 pos-rel">
      <router-link class="btn-link-large add-btn" to="add" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加镜头
      </router-link>
      <div class="pos-abs">
        <el-badge :value="workingCount" :max="10" class="item" v-if="inDevelopmentShow">
          <el-button size="large" @click="getShotsDetail('shotsInDevelopment')">制作中</el-button>
        </el-badge>
        <el-badge :value="waitingCount" :max="10" class="item" v-if="feedbackShow">
          <el-button size="large" @click="getShotsDetail('shotsInDevelopment')">反馈中</el-button>
        </el-badge>
        <el-badge :value="suspendCount" :max="10" class="item" v-if="suspendShow">
          <el-button size="large" @click="getShotsDetail('shotsSuspend')">暂停</el-button>
        </el-badge>
        <el-badge :value="waitingCount" :max="10" class="item">
          <el-button size="large" @click="getShotsDetail('shotsNotDevelopment')">等待资产</el-button>
        </el-badge>
        <el-badge :value="finishCount" :max="10" class="item" v-if="finishShow">
          <el-button size="large" @click="getShotsDetail('shotsFinish')">完成</el-button>
        </el-badge>
      </div>
    </div>
    <div class="shots ovf-hd">
      <el-col class="shots_list" :span="12">
        <el-card class="box-card">
          <div v-for="o in 4" :key="o" class="text item1">
            {{'列表内容 ' + o }}
          </div>
          <div class="box-after">
            <p class="box-card-p">Liangcy:</p>
            <div class="box-card-div">
              <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
              <p>时间：20180207-14：52</p>
              <p>时间：20180207-14：52</p>
            </div>
          </div>
          <div class="box-after">
            <p class="box-card-p">Liangcy:</p>
            <div class="box-card-div">
              <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
              <p>时间：20180207-14：52</p>
              <p>时间：20180207-14：52</p>
            </div>
          </div>
        </el-card>
        <div class="pos-rel p-t-20">
          <div class="block pages">
            <el-pagination
                @current-change="handleCurrentChange"
                layout="prev, pager, next"
                :page-size="limit"
                :current-page="currentPage"
                :total="dataCount">
            </el-pagination>
          </div>
        </div>
      </el-col>
      <el-col class="shots_list" :span="12">
        <el-card class="box-card">
          <div v-for="o in 4" :key="o" class="text item1">
            {{'列表内容 ' + o }}
          </div>
          <div class="box-after">
            <p class="box-card-p">Liangcy:</p>
            <div class="box-card-div">
              <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
              <p>时间：20180207-14：52</p>
              <p>时间：20180207-14：52</p>
            </div>
          </div>
          <div class="box-after">
            <p class="box-card-p">Liangcy:</p>
            <div class="box-card-div">
              <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
              <p>时间：20180207-14：52</p>
              <p>时间：20180207-14：52</p>
            </div>
          </div>
        </el-card>
        <div class="pos-rel p-t-20">
          <div class="block pages">
            <el-pagination
                @current-change="handleCurrentChange"
                layout="prev, pager, next"
                :page-size="limit"
                :current-page="currentPage"
                :total="dataCount">
            </el-pagination>
          </div>
        </div>
      </el-col>
    </div>
  </div>
</template>
<script>

  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        waitingCount: '',
        workingCount: '',
        suspendCount: '',
        finishCount: '',
        dataCount: null,
        currentPage: 1,
        limit: 10,
      }
    },
    methods: {
      handleCurrentChange(page) {
        this.getAllTaches(page)
      },
      getShotsDetail(name) {
        this.$router.push({name: 'shotsListDetail', query: {type: name, list: false}})
      }
    },
    components: {
      //   btnGroup
    },
    mixins: [http],
    computed: {
//      添加镜头按钮
      addShow() {
        return _g.getHasRule('shots-save')
      },
//      制作中按钮
      inDevelopmentShow() {
        return _g.getHasRule('shots-in_production_data')
      },
//      反馈中按钮
      feedbackShow() {
        return _g.getHasRule('shots-feedback_data')
      },
//      镜头暂停按钮
      suspendShow() {
        return _g.getHasRule('shots-pause_data')
      },
//      镜头完成按钮
      finishShow() {
        return _g.getHasRule('shots-finish_data')
      }
    }
  }
</script>
<style>
  .pos-abs {
    display: inline-block;
    right: 0;
  }

  .shots_list {
    /* border: 1px solid #000; */
    margin-bottom: 30px;
    /* margin-right: 45px; */
  }

  .text {
    font-size: 14px;
    /* padding: 18px 0; */
  }

  .item1 {
    padding: 18px 0;
  }

  .box-card-p, .box-card-div {
    /* display: inline-block; */
    float: left;
  }

  .box-after:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }
</style>

