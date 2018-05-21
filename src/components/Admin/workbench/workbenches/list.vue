<template>
  <div class="workbench_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/workbenches/list' }">工作台管理</el-breadcrumb-item>
        <el-breadcrumb-item>工作台</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 ovf-hd">
      <div class="fl w-1500">
        <el-row :gutter="10" class="m-b-5">
          <el-col :span="4">
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="screenChange(1)">
              <el-option
                  v-for="item in screeningProject"
                  :key="item.id"
                  :label="item.project_name"
                  :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="4">
            <el-select v-model="search.type" style="margin-left: 10px;" placeholder="请选择类型" @change="screenChange(2)">
              <el-option label="镜头" value="1"></el-option>
              <el-option label="资产" value="2"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id" style="margin-left: 10px;" placeholder="请选择场号或资产类型"
                       @change="screenChange(3)">
              <el-option
                  v-for="item in screeningSite"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.resource_id" style="margin-left: 10px;" placeholder="请选择镜头号或资产简称"
                       @change="screenChange()">
              <el-option
                  v-for="item in screeningShot"
                  :key="item.id"
                  :label="item.shot_number"
                  :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-input class="w-200" style="margin-left: 10px;" v-model.trim="search.shot_number" placeholder="请输入场号镜头号">
              <el-button slot="append" icon="el-icon-search" circle @click="searchPublic"></el-button>
            </el-input>
          </el-col>
        </el-row>
      </div>
      <div class="tx-r">
        <el-tooltip v-if="kanbanShow" effect="dark" content="工作台进度" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isTaskLIst = true"><i class="el-icon-menu"></i>
          </el-button>
        </el-tooltip>
        <el-tooltip v-if="listShow" effect="dark" content="工作台列表" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="workList()"><i class="el-icon-document"></i></el-button>
        </el-tooltip>
      </div>
    </div>

    <div class="m-b-20 ovf-hd">
      <el-tabs v-if="isTaskLIst" v-model="activeName" @tab-click="handleClick" class="fl">
        <el-tab-pane label="任务" name="task">
          <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock">
            <el-card class="box-card point" v-for="block in blocks" :slot="block.id" :key="block.id">
              <div>
                <el-card class="work-box box-card box-texts">
                  <div class="text" @click="taskDetail(block.id)">
                    <div class="text-Lens pos-rel">
                      <p class="text-Lens-name">
                        {{ block.project_name }}：<span>{{ block.shot_number }}:{{ block.task_byname }}</span></p>
                      <p class="text-Lens-rank pos-abs">
                        <el-tag type="danger" v-if="block.difficulty != '' ">
                          <el-tooltip class="pointer" effect="dark" content="难度"
                                      placement="bottom-start">
                            <span>{{ block.difficulty }}</span>
                          </el-tooltip>
                        </el-tag>
                        <el-tag type="warning" v-if="block.task_priority_level != '' ">
                          <el-tooltip class=" pointer" effect="dark" content="优先级"
                                      placement="bottom-start">
                            <span>{{ block.task_priority_level }}</span>
                          </el-tooltip>
                        </el-tag>
                      </p>
                    </div>
                    <div class="text-Lens m-t-10">
                      <!--<p class="task-Lens-assets text-p">-->
                        <!--<el-tag type="info" v-if="block.id > 0">37次</el-tag>-->
                      <!--</p>-->
                      <p class="text-Lens-time tx-r">
                        <span>
                          <el-tooltip class=" pointer" effect="dark" content="任务剩余天数"
                                      placement="bottom-start">
                            <span>{{ shotRemainDay(block.plan_end_timestamp) }}天</span>
                          </el-tooltip>
                          <el-tooltip class=" pointer" effect="dark" content="任务在次状态时间"
                                      placement="bottom-start">
                            <span>{{ shotCreateTime(block.create_timestamp) }}天</span>
                          </el-tooltip>
                          <el-tooltip class=" pointer" effect="dark" content="任务分配时间"
                                      placement="bottom-start">
                            <span>{{ block.task_allot_days }}</span>
                          </el-tooltip>
                        </span>
                        <span>
                          <el-tooltip class=" pointer" effect="dark" content="预计结束时间"
                                      placement="bottom-start">
                            <span>{{ j2time(block.plan_end_timestamp) }}</span>
                          </el-tooltip>
                        </span>
                      </p>
                    </div>
                    <div class="text-Lens-link m-t-10">
                      <!--<el-button size="small" @click="submitDailies">提交Dailies</el-button>-->
                      <!--<el-button size="small" @click="render">渲染</el-button>-->
                      <!--<el-tag type="warn">资产</el-tag>-->
                      <el-tag type="danger" v-if="block.task_finish_degree.finish_degree < 100">
                        {{ block.task_finish_degree.tache_byname }}:{{ block.task_finish_degree.finish_degree }}%
                      </el-tag>
                      <el-tag type="success" v-else>
                        {{ block.task_finish_degree.tache_byname }}:{{ block.task_finish_degree.finish_degree }}%
                      </el-tag>
                    </div>
                  </div>
                </el-card>
              </div>
            </el-card>
          </kanban-board>
          <div class="block task-block" v-if="blocksDataCount.length">
            <el-pagination
                @current-change="taskCurrentChange"
                layout="prev, pager, next, jumper"
                :page-size="limit"
                :current-page="currentPage"
                :total="blocksDataCount">
            </el-pagination>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="assetShow || shotShow" label="等待上游" name="waiting">
          <div class="waiting ovf-hd">
            <el-col v-if="assetShow" :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0 h-40">资产</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="block in upstreamListTask" :key="block.id" class="text"@click="taskDetail(block.id)">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">
                            {{ block.project_name }}：<span>{{ block.shot_number }}:{{ block.task_byname }}</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="danger" v-if="block.difficulty != '' ">
                              <el-tooltip class=" pointer" effect="dark" content="难度"
                                          placement="bottom-start">
                                <span>{{ block.difficulty }}</span>
                              </el-tooltip>
                            </el-tag>
                            <el-tag type="warning" v-if="block.task_priority_level != '' ">
                              <el-tooltip class=" pointer" effect="dark" content="优先级"
                                          placement="bottom-start">
                                <span>{{ block.task_priority_level }}</span>
                              </el-tooltip>
                            </el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                            <span>
                              <el-tooltip class=" pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>{{ block.surplus_days }}</span>
                              </el-tooltip>
                              <el-tooltip class=" pointer" effect="dark" content="任务在此状态时间"
                                          placement="bottom-start">
                                <span>无</span>
                              </el-tooltip>
                              <el-tooltip class=" pointer" effect="dark" content="任务分配时间"
                                          placement="bottom-start">
                                <span>{{ block.task_allot_days }}</span>
                              </el-tooltip>
                            </span>
                          </p>
                          <p>
                            <span>
                              <el-tooltip class=" pointer" effect="dark" content="预计结束时间"
                                          placement="bottom-start">
                                <span>{{ j2time(block.plan_end_timestamp) }}</span>
                              </el-tooltip>
                            </span>
                          </p>
                        </div>
                        <div class="text-Lens-link m-t-10">
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">合成: 0%</el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <div class="pos-rel p-t-20">
                  <div class="block pages" v-if="upstreamDataCountTask.length">
                    <el-pagination
                        @current-change="upstreamTaskCurrentChange"
                        layout="prev, pager, next, jumper"
                        :page-size="20"
                        :current-page="currentPage"
                        :total="upstreamDataCountTask">
                    </el-pagination>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col v-if="shotShow" :span="12">
              <div class="grid-content">
                <h2 class="m-0 h-40">镜头</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="block in upstreamListShot" :key="block.id" class="text"
                      @click="taskDetail(block.id)">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">
                            {{ block.project_name }}：<span>{{ block.shot_number }}:{{ block.task_byname }}</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="danger" v-if="block.difficulty != '' ">
                              <el-tooltip class=" pointer" effect="dark" content="难度"
                                          placement="bottom-start">
                                <span>{{block.difficulty}}</span>
                              </el-tooltip>
                            </el-tag>
                            <el-tag type="warning" v-if="block.task_priority_level != '' ">
                              <el-tooltip class=" pointer" effect="dark" content="优先级"
                                          placement="bottom-start">
                                <span>{{block.task_priority_level}}</span>
                              </el-tooltip>
                            </el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                            <span>
                              <el-tooltip class=" pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>{{ block.surplus_days }}</span>
                              </el-tooltip>
                              <el-tooltip class=" pointer" effect="dark" content="任务在此状态时间"
                                          placement="bottom-start">
                                <span>{{ shotCreateTime(block.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class=" pointer" effect="dark" content="任务分配时间"
                                          placement="bottom-start">
                                <span>{{ block.task_allot_days }}</span>
                              </el-tooltip>
                            </span>
                          </p>
                          <p>
                            <span>
                                <el-tooltip class=" pointer" effect="dark" content="预计结束时间"
                                            placement="bottom-start">
                                <span>{{ j2time(block.plan_end_timestamp) }}</span>
                                    </el-tooltip>
                            </span>
                          </p>
                        </div>
                        <div class="text-Lens-link m-t-10">
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">合成: 0%</el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <div class="block task-block" v-if="upstreamDataCountShot.length">
                  <el-pagination
                      @current-change="upstreamShotCurrentChange"
                      layout="prev, pager, next, jumper"
                      :page-size="10"
                      :current-page="currentPage"
                      :total="upstreamDataCountShot">
                  </el-pagination>
                </div>
              </div>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="finishShow" label="完成" name="complete">
          <div class="waiting ovf-hd">
            <el-col :span="6" v-for="block in completeList" :key="block.id">
              <div class="grid-content bg-purple p-b-5" @click="taskDetail(block.id)">
                <el-card class="box-card ">
                  <div class="text">
                    <div class="text-Lens pos-rel">
                      <p class="text-Lens-name">
                        {{block.project_name}}：<span>{{block.shot_number}}:{{block.task_byname}}</span></p>
                      <p class="text-Lens-rank pos-abs">
                        <el-tag type="danger" v-if="block.difficulty != '' ">
                          <el-tooltip class=" pointer" effect="dark" content="难度"
                                      placement="bottom-start">
                            <span>{{block.difficulty}}</span>
                          </el-tooltip>
                        </el-tag>
                        <el-tag type="warning" v-if="block.task_priority_level != '' ">
                          <el-tooltip class=" pointer" effect="dark" content="优先级"
                                      placement="bottom-start">
                            <span>{{block.task_priority_level}}</span>
                          </el-tooltip>
                        </el-tag>
                      </p>
                    </div>
                    <div class="text-Lens text-wan  m-t-10">
                      <p class="text-Lens-time tx-r">
                        <span>
                          <el-tooltip class=" pointer" effect="dark" content="任务剩余天数"
                                      placement="bottom-start">
                            <span>{{block.surplus_days}}</span>
                          </el-tooltip>
                          <el-tooltip class=" pointer" effect="dark" content="任务再次状态时间"
                                      placement="bottom-start">
                            <span>无</span>
                          </el-tooltip>
                          <el-tooltip class=" pointer" effect="dark" content="任务分配时间"
                                      placement="bottom-start">
                            <span>{{block.task_allot_days}}</span>
                          </el-tooltip>
                        </span>
                      </p>
                      <p class="text-Lens-time tx-r">
                        <span>
                          <el-tooltip class=" pointer" effect="dark" content="实际结束时间"
                                      placement="bottom-start">
                            <span>{{ j2time(block.actually_start_timestamp) }}</span>
                          </el-tooltip>
                          <el-tooltip class=" pointer" effect="dark" content="预计结束时间"
                                      placement="bottom-start">
                            <span>{{ j2time(block.plan_end_timestamp) }}</span>
                          </el-tooltip>
                        </span>
                      </p>
                    </div>
                    <div class="text-Lens-link m-t-10">
                      <el-tag type="success">已完成</el-tag>
                    </div>
                  </div>
                </el-card>
              </div>
            </el-col>
          </div>
          <div class="block task-block" v-if="completeDataCount.length">
            <el-pagination
                @current-change="completeCurrentChange"
                layout="prev, pager, next, jumper"
                :page-size="20"
                :current-page="currentPage"
                :total="completeDataCount">
            </el-pagination>
          </div>
        </el-tab-pane>
      </el-tabs>
      <div v-if="!isTaskLIst">
        <el-table ref="multipleTable" :data="tableList" tooltip-effect="dark" @selection-change="handleSelectionChange"
                  @row-click="taskDetail">
          <el-table-column type="selection" width="50"></el-table-column>
          <el-table-column prop="project_name" label="项目"></el-table-column>
          <el-table-column prop="field_number" label="场号/类型"></el-table-column>
          <el-table-column prop="shot_number" label="镜头号/名称"></el-table-column>
          <el-table-column prop="difficulty" label="难度"></el-table-column>
          <el-table-column prop="task_priority_level" label="优先级"></el-table-column>
          <el-table-column prop="status_cn" label="进度"></el-table-column>
          <el-table-column prop="plan_start_time" label="计划开始"></el-table-column>
          <el-table-column prop="plan_end_time" label="计划结束"></el-table-column>
          <el-table-column prop="actually_start_timestamp" label="实际开始"></el-table-column>
          <el-table-column prop="actually_end_timestamp" label="实际结束"></el-table-column>
          <el-table-column prop="user_name" label="制作人"></el-table-column>
        </el-table>
        <div class="pos-rel p-t-20">
          <div class="block tx-r">
            <el-pagination
                @current-change="tableListCurrentChange"
                layout="prev, pager, next, jumper"
                :page-size="10"
                :current-page="currentPage"
                :total="tableListDataCount">
            </el-pagination>
          </div>
        </div>
      </div>
      <transition name="el-zoom-in-top">
        <div class="task_detail fr" v-show="isTaskDetailShow">
          <el-card class="box-card task-xing">
            <div slot="header" class="clearfix">
              <span>任务详情</span>
              <i v-if="editShow" class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editWorkbench"></i>
              <i v-if="deleteShow" class="el-icon-delete m-l-5 fz-14 c-light-gray pointer"
                 @click="deleteTask(finishList.id)"></i>
              <i class="el-icon-close fr pointer" @click="isTaskDetailShow = !isTaskDetailShow"></i>
            </div>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">任务缩略图：<img :src="finishList.task_image" alt="" class="vtcal-mid h-40"></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">任务简称：<span>{{ finishList.task_byname }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">任务优先级：<span>{{ finishList.task_priority_level_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">任务难度：<span>{{ finishList.difficulty_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">任务计划开始时间：<span>{{ finishList.plan_start_time }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">任务计划结束时间：<span>{{ finishList.plan_end_time }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">
                  环节制作人：
                  <!-- <span class="" v-for="(item, index) in finishList.user_data" :key="index">
                    {{item.realname}}
                  </span> -->
                  <el-tag size="mini" v-for="(item, index) in finishList.user_data" :closable="deleteTaskTacheStudio"
                          type="info" @close="deletetLink(index, item)" :key="item.id">
                    {{item.realname}}
                  </el-tag>
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">任务制作要求：<span>{{ finishList.make_demand }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">所属项目：<span>{{ finishList.project_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">镜头名称/资产名称：<span>{{ finishList.shot_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头简称/资产简称：<span>{{ finishList.shot_byname }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">场号/集号：<span>{{ finishList.field_number }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头编号：<span>{{ finishList.shot_number }}</span></p>
              </el-col>
            </el-row>
          </el-card>
        </div>
      </transition>
    </div>
    <editWorkbenches ref="editWorkbenches" @updataTaskdetail="dataTaskdetail" :message="finishList"></editWorkbenches>
  </div>
</template>

<script>
  import editWorkbenches from '../workbenches/edit.vue'
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {
        // isTaskLIst:true,
        isTaskLIst: true,//是否显示任务
        isTaskDetailShow: false,//是否显示详情
        limit: 40,
        id: 0,
        currentPage: 1,
        activeName: 'task',//默认任务页面显示
        address: window.baseUrl + '/',
        // frequencyState:true,
        tabVale: 1,
        screeningProject: [],//项目下拉列表数据
        // screeningType:[],//类型
        screeningSite: [],//场号下拉列表数据
        screeningShot: [],
        search: {
          project_id: '',//项目下拉框选中的值
          type: '',//类型
          field_id: '',//场号下拉框选中的值
          resource_id: '',//镜头选中的值
          shot_number: ''//输入狂的值
        },
        stages: ['制作中', '反馈中', '等待制作', '提交发布'],
        blocksDataCount: 0,//任务的数量
        blocks: [],//任务页面列表
        upstreamDataCountTask: 0,//等待上游资产的数量
        upstreamDataCountShot: 0,//等待上游镜头的数量
        upstreamListTask: [],//等待上游列表
        upstreamListShot: [],//等待上游列表
        completeDataCount: 0,//完成数量
        completeList: [],//完成列表
        tableList: [],  //工作台列表
        tableListDataCount: 0,//工作台列表数量
        // limit: 10,
        finishList: {},//任务详情
      }
    },
    methods: {
      submitDailies() {

      },
      render() {

      },
      editWorkbench () {
        this.$refs.editWorkbenches.open()
      },
      //      删除任务
      deleteTask (id) {
        this.$confirm('确认删除该任务?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/workbenches/', id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.init(this.activeName)
              this.isTaskDetailShow = false
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      //工作台详情删除制作人
      deletetLink (tache_name, items) {
        // console.log(item)
        this.$confirm('确认删除该制作人?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          const data = {
            task_id: this.id,
            user_id: items.user_id
          }
          this.apiPost('task/user_del', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              // this.taskDetail(this.id)
              this.dataTaskdetail()
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      updateBlock (id, status) {
        const data = {
          'id': Number(id),
          'status': status
        }
        this.blocks.find(b => b.id === Number(id)).status = status;
        this.apiPost('task/change_status', data).then((res) => {
          _g.shallowRefresh(this.$route.name)
          this.handelResponse(res, (data) => {
          }, () => {
//            this.getAllWorkbenches('admin/workbenches', this.currentPage, 1, 40)
//            this.isLoading = !this.isLoading
          })
        })
      },
      handleSelectionChange (val) {
        this.multipleSelection = val;
      },
      //tab切换
      handleClick (tab, event) {
        // console.log(tab, event);
        // console.log(tab)
        this.tabVale = parseInt(tab.index) + 1
        this.publicRequest(tab.name, parseInt(tab.index) + 1)
      },

      //tab切换、搜索时 请求调用
      publicRequest (type, value) {
        switch (type) {
          case 'task':
            this.getAllWorkbenches('admin/workbenches', 1, value, 40)//任务页面传参数
            break;
          case 'waiting':
            this.getAllWorkbenches('task/upper_shots', 1, value, 10)//镜头
            break;
          case 'complete':
            this.getAllWorkbenches('task/finish_task', 1, value, 10)//完成页面传参数
            break;
        }
      },
      //工作台列表
      workList () {
        this.isTaskLIst = false
        this.getAllWorkbenches('task/index_list', 1, 11, 10)
      },
      //      时间抽转换为时间格式
      j2time (time) {
        var time = new Date(time * 1000);
        let year = time.getFullYear()
        let month = time.getMonth() + 1 < 10? '0' + (time.getMonth() + 1): time.getMonth() + 1
        let date = time.getDate() < 10? '0' + time.getDate(): time.getDate()
        let hour = time.getHours() < 10? '0' + time.getHours(): time.getHours()
        let min = time.getMinutes() < 10? '0' + time.getMinutes(): time.getMinutes()
        let seconds = time.getSeconds() < 10? '0' + time.getSeconds(): time.getSeconds()
        return year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + seconds
      },
//      任务剩余天数 = 预计结束时间戳 - 当前时间戳
      shotRemainDay (end_time) {
        return Math.ceil((end_time - new Date() / 1000) / 86400)
      },
//      任务建立时间 = 当前时间戳 - 任务创建时间戳
      shotCreateTime (create_time) {
        return Math.ceil((new Date() / 1000 - create_time) / 86400)
      },
      // 任务切换页码
      taskCurrentChange (page) {
        this.getAllWorkbenches('admin/workbenches', page, 1, 40)
      },
      //等待上游镜头切换页码
      upstreamShotCurrentChange (page) {
        this.getAllWorkbenches('task/upper_shots', page, 2, 10)
      },
      //等待上游资产切换页码
      upstreamTaskCurrentChange (page) {
        this.getAllWorkbenches('task/upper_assets',page,2,10)
      },
      //完成切换页码
      completeCurrentChange (page) {
        this.getAllWorkbenches('task/finish_task', page, 3, 10)
      },
      //工作台列表切换页码
      tableListCurrentChange (page) {
        this.getAllWorkbenches('task/index_list', page, 11, 10)
      },
      /*
       * 获取项目列表
       *   params: {
       *     state:请求地址
       *     status:tab切换传入的值
       *     this.search{
       *      选择项目的id
       *      选择场号的id
       *      镜头号
       *     }
       *   }
       * */
      getAllWorkbenches (state, page, status, limit) {
        this.loading = true
        const data = {
          params: {
            keywords: this.search,
            page: page,
            limit: limit,
          }
        }
        this.apiGet(state, data).then((res) => {
          this.handelResponse(res, (data) => {
            switch (status) {
              case 1:
                this.blocks = data.list
                _(this.blocks).forEach((res1, res2) => {
                  this.blocks[res2].status = res1.task_status == 1? '等待制作': (res1.task_status == 5? '制作中': (res1.task_status == 15? '反馈中': '提交发布'))
                })
                this.blocksDataCount = data.dataCount
                break;
              case 2://等待上游列表
                this.upstreamListShot = data.list
                this.upstreamDataCountShot = data.dataCount
                break;
              case 3://完成列表
                this.completeList = data.list
                this.completeDataCount = data.dataCount
                break;
              case 11://工作台列表
                this.tableList = data.list
                this.tableListDataCount = data.dataCount
                break;
            }
          })
        })
      },
      //请求所有项目
      getProjects () {
        this.apiGet('admin/projects').then((res) => {
          this.handelResponse(res, (data) => {
            this.screeningProject = data.list
          })
        })
      },
      //请求项目下的场号、镜头和对应的任务列表
      screenChange (svuel) {
        switch (svuel) {
          case 1://请求场号
            this.search.field_id = ''
            this.search.resource_id = ''
            const data = {
              params: {
                project_id: this.search.project_id
              }
            }
            this.apiGet('admin/get_fields', data).then((res) => {//请求项目下的场号
              this.handelResponse(res, (data) => {
                // this.screeningSite = data
              })
            })
            break
          case 2://请求类型
            this.search.field_id = ''
            this.search.resource_id = ''
            const datatype = {
              params: {
                project_id: this.search.project_id,
                type: this.search.type
              }
            }
            this.apiGet('admin/get_fields', datatype).then((res) => {//请求项目下的场号
              this.handelResponse(res, (data) => {
                this.screeningSite = data

              })
            })
            break
          case 3://请求镜头号
            this.search.resource_id = ''
            const datas = {
              params: {
                field_id: this.search.field_id
              }
            }
            this.apiGet('admin/get_shot_num', datas).then((res) => {//请求项目下的场号
              this.handelResponse(res, (data) => {
                this.screeningShot = data
              })
            })
            break
        }
        //对应的任务列表
        this.screeningPublic()
      },
      //点击搜索按钮
      searchPublic () {
        //对应的任务列表
        if (this.search.shot_number.length === 6) {
          // this.search.project_id = ''
          this.search.field_id = ''
          this.search.resource_id = ''
          this.screeningPublic()
        }
        if (!this.search.field_id && this.search.shot_number.length === 3) {
          _g.toastMsg('warning', '请选择所属项目及场号')
          return
        } else {
          this.search.resource_id = ''
          this.screeningPublic()
        }
      },
      //筛选时请求工作台的、工作台列表的数据
      screeningPublic () {
        this.publicRequest(this.activeName, this.tabVale)//工作台的
        this.getAllWorkbenches('task/index_list', 1, 11, 10)//工作台列表
      },
      //      点击任务显示任务详情
      taskDetail (id) {
        //        判断任务详情传入值是否为数字
        if (!/^[0-9]*$/.test(id)) {
          id = id.id
        }
        this.id = id
        this.apiGet('admin/workbenches/' + id).then((res) => {
          this.handelResponse(res, (data) => {
            this.finishList = data
          })
        })
        // this.screeningPublic()
        if (this.isTaskDetailShow) {

        } else {
          this.isTaskDetailShow = !this.isTaskDetailShow
        }
      },
      //编辑页面调用
      dataTaskdetail () {
        this.screeningPublic()
        this.taskDetail(this.id)
      },
//      初始化项目列表内容
      init () {
        this.getAllWorkbenches('admin/workbenches', 1, 1, 40)
        this.getProjects()
      }
    },
    created () {
      this.init()
    },
    components: {
      editWorkbenches
    },
    mixins: [http],
    computed: {
//      工作台列表
      kanbanShow () {
        return _g.getHasRule('workbenches-index')
      },
//      工作台标准列表
      listShow () {
        return _g.getHasRule('workbenches-index_list')
      },
//      编辑任务按钮
      editShow () {
        return _g.getHasRule('workbenches-update')
      },
//      删除任务按钮
      deleteShow () {
        return _g.getHasRule('workbenches-delete')
      },
//      删除所属任务制作人
      deleteTaskTacheStudio () {
        return _g.getHasRule('workbenches-delete_userid')
      },
//      等待上游资产列表
      assetShow () {
        return _g.getHasRule('workbenches-wait_upper_assets')
      },
//      等待上游镜头列表
      shotShow () {
        return _g.getHasRule('workbenches-wait_upper_shots')
      },
      finishShow () {
        return _g.getHasRule('workbenches-finish_list')
      }
    }
  }
</script>
<style>
  .item {
    margin-right: 20px;
  }

  .el-tabs {
    width: 95%;
  }

  .task_detail {
    width: 25%;
    margin-top: 55px;
  }

  .drag-container {
    margin: 0;
    max-width: initial;
  }

  .drag-container .drag-column {
    flex: inherit;
    width: 25%;
    margin: 0;
    background: none;
  }

  .drag-container .drag-column .drag-column-header {
    text-align: center;
  }

  .drag-item {
    height: inherit;
    padding: 0;
    margin: 0;
    background: none;
  }

  .drag-item.is-moving {
    background: none;
  }

  .waiting h2,
  .drag-column-制作中 .drag-column-header,
  .drag-column-制作中 .drag-options,
  .drag-column-制作中 .is-moved,
  .drag-column-反馈中 .drag-column-header,
  .drag-column-反馈中 .drag-options,
  .drag-column-反馈中 .is-moved,
  .drag-column-提交发布 .drag-column-header,
  .drag-column-提交发布 .drag-options,
  .drag-column-提交发布 .is-moved,
  .drag-column-等待制作 .drag-column-header,
  .drag-column-等待制作 .drag-options,
  .drag-column-等待制作 .is-moved{
    font-size: .8rem;
    background: #fff;
    color: #666;
    border-left: 2px solid #409eff;
    padding-left: 10px;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }

  .workbench_list .task_detail .task-xing .el-card__body {
    padding: 15px;
  }

  .workbench_list .text-Lens:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }

  .workbench_list .text-Lens p {
    margin: 0;
    display: inline-block;
    font-weight: 500;
    /* width:50%; */
  }

  .workbench_list .text-Lens .text-Lens-rank {
    top: 0;
    right: 0;
  }

  .workbench_list .task-Lens-assets {
    max-width: 70%;
    float: left;
  }

  .workbench_list .task-Lens-assets .el-tag {
    margin-bottom: 5px;
  }

  .workbench_list .text-Lens .text-Lens-time {
    width: 50%;
    /* position:  */
    float: right;
  }

  .workbench_list .text-wan .text-Lens-time {
    width: 100%;
  }

  .workbench_list .text-Lens-time span {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    font-weight: normal;
  }

  .workbench_list .text-Lens-time .item {
    margin-right: 0;
    padding: 12px 4px;
    margin-left: 0;
    border-radius: 0;
    border: none;
    border-right: 1px solid;
    line-height: 0;
    font-size: 14px;
  }

  .workbench_list .text-Lens-link {
    text-align: right;
  }

  .workbench_list .task_detail {
    width: 30%;
    position: fixed;
    right: 20px;
    top: 100px;
    z-index: 1;
  }

  .workbench_list .el-card__body {
    padding: 10px;
    width: 100%;
  }

  .workbench_list .el-card .el-card__body {
    padding: 0;
    width: 100%;
  }

  .workbench_list .el-card .el-card__body .text {
    padding: 10px;
  }

  .workbench_list .text-p {
    text-align: right;
    width: 50%;
  }

  .workbench_list .task-reaches p {
    margin: 0;
    display: block;
    font-weight: 500;
    /* width: 50%; */
    text-align: right;
  }

  .workbench_list .text-Lens .task-r {
    width: 100%;
  }

  .workbench_list .task-block {
    text-align: right;
  }

  .workbench_list .task_detail p {
    font-size: .8rem;
    color: #666;
  }

</style>