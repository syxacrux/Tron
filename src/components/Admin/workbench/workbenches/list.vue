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
        <el-button type="primary" plain size="mini" @click="isList = false"><i class="el-icon-document"></i></el-button>
      </el-tooltip>
    </div>
    <div class="m-b-20 ovf-hd">
      <el-tabs v-model="activeName" @tab-click="handleClick" class="fl" v-if="isList">
        <el-tab-pane label="任务" name="task">
          <kanban-board :stages="stages" :blocks="blocks" @update-block="updateBlock">
            <el-card class="box-card point" v-for="block in blocks" :slot="block.id" :key="block.id">
              <div style="display:none">
                <strong>id:</strong> {{ block.id }}
              </div>
              <div>
                <!-- {{ block.title }} -->
                <el-card class="work-box box-card box-texts">
                  <div class="text" @click="task2 = !task2">
                    <div class="text-Lens pos-rel">
                      <p class="text-Lens-name">{{block.shot_image}}：<span>{{block.field_id + block.shot_number}}:tengsf</span></p>
                      <p class="text-Lens-rank pos-abs">
                        <el-tag type="warning">{{block.task_priority_level}}</el-tag>
                        <el-tag type="danger">{{block.difficulty}}</el-tag>
                      </p>
                    </div>
                    <div class="text-Lens m-t-10">
                      <p class="task-Lens-assets text-p">
                            <el-tag type="info" >37次</el-tag>
                      </p>
                      <p class="text-Lens-time tx-r">
                              <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                            placement="bottom-start">
                                  <span>8天</span>
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                            placement="bottom-start">
                                  <span>{{block.task_is_status_time}}分</span>
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                            placement="bottom-start">
                                  <span>{{block.task_allocated_time}}天</span>
                                </el-tooltip>
                              </span>
                        <span>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                            placement="bottom-start">
                            <span>2018/02/08 14:00</span>
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
                  <li v-for="block in blocks" :key="block.id" class="text">
                    <el-card class="box-card">
                      <!-- <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div> -->
                      <div class="text item">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">{{block.shot_image}}：<span>{{block.field_id + block.shot_number}}:tengsf</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">{{block.task_priority_level}}</el-tag>
                            <el-tag type="danger">{{block.difficulty}}</el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                                  <span>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                                placement="bottom-start">
                                      <span>8天</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                                placement="bottom-start">
                                      <span>{{block.task_is_status_time}}分</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                                placement="bottom-start">
                                      <span>{{block.task_allocated_time}}天</span>
                                    </el-tooltip>
                                  </span>
                            
                          </p>
                          <p>
                            <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                                placement="bottom-start">
                                <span>2018/02/08 14:00</span>
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
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0 h-40 tx-c c-white" style="background: yellowgreen">镜头</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="block in blocks" :key="block.id" class="text">
                    <el-card class="box-card">
                      <!-- <div v-for="o in 4" :key="o" class="text item">
                        {{'列表内容 ' + o }}
                      </div> -->
                      <div class="text item">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name">{{block.shot_image}}：<span>{{block.field_id + block.shot_number}}:tengsf</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">{{block.task_priority_level}}</el-tag>
                            <el-tag type="danger">{{block.difficulty}}</el-tag>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10 task-reaches">
                          <p class="text-Lens-time tx-r task-r">
                                  <span>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                                placement="bottom-start">
                                      <span>8天</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                                placement="bottom-start">
                                      <span>{{block.task_is_status_time}}分</span>
                                    </el-tooltip>
                                    <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                                placement="bottom-start">
                                      <span>{{block.task_allocated_time}}天</span>
                                    </el-tooltip>
                                  </span>
                          </p>
                          <p>
                            <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                                placement="bottom-start">
                                <span>2018/02/08 14:00</span>
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
              </div>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane label="完成" name="complete">
          <el-col :span="6" v-for="block in blocks" :key="block.id">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card ">
                  <div class="text">
                    <div class="text-Lens pos-rel">
                      <p class="text-Lens-name">{{block.shot_image}}：<span>{{block.field_id + block.shot_number}}:tengsf</span></p>
                      <p class="text-Lens-rank pos-abs">
                        <el-tag type="warning">{{block.task_priority_level}}</el-tag>
                        <el-tag type="danger">{{block.difficulty}}</el-tag>
                      </p>
                    </div>
                    <div class="text-Lens m-t-10">
                      <p class="text-Lens-time tx-r">
                              <span>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                            placement="bottom-start">
                                  <span>8天</span>
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                            placement="bottom-start">
                                  <span>{{block.task_is_status_time}}分</span>
                                </el-tooltip>
                                <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                            placement="bottom-start">
                                  <span>{{block.task_allocated_time}}天</span>
                                </el-tooltip>
                              </span>
                      </p>
                      <p>
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
                      <el-tag type="warn">动画: 60%</el-tag>
                      <el-tag type="danger">合成: 0%</el-tag>
                    </div>
                  </div>
              </el-card>
            </div>
          </el-col>

        </el-tab-pane>
      </el-tabs>
      <el-table v-if="!isList" :data="tableData" stripe class="fl">
        <el-table-column type="selection" width="50"></el-table-column>
        <el-table-column prop="shot_image" label="缩略图"></el-table-column>
        <el-table-column prop="field_id" label="场号"></el-table-column>
        <el-table-column prop="shot_number" label="镜头号"></el-table-column>
        <el-table-column prop="difficulty" label="难度"></el-table-column>
        <el-table-column prop="task_priority_level" label="优先级"></el-table-column>
        <el-table-column prop="finish_degree" label="进度"></el-table-column>
        <el-table-column prop="plan_start_timestamp" label="计划开始"></el-table-column>
        <el-table-column prop="plan_end_timestamp" label="计划结束"></el-table-column>
        <el-table-column prop="actually_start_timestamp" label="实际开始"></el-table-column>
        <el-table-column prop="actually_end_timestamp" label="实际结束"></el-table-column>
        <el-table-column prop="make_demand" label="备注"></el-table-column>
      </el-table>
      <transition name="el-zoom-in-top">
        <div class="task_detail fr" v-show="task2">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>任务详情</span>
              <i class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editShot"></i>
                <i class="el-icon-delete m-l-5 fz-14 c-light-gray pointer"></i>
                <i class="el-icon-close fr pointer" @click="task2 = !task2"></i>
            </div>
            <div v-for="o in 4" :key="o" class="text item">
              {{'我是任务详情' + o }}
            </div>
          </el-card>
        </div>
      </transition>
    </div>
    <!-- <editShots ref="editShots"></editShots> -->
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
        task2: false,
        activeName: 'task',
        // frequencyState:true,
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄',
          id:0,
          group_id:null,//角色ID
          user_id:null,//所属用户
          project_id:2,//所属项目ID
          field_id:3,//	场号/集号ID
          shot_id:null,//镜头ID 根据任务类型存值
          assets_id:null,//资产ID 根据任务类型存值
          tache_id:1,//环节ID
          tache_sort:null,//环节序号
          studio_id:2,//工作室ID
          task_type:'',//任务类型
          shot_image:'FUY',//镜头简称
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

        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄',
          id:0,
          group_id:null,//角色ID
          user_id:null,//所属用户
          project_id:2,//所属项目ID
          field_id:3,//	场号/集号ID
          shot_id:null,//镜头ID 根据任务类型存值
          assets_id:null,//资产ID 根据任务类型存值
          tache_id:1,//环节ID
          tache_sort:null,//环节序号
          studio_id:2,//工作室ID
          task_type:'',//任务类型
          shot_image:'FUY',//镜头简称
          make_demand:'',//制作要求
          shot_number:'001',//镜头
          task_priority_level:'A',//优先级
          difficulty:'S',//难度
          second_company:'',//二级公司(相当于其他工作室ID )
          task_is_status_time:'32',//任务在此状态时间 分
          task_allocated_time:'9',//任务分配时间 小时
          plan_start_timestamp:'',//计划开始时间
          plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
          actually_start_timestamp:'2018/02/04 14:00',//任务实际开始时间
          actually_end_timestamp:'2018/02/04 14:00',//任务实际结时时间	
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
        }],
        stages: ['制作中', '反馈中',  '等待制作', '提交发布'],
        blocks: [
          {
            id: 1,
            status: '制作中',
            title: 'Test',
            group_id:null,//角色ID
            user_id:null,//所属用户
            project_id:2,//所属项目ID
            field_id:3,//	场号/集号ID
            shot_id:null,//镜头ID 根据任务类型存值
            assets_id:null,//资产ID 根据任务类型存值
            tache_id:1,//环节ID
            tache_sort:null,//环节序号
            studio_id:2,//工作室ID
            task_type:'',//任务类型
            shot_image:'FUY',//镜头简称
            make_demand:'',//制作要求
            shot_number:'001',//镜头
            task_priority_level:'A',//优先级
            difficulty:'S',//难度
            second_company:'',//二级公司(相当于其他工作室ID )
            task_is_status_time:'32',//任务在此状态时间 分
            task_allocated_time:'9',//任务分配时间 小时
            plan_start_timestamp:'',//计划开始时间
            plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
            actually_start_timestamp:'2018/02/04 14:00',//任务实际开始时间
            actually_end_timestamp:'2018/02/04 14:00',//任务实际结时时间	
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
          },{
            id: 2,
            status: '提交发布',
            title: 'Test',
            group_id:null,//角色ID
            user_id:null,//所属用户
            project_id:2,//所属项目ID
            field_id:3,//	场号/集号ID
            shot_id:null,//镜头ID 根据任务类型存值
            assets_id:null,//资产ID 根据任务类型存值
            tache_id:1,//环节ID
            tache_sort:null,//环节序号
            studio_id:2,//工作室ID
            task_type:'',//任务类型
            shot_image:'FUY',//镜头简称
            make_demand:'',//制作要求
            shot_number:'001',//镜头
            task_priority_level:'A',//优先级
            difficulty:'S',//难度
            second_company:'',//二级公司(相当于其他工作室ID )
            task_is_status_time:'32',//任务在此状态时间 分
            task_allocated_time:'9',//任务分配时间 小时
            plan_start_timestamp:'',//计划开始时间
            plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
            actually_start_timestamp:'2018/02/04 14:00',//任务实际开始时间
            actually_end_timestamp:'2018/02/04 14:00',//任务实际结时时间	
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
          },{
            id: 3,
            status: '提交发布',
            title: 'Test',
            group_id:null,//角色ID
            user_id:null,//所属用户
            project_id:2,//所属项目ID
            field_id:3,//	场号/集号ID
            shot_id:null,//镜头ID 根据任务类型存值
            assets_id:null,//资产ID 根据任务类型存值
            tache_id:1,//环节ID
            tache_sort:null,//环节序号
            studio_id:2,//工作室ID
            task_type:'',//任务类型
            shot_image:'FUY',//镜头简称
            make_demand:'',//制作要求
            shot_number:'001',//镜头
            task_priority_level:'A',//优先级
            difficulty:'S',//难度
            second_company:'',//二级公司(相当于其他工作室ID )
            task_is_status_time:'32',//任务在此状态时间 分
            task_allocated_time:'9',//任务分配时间 小时
            plan_start_timestamp:'',//计划开始时间
            plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
            actually_start_timestamp:'2018/02/04 14:00',//任务实际开始时间
            actually_end_timestamp:'2018/02/04 14:00',//任务实际结时时间	
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
          },{
            id: 4,
            status: '等待制作',
            title: 'Test',
            group_id:null,//角色ID
            user_id:null,//所属用户
            project_id:2,//所属项目ID
            field_id:3,//	场号/集号ID
            shot_id:null,//镜头ID 根据任务类型存值
            assets_id:null,//资产ID 根据任务类型存值
            tache_id:1,//环节ID
            tache_sort:null,//环节序号
            studio_id:2,//工作室ID
            task_type:'',//任务类型
            shot_image:'FUY',//镜头简称
            make_demand:'',//制作要求
            shot_number:'001',//镜头
            task_priority_level:'A',//优先级
            difficulty:'S',//难度
            second_company:'',//二级公司(相当于其他工作室ID )
            task_is_status_time:'32',//任务在此状态时间 分
            task_allocated_time:'9',//任务分配时间 小时
            plan_start_timestamp:'',//计划开始时间
            plan_end_timestamp:'2018/02/08 14:00',//计划结束时间
            actually_start_timestamp:'2018/02/04 14:00',//任务实际开始时间
            actually_end_timestamp:'2018/02/04 14:00',//任务实际结时时间	
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
      editShot() {
        this.$refs.editShots.open()
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
    padding: 0;
    width: 100%;
  }
  .workbench_list .el-card .el-card__body{
    padding: 0;
    width: 100%;
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
</style>