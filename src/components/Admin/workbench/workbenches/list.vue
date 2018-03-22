<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/workbenches/list' }">工作台管理</el-breadcrumb-item>
        <el-breadcrumb-item>工作台</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="tx-r">
      <el-tooltip effect="dark" content="工作台进度" placement="bottom-start">
        <el-button type="primary" plain size="mini" @click="isList = true"><i class="el-icon-menu"></i></el-button>
      </el-tooltip>
      <el-tooltip effect="dark" content="工作台列表" placement="bottom-start">
        <el-button type="primary" plain size="mini" @click="isList = false"><i class="el-icon-document"></i></el-button>
      </el-tooltip>
    </div>
    <div class="m-b-20 ovf-hd">
      <el-tabs v-model="activeName" @tab-click="handleClick" class="fl" v-if="isList">
        <el-tab-pane label="任务" name="task">
          <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock">
            <el-card class="box-card point" v-for="block in blocks" :slot="block.id" :key="block.id">
              <div>
                <strong>id:</strong> {{ block.id }}
              </div>
              <div>
                {{ block.title }}
              </div>
            </el-card>
          </kanban-board>
        </el-tab-pane>
        <el-tab-pane label="等待上游" name="waiting">
          <div class="waiting ovf-hd">
            <el-col :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0 h-40 tx-c c-white">资产</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div>
                    </el-card>
                  </li>
                </ul>
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0 h-40 tx-c c-white" style="background: yellowgreen">镜头</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div>
                    </el-card>
                  </li>
                </ul>
              </div>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane label="完成" name="complete">
          <el-col :span="6" v-for="i in 7" :key="i">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card" style="">
                <div v-for="o in 3" :key="o" class="text item">
                  {{'列表内容 ' + o }}
                </div>
              </el-card>
            </div>
          </el-col>

        </el-tab-pane>
      </el-tabs>
      <el-table v-if="!isList" :data="tableData" stripe class="fl">
        <el-table-column type="selection" width="50"></el-table-column>
        <el-table-column prop="date" label="缩略图"></el-table-column>
        <el-table-column prop="name" label="场号"></el-table-column>
        <el-table-column prop="address" label="镜头号"></el-table-column>
        <el-table-column prop="address" label="难度"></el-table-column>
        <el-table-column prop="address" label="优先级"></el-table-column>
        <el-table-column prop="address" label="进度"></el-table-column>
        <el-table-column prop="address" label="计划开始"></el-table-column>
        <el-table-column prop="address" label="计划结束"></el-table-column>
        <el-table-column prop="address" label="实际开始"></el-table-column>
        <el-table-column prop="address" label="实际结束"></el-table-column>
        <el-table-column prop="explain" label="备注"></el-table-column>
      </el-table>
      <div class="task_detail fr">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>任务详情</span>
          </div>
          <div v-for="o in 4" :key="o" class="text item">
            {{'我是任务详情' + o }}
          </div>
        </el-card>
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
        isList:true,
        activeName: 'task',
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }],
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
          },{
            id: 3,
            status: '上游反馈',
            title: 'Test',
          },{
            id: 4,
            status: '等待制作',
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
  .el-tabs{
    width: 75%;
  }

  .task_detail{
    width: 25%;
    margin-top: 55px;
  }
  .waiting h2{
    font-size: .8rem;
    background: lightpink;

  }
  .drag-container{
    margin: 0;
    max-width: initial;
  }
  .drag-container .drag-column{
    flex: inherit;
    width: 25%;
    margin: 0;
    background: none;
  }
  .drag-container .drag-column .drag-column-header{
    text-align: center;
  }
  .drag-container .drag-column .drag-column-header h2{
    width: 100%;
    color: #fff;
  }
  .drag-item{
    height: inherit;
    padding: 0;
    margin: 0;
    background: none;
  }
  .drag-item.is-moving{
    background: none;
  }
  .drag-column-制作中 .drag-column-header, .drag-column-制作中 .drag-options, .drag-column-制作中 .is-moved {
    background: #fb7d44;
  }
  .drag-column-反馈中 .drag-column-header, .drag-column-反馈中 .drag-options, .drag-column-反馈中 .is-moved {
    /*background: #2a92bf;*/
    background: #f4ce46;
  }
  .drag-column-上游反馈 .drag-column-header, .drag-column-上游反馈 .drag-options, .drag-column-上游反馈 .is-moved {
    background: #2a92bf;
  }
  .drag-column-等待制作 .drag-column-header, .drag-column-等待制作 .drag-options, .drag-column-等待制作 .is-moved {
    background: #00b961;
  }
</style>