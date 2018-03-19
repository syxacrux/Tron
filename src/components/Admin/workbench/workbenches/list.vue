<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/workbenches/list' }">工作台管理</el-breadcrumb-item>
        <el-breadcrumb-item>工作台</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="任务" name="task">
          <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock"></kanban-board>
        </el-tab-pane>
        <el-tab-pane label="等待上游" name="waiting">等待上游</el-tab-pane>
        <el-tab-pane label="完成" name="complete">完成</el-tab-pane>
      </el-tabs>
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
        activeName: 'task',
        stages: ['制作中', '反馈中', '上游反馈', '等待制作'],
        blocks: [
          {
            id: 1,
            status: '制作中',
            title: 'Test',
          },{
            id: 2,
            status: '上游反馈',
            title: 'Test',
          },
        ],
      }
    },
    methods: {
      updateBlock(id, status) {
        console.log(id)
        console.log(status)
        console.log(arguments)
        this.blocks.find(b => b.id === Number(id)).status = status;
      },
      handleClick(tab, event) {
        console.log(tab, event);
      },
//      获取项目列表
      getAllProjects(status) {
      },
//      初始化项目列表内容
      init() {
//        this.getAllProjects(0)
      }
    },
    created() {
      this.init()
    },
    components: {},
    mixins: [http],
    computed: {
//      addShow() {
//        return _g.getHasRule('projects-save')
//      },
//      editShow() {
//        return _g.getHasRule('projects-update')
//      },
//      deleteShow() {
//        return _g.getHasRule('projects-delete')
//      }
    }
  }
</script>
<style>
  .item {
    margin-right: 20px;
  }
</style>