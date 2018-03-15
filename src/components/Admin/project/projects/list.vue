<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/projects/list' }">项目管理</el-breadcrumb-item>
        <el-breadcrumb-item>项目</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <router-link class="btn-link-large add-btn" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加项目
      </router-link>
      <div class="pos-abs">
        <el-badge :value="12" class="item" @click="getAllProjects(1)">
          <el-button size="large">等待中</el-button>
        </el-badge>
        <el-badge :value="3" class="item" @click="getAllProjects(2)">
          <el-button size="large">制作中</el-button>
        </el-badge>
        <el-badge :value="12" class="item" @click="getAllProjects(3)">
          <el-button size="large">暂停</el-button>
        </el-badge>
        <el-badge :value="3" class="item" @click="getAllProjects(4)">
          <el-button size="large">完成</el-button>
        </el-badge>
      </div>
    </div>
    <!--<el-col :span="11" v-for="(item, index) in tableData" :key="item" :offset="index > 0 ? 1 : 0">-->
    <div>
      <el-col :span="11" v-for="(item, index) in tableData" :key="item.id" :offset="index > 0 ? 1 : 0">
        <el-card :body-style="{ padding: '0px' }">
          <img :src="address + item.project_image" class="image">
          <div style="padding: 14px;">
            <div class="content">
              <p><span>总时长：{{ item.duration }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>遮幅比：{{ item.aspect_ratio }}</span></p>
              <p><span>计划开始：{{ item.plan_start_time }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>计划结束：{{ item.plan_end_time }}</span></p>
              <p><span>项目帧率：{{ item.frame_rate }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>分辨率：{{ item.resolutic }}</span></p>
              <p><span>视效总监：{{ item.visual_effects_boss }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>视效总制片：{{ item.visual_effects_producer }}</span></p>
              <p><span>现场制片：{{ item.scene_producer }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>现场指导：{{ item.scene_director }}</span></p>
              <p><span :title="'二级公司制片：' + item.second_company_producer">二级公司制片：{{ item.second_company_producer }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>内部协调：{{ item.inside_coordinate }}</span>
              </p>
            </div>
            <div class="bottom clearfix tx-r">
              <span v-if="editShow">
                <router-link :to="{ name: 'projectsEdit', params: { id: item.id }}">
                  <el-button size="small" type="primary">编辑</el-button>
                </router-link>
              </span>
              <el-button size="small" type="danger" @click="confirmDelete(item)">删除</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </div>
    <div class="pos-rel p-t-20">
      <!--<btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup>-->
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
        address: window.HOST + '/',
        tableData: []
      }
    },
    methods: {
//      删除项目执行方法
      confirmDelete(item) {
        this.$confirm('确认删除该项目?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/projects/', item.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              setTimeout(() => {
                _g.shallowRefresh(this.$route.name)
              }, 1500)
            })
          })
        }).catch(() => {
          // handle error
        })
      },
//      获取项目列表
      getAllProjects(status) {
        this.loading = true
        const data = {
          params: {
            status: status
          }
        }
        this.apiGet('admin/projects', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
          })
        })
      },
//      初始化项目列表内容
      init() {
        this.getAllProjects(0)
      }
    },
    created() {
      this.init()
    },
    components: {
      btnGroup
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
  .item {
    margin-right: 20px;
  }

  .content p {
    font-size: 13px;
    color: #333;
  }

  .content p span {
    color: #666;
    display: inline-block;
    width: 48%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .pos-abs {
    display: inline-block;
    right: 0;
  }

  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    width: 100%;
    height: 300px;
    display: block;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }

  .clearfix:after {
    clear: both
  }
</style>