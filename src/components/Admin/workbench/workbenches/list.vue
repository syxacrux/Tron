<template>
  <div class="workbench_list">
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
        <el-button type="primary" plain size="mini" @click="workList()"><i class="el-icon-document"></i></el-button>
      </el-tooltip>
    </div>
    <div class="m-b-20 ovf-hd">
      <el-tabs v-model="activeName" @tab-click="handleClick" class="fl" v-if="isList">
        <el-tab-pane label="任务" name="task">
          <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock">
            <el-card class="box-card point" v-for="block in blocks" :slot="block.id" :key="block.id">
              <div>
                <!-- <strong>id:</strong> {{ block.id }} -->
              </div>
              <div>
                <!-- {{ block.title }} -->
                <el-card class="work-box box-card box-texts">
                  <div class="text" @click="taskDetail(block.id)">
                    <div class="text-Lens pos-rel">
                      <p class="text-Lens-name">{{block.project_name}}：<span>{{block.shot_number}}:{{block.task_byname}}</span></p>
                      <p class="text-Lens-rank pos-abs">
                        <el-tag type="danger" v-if="block.difficulty != '' ">
                          <el-tooltip class="m-r-5 pointer" effect="dark" content="难度"
                                              placement="bottom-start">
                                    <span>{{block.difficulty}}</span>
                            </el-tooltip>
                          </el-tag>
                          <el-tag type="warning" v-if="block.task_priority_level != '' ">
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="优先级"
                                              placement="bottom-start">
                                    <span>{{block.task_priority_level}}</span>
                          </el-tooltip>
                        </el-tag>
                      </p>
                    </div>
                    <div class="text-Lens m-t-10">
                      <p class="task-Lens-assets text-p">
                            <el-tag type="info"  v-if="block.id > 0">37次</el-tag>
                      </p>
                      <p class="text-Lens-time tx-r">
                              <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                            placement="bottom-start">
                                  <span>{{block.surplus_days}}</span>
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务在次状态时间"
                                            placement="bottom-start">
                                  <span>{{ shotCreateTime(block.create_timestamp) }}天</span>
                                  <!-- <span>没给</span> -->
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务分配时间"
                                            placement="bottom-start">
                                  <span>{{block.task_allot_days}}</span>
                                </el-tooltip>
                              </span>
                        <span>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间"
                                            placement="bottom-start">
                            <span>{{ j2time(block.plan_end_timestamp) }}</span>
                                </el-tooltip>
                        </span>
                      </p>
                    </div>
                    <div class="text-Lens-link m-t-10">
                      <el-tag type="warn">动画: 60%</el-tag>
                      <el-tag type="danger" v-if="block.task_finish_degree.finish_degree < 100">{{block.task_finish_degree.tache_byname}}:{{block.task_finish_degree.finish_degree}}%</el-tag>
                      <el-tag type="success" v-else>{{block.task_finish_degree.tache_byname}}:{{block.task_finish_degree.finish_degree}}%</el-tag>
                    </div>
                  </div>
                </el-card>
              </div>
            </el-card>
          </kanban-board>
          <div class="block task-block">
            <el-pagination
                    @current-change="taskCurrentChange"
                    layout="prev, pager, next, jumper"
                    :page-size="limit"
                    :current-page="currentPage"
                    :total="blocksDataCount">
            </el-pagination>
          </div>
        </el-tab-pane>
        <el-tab-pane label="等待上游" name="waiting">
          <div class="waiting ovf-hd">
            <el-col :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0 h-40 tx-c c-white">资产</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="block in upstreamList" :key="block.id" class="text" @click="task2 = !task2">
                    <el-card class="box-card">
                      <!-- <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div> -->
                      <div class="text item">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">{{block.project_name}}：<span>{{block.shot_number}}:{{block.task_byname}}</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="danger" v-if="block.difficulty != '' ">
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="难度"
                                                placement="bottom-start">
                                      <span>{{block.difficulty}}</span>
                              </el-tooltip>
                            </el-tag>
                            <el-tag type="warning" v-if="block.task_priority_level != '' ">
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="优先级"
                                                placement="bottom-start">
                                      <span>{{block.task_priority_level}}</span>
                              </el-tooltip>
                            </el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                                  <span>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                                placement="bottom-start">
                                      <span>{{block.surplus_days}}</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务在此状态时间"
                                                placement="bottom-start">
                                      <span>无</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务分配时间"
                                                placement="bottom-start">
                                      <span>{{block.task_allot_days}}</span>
                                    </el-tooltip>
                                  </span>
                            
                          </p>
                          <p>
                            <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间"
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
                  <!-- <btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup> -->
                  <div class="block pages">
                    <el-pagination
                      @current-change="upstreamCurrentChange"
                      layout="prev, pager, next, jumper"
                      :page-size="20"
                      :current-page="currentPage"
                      :total="upstreamDataCount">
                    </el-pagination>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0 h-40 tx-c c-white" style="background: yellowgreen">镜头</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="block in blocks" :key="block.id" class="text" @click="task2 = !task2">
                    <el-card class="box-card">
                      <!-- <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div> -->
                      <div class="text item">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">{{block.project_name}}：<span>{{block.shot_number}}:{{block.task_byname}}</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="danger" v-if="block.difficulty != '' ">
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="难度"
                                                placement="bottom-start">
                                      <span>{{block.difficulty}}</span>
                              </el-tooltip>
                            </el-tag>
                            <el-tag type="warning" v-if="block.task_priority_level != '' ">
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="优先级"
                                                placement="bottom-start">
                                      <span>{{block.task_priority_level}}</span>
                              </el-tooltip>
                            </el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                                  <span>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                                placement="bottom-start">
                                      <span>{{block.surplus_days}}</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务在此状态时间"
                                                placement="bottom-start">
                                      <span>无</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务分配时间"
                                                placement="bottom-start">
                                      <span>{{block.task_allot_days}}</span>
                                    </el-tooltip>
                                  </span>
                          </p>
                          <p>
                            <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间"
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
                <div class="block task-block">
                  <el-pagination
                          @current-change="upstreamCurrentChange"
                          layout="prev, pager, next, jumper"
                          :page-size="20"
                          :current-page="currentPage"
                          :total="upstreamDataCount">
                  </el-pagination>
                </div>
              </div>
            </el-col>
          </div>
          
        </el-tab-pane>
        <el-tab-pane label="完成" name="complete">
          <div class="waiting ovf-hd">
            <el-col :span="6" v-for="block in completeList" :key="block.id">
              <div class="grid-content bg-purple p-b-5" @click="taskDetail(block.id)">
                <el-card class="box-card ">
                    <div class="text">
                      <div class="text-Lens pos-rel">
                        <p class="text-Lens-name">{{block.project_name}}：<span>{{block.shot_number}}:{{block.task_byname}}</span></p>
                        <p class="text-Lens-rank pos-abs">
                          <el-tag type="danger" v-if="block.difficulty != '' ">
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="难度"
                                              placement="bottom-start">
                                    <span>{{block.difficulty}}</span>
                            </el-tooltip>
                          </el-tag>
                          <el-tag type="warning" v-if="block.task_priority_level != '' ">
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="优先级"
                                              placement="bottom-start">
                                    <span>{{block.task_priority_level}}</span>
                            </el-tooltip>
                          </el-tag>
                        </p>
                      </div>
                      <div class="text-Lens text-wan  m-t-10">
                        <p class="text-Lens-time tx-r">
                                <span>
                                  <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                              placement="bottom-start">
                                    <span>{{block.surplus_days}}</span>
                                  </el-tooltip>
                                  <el-tooltip class="m-r-5 pointer" effect="dark" content="任务再次状态时间"
                                              placement="bottom-start">
                                    <span>无</span>
                                  </el-tooltip>
                                  <el-tooltip class="m-r-5 pointer" effect="dark" content="任务分配时间"
                                              placement="bottom-start">
                                    <span>{{block.task_allot_days}}</span>
                                  </el-tooltip>
                                </span>
                        </p>
                        <p class="text-Lens-time tx-r">
                          <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="实际开始时间"
                                              placement="bottom-start">
                              <span>{{block.actually_start_timestamp}}</span>
                                  </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="实际结束时间"
                                              placement="bottom-start">
                              <span>{{block.actually_end_timestamp}}</span>
                                  </el-tooltip>
                          </span>
                        </p>
                      </div>
                      <div class="text-Lens-link m-t-10">
                        <el-tag type="warn"></el-tag>

                        <el-tag type="danger" v-if="block.task_finish_degree.finish_degree < 100">{{block.task_finish_degree.tache_byname}}:{{block.task_finish_degree.finish_degree}}%</el-tag>
                        <el-tag type="success" v-else>{{block.task_finish_degree.tache_byname}}:{{block.task_finish_degree.finish_degree}}%</el-tag>
                      </div>
                    </div>
                </el-card>
              </div>
            </el-col>
          </div>
          <div class="block task-block">
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
      <el-table v-if="!isList" :data="tableList" stripe class="fl">
        <el-table-column type="selection" width="50"></el-table-column>
        <el-table-column prop="project_name" label="项目"></el-table-column>
        <el-table-column prop="field_number" label="场号"></el-table-column>
        <el-table-column prop="shot_number" label="镜头号"></el-table-column>
        <el-table-column prop="difficulty" label="难度"></el-table-column>
        <el-table-column prop="task_priority_level" label="优先级"></el-table-column>
        <el-table-column prop="status_cn" label="进度"></el-table-column>
        <el-table-column prop="plan_start_time" label="计划开始"></el-table-column>
        <el-table-column prop="plan_end_time" label="计划结束"></el-table-column>
        <el-table-column prop="actually_start_timestamp" label="实际开始"></el-table-column>
        <el-table-column prop="actually_end_timestamp" label="实际结束"></el-table-column>
        <el-table-column prop="make_demand" label="制作人"></el-table-column>
      </el-table>
      <transition name="el-zoom-in-top">
        <div class="task_detail fr" v-show="task2">
          <el-card class="box-card task-xing">
            <div slot="header" class="clearfix">
              <span>任务详情</span>
              <i class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editWorkbench"></i>
                <i class="el-icon-delete m-l-5 fz-14 c-light-gray pointer" @click="deleteTask(finishList.id)"></i>
                <i class="el-icon-close fr pointer" @click="task2 = !task2"></i>
            </div>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">任务缩略图：<img :src="finishList.task_image" alt="" class="vtcal-mid h-40"></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">任务简称：<span>无</span></p>
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
                <p class="m-0">任务计划开始时间：<span>无</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
               <el-col :span="24">
                <p class="m-0">任务计划结束时间：<span>无</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">
                  环节制作人：
                  <span class="" v-for="(item, index) in finishList.user_data" :key="index">
                    {{item.realname}}
                  </span>
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">任务制作要求：<span>无</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头缩略图：<img :src="finishList.shot_image" alt="" class="vtcal-mid h-40"></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头简称：<span>{{ finishList.shot_byname }}</span></p>
              </el-col>
               <el-col :span="12">
                <p class="m-0">所属项目：<span>{{ finishList.project_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头编号：<span>{{ finishList.shot_number }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">镜头名称：<span>{{ finishList.shot_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">镜头计划开始时间：<span>{{ finishList.plan_start_time }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
               <el-col :span="24">
                <p class="m-0">镜头计划结束时间：<span>{{ finishList.plan_end_time }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产：<span>无</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">场号/集号：<span>{{ finishList.field_number }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头优先级：<span>{{ finishList.task_priority_level_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">镜头难度：<span>{{ finishList.difficulty_name }}</span></p>
              </el-col>
            </el-row>
            
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">时刻：<span>{{ finishList.time_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">环境：<span>{{ finishList.ambient_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">帧长范围：<span>{{ finishList.frame_range }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">手柄帧：<span>{{ finishList.handle_frame }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">剪辑帧长：<span>{{ finishList.clip_frame_length }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">素材帧长：<span>{{ finishList.material_frame_length }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">素材号：<span>{{ finishList.material_number }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">二级公司：<span>{{ finishList.second_company }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头备注：<span>{{ finishList.shot_explain }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">变速信息：<span>{{ finishList.charge_speed_info }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">制作要求：<span>{{ finishList.make_demand }}</span></p>
              </el-col>
            </el-row>
          </el-card>
        </div>
      </transition>
    </div>
    <editWorkbenches ref="editWorkbenches" :message="finishList"></editWorkbenches>
    <div v-if="!isList" class="pos-rel p-t-20">
      <div class="block pages">
        <el-pagination
                @current-change="taskCurrentChange"
                layout="prev, pager, next, jumper"
                :page-size="limit"
                :current-page="currentPage"
                :total="tableListDataCount">
        </el-pagination>
      </div>
		</div>
  </div>
</template>

<script>
  import editWorkbenches from '../workbenches/edit.vue'
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isList:true,
        task2: false,
        limit: 40,
        currentPage: 1,
        activeName: 'task',
        address: window.baseUrl + '/',
        // frequencyState:true,
        tableData: [{
          id:0,
            group_id:null,//角色ID
            user_id:null,//所属用户
            project_id:2,//所属项目ID
            project_name:'',//项目简称
            field_id:3,//	场号/集号ID
            shot_id:null,//镜头ID 根据任务类型存值
            assets_id:null,//资产ID 根据任务类型存值
            tache_id:1,//环节ID
            tache_sort:null,//环节序号
            studio_id:2,//工作室ID
            task_type:'',//任务类型
            shot_image:'FUY',//镜头缩略图
            task_byname:'FUY',//任务简称
            make_demand:'',//制作要求
            shot_number:'001',//镜头
            task_priority_level:'A',//优先级
            difficulty:'S',//难度
            second_company:'',//二级公司(相当于其他工作室ID )
            task_is_status_time:'32',//任务在此状态时间 分
            task_allocated_time:'9',//任务分配时间 小时
            plan_start_timestamp:'',//计划开始时间
            plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
            actually_start_timestamp:'',//任务实际开始时间
            actually_end_timestamp:'',//任务实际结时时间	
            finish_degree:'',//完成度
            task_status:1,//任务状态 1等待制作 5制作中 10等待审核 15 镜头反馈中 16 资产反馈中 20内部审核通过 25完成 30客户通过
            is_pause:1,//是否暂停 1 非暂停 2暂停
            camera_model:'',//相机型号
            camera_catch:'',//相机捕捉	
            camera_motion:1,//相机运动 1匀速
            camera_height:null,//相机高度
            camera_focus:'',//相机焦距
            focus_distance:'',//对焦距离
            depth_of_field:'',//景深
            pid:1,//所属任务主键
            create_time:'',//创建时间
            update_time:0,//改变状态时更新时间

        }],
        stages: ['制作中', '反馈中',  '等待制作', '提交发布'],
        blocksDataCount:0,//任务的数量
        blocks: [],//任务页面列表
        upstreamDataCount:0,//等待上游的数量
        upstreamList:[],//等待上游列表
        completeDataCount:0,//完成数量
        completeList:[],//完成列表
        tableList: [],  //任务列表
        tableListDataCount:0,//任务数量
        // limit: 10,
        finishList: {},//任务详情
      }
    },
    methods: {
      editWorkbench() {
        this.$refs.editWorkbenches.open()

      },
      //      删除任务
      deleteTask(id) {
        this.$confirm('确认删除该镜头?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/workbenches/',id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.init(this.activeName)
              this.task2 = false
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      updateBlock(id, status) {
        // console.log(arguments)
        // if()
        // console.log()
        const data = {
           'id':Number(id),
           'status':status

        }
        this.blocks.find(b => b.id === Number(id)).status = status;
        this.apiPost('task/change_status',data).then((res) => {
          this.handelResponse(res, (data) => {

          }, () => {
            this.isLoading = !this.isLoading
            getAllWorkbenches('admin/workbenches',this.currentPage,1)
          })
        })

      },
      handleClick(tab, event) {
        console.log(tab, event);
        console.log(tab)
        switch (tab.label){
          case '任务':
            this.getAllWorkbenches('admin/workbenches',1,parseInt(tab.index)+1)
          break;
          case '完成':
            this.getAllWorkbenches('task/finish_task',1,parseInt(tab.index)+1)
          break;
        }
      },
      //工作台列表
      workList(){
        // @click="isList = false"
        this.isList = false
        this.getAllWorkbenches('task/index_list',1,11)
      },
      //      时间抽转换为时间格式
      j2time(time) {
        var time = new Date(time * 1000);
        let year = time.getFullYear()
        let month = time.getMonth() + 1 < 10 ? '0' + (time.getMonth() + 1) : time.getMonth() + 1
        let date = time.getDate() < 10 ? '0' + time.getDate() : time.getDate()
        let hour = time.getHours() < 10 ? '0' + time.getHours() : time.getHours()
        let min = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes()
        let seconds = time.getSeconds() < 10 ? '0' + time.getSeconds() : time.getSeconds()
        return year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + seconds
      },
//      任务剩余天数 = 预计结束时间戳 - 当前时间戳
      shotRemainDay(end_time) {
        return Math.ceil((end_time - new Date() / 1000) / 86400)
      },
//      任务建立时间 = 当前时间戳 - 任务创建时间戳
      shotCreateTime(create_time) {
        return Math.ceil((new Date() / 1000 - create_time) / 86400)
      },
       // 任务切换页码
      taskCurrentChange(page) {
        this.getAllWorkbenches(page)
      },
      //等待上游切换页码
      upstreamCurrentChange(page){
        this.getAllWorkbenches(page)
      },
      //完成切换页码
      completeCurrentChange(page){
        this.getAllWorkbenches(page)
      },
//      获取项目列表
      getAllWorkbenches(state,page,status) {

        this.loading = true
        const data = {
          params: {
            keywords: {
              // list_type: status,
            },
            page: page,
            limit: this.limit
          }
        }
        this.apiGet(state,data).then((res) => {
          this.handelResponse(res, (data) => {
            switch (status){
              case 1:
                this.blocks=data.list
                _(this.blocks).forEach((res1,res2) => {
                  this.blocks[res2].status = res1.task_status == 1 ? '等待制作' : (res1.task_status == 5 ? '制作中' : (res1.task_status == 15 ? '反馈中' : '提交发布'))
                })
                console.log(this.blocks)
                this.blocksDataCount=data.dataCount
                break;
              case 11:
                this.tableList=data.list
                console.log(this.blocks)
                this.tableListDataCount=data.dataCount
                break;
            }
            
          })
        })
      },
 //      点击任务显示任务详情
      taskDetail(id) {
        console.log(id)
        this.apiGet('admin/workbenches/' + id).then((res) => {
          this.handelResponse(res, (data) => {
            this.finishList = data
            console.log(this.finishList)
          })
        })
        if (this.task2) {

        } else {
          this.task2 = !this.task2
          //  @click="task2 = !task2"
        }
      },
//      初始化项目列表内容
      init() {
       this.getAllWorkbenches('admin/workbenches',1,1)
      }
    },
    created() {
      this.init()
    },
    components: {
      editWorkbenches
    },
    mixins: [http],
    computed: {
//      addShow() {
//        return _g.getHasRule('workbenches-save')
//      },
//      editShow() {
//        return _g.getHasRule('workbenches-update')
//      },
//      deleteShow() {
//        return _g.getHasRule('workbenches-delete')
//      }
    }
  }
</script>
<style>
  .item {
    margin-right: 20px;
  }
  .el-tabs{
    width: 95%;
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
  .drag-column-提交发布 .drag-column-header, .drag-column-提交发布 .drag-options, .drag-column-提交发布 .is-moved {
    background: #2a92bf;
  }
  .drag-column-等待制作 .drag-column-header, .drag-column-等待制作 .drag-options, .drag-column-等待制作 .is-moved {
    background: #00b961;
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
    float:right;
  }
  .workbench_list .text-wan .text-Lens-time{
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
    width: 25%;
    position: fixed;
    right: 20px;
    top: 100px;
    z-index: 1;
  }
  .workbench_list .el-card__body{
    padding: 10px;
    width: 100%;
  }
  .workbench_list .el-card .el-card__body{
    padding: 0;
    width: 100%;
  }
  .workbench_list .el-card .el-card__body .text{
    padding: 10px;
  }
  .workbench_list .text-p{
    text-align: right;
    width:50%;
  }
  .workbench_list .task-reaches p{
    margin: 0;
    display: block;
    font-weight: 500; 
    /* width: 50%; */
    text-align: right;
  }
  .workbench_list .text-Lens .task-r{
    width: 100%;
  }
  .workbench_list .task-block{
    text-align: right;
  }
  .workbench_list .task_detail p{
    font-size: .8rem;
    color: #666;
  }
  
</style>