<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/projects/list' }">镜头管理</el-breadcrumb-item>
        <el-breadcrumb-item>镜头汇总</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <router-link class="btn-link-large add-btn" to="add" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加项目
      </router-link>
      <div class="pos-abs">
        <el-badge :value="workingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(2)">制作中</el-button>
        </el-badge>
        <el-badge :value="waitingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(1)">反馈中</el-button>
        </el-badge>
        <el-badge :value="suspendCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(3)">暂停</el-button>
        </el-badge>
        <el-badge :value="waitingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(1)">等待制作</el-button>
        </el-badge>
        <el-badge :value="waitingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(1)">等待资产</el-button>
        </el-badge>
        <el-badge :value="finishCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(4)">完成</el-button>
        </el-badge>
      </div>
    </div>
    <div class="shots">
        <el-col class="shots_list" :span="12">
            <el-card class="box-card">
                <div v-for="o in 4" :key="o" class="text item1">
                    {{'列表内容 ' + o }}
                </div>
                <div>
                    <p class="box-card-p">Liangcy:</p>
                    <div class="box-card-div">
                        <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
                        <p>时间：20180207-14：52</p>
                        <p>时间：20180207-14：52</p>
                    </div>
                </div>
                <div>
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
                <div>
                    <p class="box-card-p">Liangcy:</p>
                    <div class="box-card-div">
                        <p>提交文件---fuy003002_rig_liangcy_sdddf_v0101提交dailies。</p>
                        <p>时间：20180207-14：52</p>
                        <p>时间：20180207-14：52</p>
                    </div>
                </div>
                <div>
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
        <!-- <el-col class="shots_list" :span="12"> -->
            <!-- <el-card :body-style="{ padding: '0px' }">
            <img class="image">
            <div style="padding: 14px;">
                <div class="content">
                <p><span>总时长：</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>遮幅比：</span></p>
                <p><span>计划开始</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>计划结束：</span></p>
                <p><span>项目帧率：</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>分辨率：</span></p>
                <p><span>视效总监：</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>视效总制片：</span></p>
                <p><span>现场制片：</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>现场指导：</span></p>
                <p><span :title="'二级公司制片：'">二级公司制片：</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>内部协调：}</span>
                </p>
                </div>
            </div>
            </el-card> -->
        <!-- </el-col> -->
    <div class="pos-rel p-t-20">
      <!--<btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup>-->
    </div>
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
    },
    components: {
    //   btnGroup
    },
    mixins: [http],
    computed: {
      addShow() {
        return _g.getHasRule('projects-save')
      },
      editShow() {
        return _g.getHasRule('projects-update')
      },
      deleteShow() {
        return _g.getHasRule('projects-delete')
      }
    }
}
</script>
<style>
  .pos-abs {
    display: inline-block;
    right: 0;
  }
  .shots_list{
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
  .box-card-p, .box-card-div{
      /* display: inline-block; */
      float: left;
  }
</style>

