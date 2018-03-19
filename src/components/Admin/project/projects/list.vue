<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/projects/list' }">项目管理</el-breadcrumb-item>
        <el-breadcrumb-item>项目汇总</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <router-link class="btn-link-large add-btn" to="add" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加项目
      </router-link>
      <div class="pos-abs">
        <el-badge :value="waitingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(1)">等待中</el-button>
        </el-badge>
        <el-badge :value="workingCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(2)">制作中</el-button>
        </el-badge>
        <el-badge :value="suspendCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(3)">暂停</el-button>
        </el-badge>
        <el-badge :value="finishCount" :max="10" class="item">
          <el-button size="large" @click="getAllProjects(4)">完成</el-button>
        </el-badge>
      </div>
    </div>
    <div>
      <el-col class="project_list" :span="11" v-for="(item, index) in tableData" :key="item.id">
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
                <!-- <router-link :to="{ name: 'projectsEdit', params: { id: item.id }}">
                  <el-button size="small" type="primary">编辑</el-button>
                </router-link> -->
                 <el-button size="small" type="primary" @click="ProjectEdit(item.id)">编辑</el-button>
              </span>
              <el-button size="small" type="danger" @click="confirmDelete(item)">删除</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </div>
    <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock"></kanban-board>
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
        address: window.baseUrl + '/',
        tableData: [],
        uid: '',
        keywords: {},
        waitingCount: '',
        workingCount: '',
        suspendCount: '',
        finishCount: '',
        stages: ['on-hold', 'in-progress', 'needs-review', 'approved'],
        blocks: [
          {
            id: 1,
            status: 'on-hold',
            title: 'Test',
          },{
            id: 1,
            status: 'on-hold',
            title: 'Test',
          },
        ],
      }
    },
    methods: {
      updateBlock(id, status) {
        this.blocks.find(b => b.id === Number(id)).status = status;
      },
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
      //项目编辑跳转
      ProjectEdit(id){
        const data = {
          table: 'project',
          table_id: id,
          uid: this.uid
        }
        this.apiPost('admin/check_auth', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.$router.push({name: 'projectsEdit', params: { id: id }})
          })
        })
      },
//      获取项目列表
      getAllProjects(status) {
        this.loading = true
        const data = {
          params: {
            keywords: {
              status: status
            }
          }
        }
        this.apiGet('admin/projects', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.uid = data.uid
            this.waitingCount = data.waitingCount
            this.workingCount = data.workingCount
            this.suspendCount = data.suspendCount
            this.finishCount = data.finishCount
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

  .project_list{
    margin-bottom: 30px;
    margin-right: 45px;
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