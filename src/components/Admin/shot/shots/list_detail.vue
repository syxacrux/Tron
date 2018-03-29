<template>
  <div class="shot_list_detail">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/shots/list' }">镜头管理</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/shots/list' }">镜头列表</el-breadcrumb-item>
        <el-breadcrumb-item>镜头详情</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20">
      <div class="tx-r">
        <el-tooltip effect="dark" content="镜头进度" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = false"><i class="el-icon-menu"></i></el-button>
        </el-tooltip>
        <el-tooltip effect="dark" content="镜头列表" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = true"><i class="el-icon-document"></i>
          </el-button>
        </el-tooltip>
      </div>
    </div>
    <div class="m-b-20 ovf-hd">
      <el-table v-if="isList" ref="multipleTable" :data="tableData" tooltip-effect="dark"
                @selection-change="handleSelectionChange">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column prop="shot_image" label="缩略图">
          <template slot-scope="scope">
            <img :src="address + scope.row.shot_image" alt="" style="width: 50px;height: 50px">
          </template>
        </el-table-column>
        <el-table-column prop="field_name" label="场号"></el-table-column>
        <el-table-column prop="shot_number" label="镜头号"></el-table-column>
        <el-table-column prop="difficulty" label="难度"></el-table-column>
        <el-table-column prop="priority_level" label="优先级"></el-table-column>
        <el-table-column prop="tache" label="进度" width="200">
          <template slot-scope="scopes">
            <!--这里插入子列表就可以了,子列表的数据来自scope-->
            <!--<el-tag v-for="(taches, index) in scopes.row.tache" :key="item.id" type="success">{{taches.name}}</el-tag>-->
            <el-tag type="success">标签二</el-tag>
            <el-tag type="info">标签三</el-tag>
            <el-tag type="warning">标签四</el-tag>
            <el-tag type="danger">标签五</el-tag>
          </template>

        </el-table-column>
        <el-table-column prop="plan_start_timestamp" label="计划开始"></el-table-column>
        <el-table-column prop="plan_end_timestamp" label="计划结束"></el-table-column>
        <el-table-column prop="actual_start_timestamp" label="实际开始"></el-table-column>
        <el-table-column prop="actual_end_timestamp" label="实际结束"></el-table-column>
        <el-table-column prop="make_demand" label="备注"></el-table-column>
      </el-table>
      <el-tabs v-if="!isList" v-model="activeName" @tab-click="tabClick" class="fl">
        <el-tab-pane label="镜头制作中" name="shotsInDevelopment">
          <div class="shot_card ovf-hd">
            <el-col :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0">制作中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="item in inProductionList" :key="item.id" @click="shotDetail(item.id)">
                    <el-card>
                      <div>
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">
                            {{item.project_name}}：<span>{{item.shot_number}}</span>
                          </p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                              <el-tag type="warning">{{item.difficulty}}</el-tag>
                            </el-tooltip>
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级" placement="bottom-start">
                              <el-tag type="danger">{{item.priority_level}}</el-tag>
                            </el-tooltip>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10">
                          <p class="text-Lens-assets">
                            <!--<el-tag type="info" v-for="(props, index) in item.prop" :key="item.id">{{props.name}}:{{props.lmane}}</el-tag>-->
                            <el-tag type="info">元宝:道具</el-tag>
                            <el-tag type="info">混天绫:道具</el-tag>
                            <el-tag type="info">元宝:道具</el-tag>
                            <el-tag type="info">混天绫:道具</el-tag>
                          </p>
                          <p class="text-Lens-time fr tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                                <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                                <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间" placement="bottom-start">
                                <span>{{ Math.ceil(item.surplus_days) }}天</span>
                              </el-tooltip>
                            </span>
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                                <span>{{ j2time(item.plan_end_timestamp) }}</span>
                              </el-tooltip>
                            </span>
                          </p>
                        </div>
                        <div class="text-Lens-link m-t-10">
                          <el-tag class="m-l-5 m-b-5" v-for="value in item.tache_info" v-if="value.finish_degree!==''?true:false" :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                            {{ value.tache_byname }}：{{ value.finish_degree }}%
                          </el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <el-pagination
                    small
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage3"
                    :page-size="1"
                    layout="prev, pager, next, jumper"
                    :total="50">
                </el-pagination>
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0">反馈中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="item in feedbackList" :key="item.id" @click="show2 = !show2">
                    <el-card class="">
                      <div class="">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">
                            {{item.project_name}}：<span>{{item.shot_number}}</span>
                          </p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                              <el-tag type="warning">{{item.difficulty}}</el-tag>
                            </el-tooltip>
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级" placement="bottom-start">
                              <el-tag type="danger">{{item.priority_level}}</el-tag>
                            </el-tooltip>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10">
                          <p class="text-Lens-assets">
                            <el-tag type="info">混天绫:道具</el-tag>
                            <el-tag type="info">元宝:道具</el-tag>
                            <el-tag type="info">混天绫:道具</el-tag>
                            <el-tag type="info">元宝:道具</el-tag>
                            <el-tag type="info">混天绫:道具</el-tag>
                          </p>
                          <p class="text-Lens-time fr tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                                <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                                <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间" placement="bottom-start">
                                <span>0天</span>
                              </el-tooltip>
                            </span>
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                                <span>{{ j2time(item.plan_end_timestamp) }}</span>
                              </el-tooltip>
                            </span>
                          </p>
                        </div>
                        <div class="text-Lens-link m-t-10">
                          <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false" :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                            {{ value.tache_byname }}：{{ value.finish_degree }}%
                          </el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <el-pagination
                    small
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage3"
                    :page-size="1"
                    layout="prev, pager, next, jumper"
                    :total="50">
                </el-pagination>
              </div>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane label="镜头未制作" name="shotsNotDevelopment">
          <div class="shot_card ovf-hd">
            <div class="grid-content bg-purple">
              <h2 class="m-0">等待资产</h2>
              <el-col :span="12" v-for="item in waitingList" :key="item.id">
                <div class="grid-content bg-purple p-b-5">
                  <el-card class="">
                    <div class="">
                      <div class="text-Lens pos-rel">
                        <p class="text-Lens-name h-28 ">
                          {{item.project_name}}：<span>{{item.shot_number}}</span>
                        </p>
                        <p class="text-Lens-rank pos-abs">
                          <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                            <el-tag type="warning">{{item.difficulty}}</el-tag>
                          </el-tooltip>
                          <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级" placement="bottom-start">
                            <el-tag type="danger">{{item.priority_level}}</el-tag>
                          </el-tooltip>
                        </p>
                      </div>
                      <div class="text-Lens m-t-10">
                        <p class="text-Lens-assets">
                          <el-tag type="info">混天绫:道具</el-tag>
                          <el-tag type="info">元宝:道具</el-tag>
                          <el-tag type="info">混天绫:道具</el-tag>
                          <el-tag type="info">元宝:道具</el-tag>
                          <el-tag type="info">混天绫:道具</el-tag>
                        </p>
                        <p class="text-Lens-time fr tx-r">
                          <span>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                              <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                            </el-tooltip>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                              <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                            </el-tooltip>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间" placement="bottom-start">
                              <span>0天</span>
                            </el-tooltip>
                          </span>
                          <span>
                            <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                              <span>{{ j2time(item.plan_end_timestamp) }}</span>
                            </el-tooltip>
                          </span>
                        </p>
                      </div>
                      <div class="text-Lens-link m-t-10">
                        <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false" :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                          {{ value.tache_byname }}：{{ value.finish_degree }}%
                        </el-tag>
                      </div>
                    </div>
                  </el-card>
                </div>
              </el-col>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="镜头暂停" name="shotsSuspend">
          <el-col :span="12" v-for="item in pauseList" :key="item.id">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="">
                <div class="">
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">
                      {{item.project_name}}：<span>{{item.shot_number}}</span>
                    </p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                        <el-tag type="warning">{{item.difficulty}}</el-tag>
                      </el-tooltip>
                      <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级" placement="bottom-start">
                        <el-tag type="danger">{{item.priority_level}}</el-tag>
                      </el-tooltip>
                    </p>
                  </div>
                  <div class="text-Lens m-t-10">
                    <p class="text-Lens-assets">
                      <el-tag type="info">混天绫:道具</el-tag>
                      <el-tag type="info">元宝:道具</el-tag>
                      <el-tag type="info">混天绫:道具</el-tag>
                      <el-tag type="info">元宝:道具</el-tag>
                      <el-tag type="info">混天绫:道具</el-tag>
                    </p>
                    <p class="text-Lens-time fr tx-r">
                      <span>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                          <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                          <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间" placement="bottom-start">
                          <span>0天</span>
                        </el-tooltip>
                      </span>
                      <span>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                          <span>{{ j2time(item.plan_end_timestamp) }}</span>
                        </el-tooltip>
                      </span>
                    </p>
                  </div>
                  <div class="text-Lens-link m-t-10">
                    <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false" :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                      {{ value.tache_byname }}：{{ value.finish_degree }}%
                    </el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
        <el-tab-pane label="镜头完成" name="shotsFinish">
          <el-col :span="12" v-for="item in finishList" :key="item.id">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="">
                <div class="">
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">
                      {{item.project_name}}：<span>{{item.shot_number}}</span>
                    </p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                        <el-tag type="warning">{{item.difficulty}}</el-tag>
                      </el-tooltip>
                      <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级" placement="bottom-start">
                        <el-tag type="danger">{{item.priority_level}}</el-tag>
                      </el-tooltip>
                    </p>
                  </div>
                  <div class="text-Lens m-t-10">
                    <p class="text-Lens-assets">
                      <el-tag type="info">混天绫:道具</el-tag>
                      <el-tag type="info">元宝:道具</el-tag>
                      <el-tag type="info">混天绫:道具</el-tag>
                      <el-tag type="info">元宝:道具</el-tag>
                      <el-tag type="info">混天绫:道具</el-tag>
                    </p>
                    <p class="text-Lens-time fr tx-r">
                      <span>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                          <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                          <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间" placement="bottom-start">
                          <span>0天</span>
                        </el-tooltip>
                      </span>
                      <span>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                          <span>{{ j2time(item.plan_end_timestamp) }}</span>
                        </el-tooltip>
                      </span>
                    </p>
                  </div>
                  <div class="text-Lens-link m-t-10">
                    <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false" :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                      {{ value.tache_byname }}：{{ value.finish_degree }}%
                    </el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
      </el-tabs>
      <transition name="el-zoom-in-top">
        <div v-show="isShotDetailShow" class="shot_detail fr">
          <el-card class="">
            <div slot="header" class="clearfix">
              <span>镜头详情</span>
              <i class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editShot"></i>
              <i class="el-icon-delete m-l-5 fz-14 c-light-gray pointer"></i>
              <i class="el-icon-close fr pointer" @click="isShotDetailShow = !isShotDetailShow"></i>
            </div>
            <div v-for="o in 4" :key="o" class="item">
              {{'我是镜头详情' + o }}
            </div>
          </el-card>
        </div>
      </transition>
    </div>
    <editShots ref="editShots"></editShots>
  </div>
</template>

<script>
  import editShots from '../shots/edit.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        currentPage3: 5,
        isShotDetailShow: false,    //是否显示镜头详情
        activeName: 'shotInDevelopment', //镜头tab当前选中值
        isList: false,    // 是否显示镜头列表
        address: window.baseUrl + '/',
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: 'S',
          shot_image: 'uploads/Projects/images/20180315/0fb7566809a188ba7e330564d7a9c644.jpg',
          field_name: '',
          shot_number: '',
          difficulty: '',
          priority_level: '',
          plan_start_timestamp: '',
          plan_end_timestamp: '',
          make_demand: '',
          tache: [{name: 'ANI', lmane: '100%'}, {name: 'MMV', lmane: '100%'}],
          actual_start_timestamp: '1',
          actual_end_timestamp: '2'
        }],
        inProductionList: [],  //制作中列表
        feedbackList: [],  //反馈中列表
        waitingList: [],  //等待资产列表
        pauseList: [],  //镜头暂停列表
        finishList: []  //镜头完成列表
      }
    },
    methods: {
//      点击编辑镜头执行方法
      editShot() {
        this.$refs.editShots.open()
      },
//      点击镜头显示镜头详情
      shotDetail(id) {
        console.log(id)
        if(this.isShotDetailShow) {

        }else {
          this.isShotDetailShow = !this.isShotDetailShow
        }
      },
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
      },
      handleCurrentChange(val) {
        console.log(`当前页: ${val}`);
      },
/*
* 镜头列表批量点击checkbox
* params: {
*   val: 当前已选中的checkbox群的value
* }
* */
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
/*
* 切换镜头tab方法
*   params: {
*     tab: 传入当前点击tab信息
*   }
* */
      tabClick(tab, event) {
        this.init(tab.name)
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
//      镜头剩余天数 = 预计结束时间戳 - 当前时间戳
      shotRemainDay(end_time) {
        return Math.ceil((end_time - new Date() / 1000) / 86400)
      },
//      镜头建立时间 = 当前时间戳 - 镜头创建时间戳
      shotCreateTime(create_time) {
        return Math.ceil((new Date() / 1000 - create_time) / 86400)
      },
/*
* 获取某个状态的镜头看板内容
* params: {
*   shot_status: 区分请求接口的地址（in_production、feedback、waiting、pause、finish）
* }
* */
      getShots(shot_status) {
        let url = `shot/${shot_status}`
        this.loading = true
        this.apiGet(url).then((res) => {
          this.handelResponse(res, (data) => {
            switch (shot_status) {
              case 'in_production':
                this.inProductionList = data.list
                break;
              case 'feedback':
                this.feedbackList = data.list
                break;
              case 'waiting':
                this.waitingList = data.list
                break;
              case 'pause':
                this.pauseList = data.list
                break;
              case 'finish':
                this.finishList = data.list
                break;
            }
          })
        })
      },
/*
* 初始化镜头看板内容
* params: {
*   tab_name : 当点击切换镜头tab时的传入值
* }
* */
      init(tab_name) {
        switch (tab_name) {
          case 'shotsInDevelopment':
            this.getShots('in_production')
            this.getShots('feedback')
            break;
          case 'shotsNotDevelopment':
            this.getShots('waiting')
            break;
          case 'shotsSuspend':
            this.getShots('pause')
            break;
          case 'shotsFinish':
            this.getShots('finish')
            break;
        }
      }
    },
    created() {
      this.activeName = this.$route.query.type
      this.isList = this.$route.query.list
      this.init(this.activeName)
    },
    components: {
      editShots
    },
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
  .shot_list_detail .item {
    margin-right: 20px;
  }

  .shot_list_detail .el-card__body {
    padding: 15px;
  }

  .shot_list_detail .el-table {
    width: 95%;
  }

  .shot_list_detail .el-tabs {
    width: 95%;
  }

  .shot_list_detail .el-tag {
    padding: 0 8px;
  }

  .shot_list_detail .shot_detail {
    width: 25%;
    position: fixed;
    right: 20px;
    top: 100px;
    z-index: 1;
  }

  .shot_list_detail .shot_card h2 {
    font-size: .8rem;
    height: 35px;
    line-height: 35px;
    background: #fff;
    color: #666;
    border-left: 2px solid #409eff;
    padding-left: 10px;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }

  .shot_list_detail .text-Lens:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }

  .shot_list_detail .text-Lens p {
    margin: 0;
    display: inline-block;
    font-weight: 500;
  }

  .shot_list_detail .text-Lens .text-Lens-rank {
    top: 0;
    right: 0;
  }

  .shot_list_detail .text-Lens-assets {
    max-width: 70%;
    float: left;
  }

  .shot_list_detail .text-Lens-assets .el-tag {
    margin-bottom: 5px;
  }

  .shot_list_detail .text-Lens-time {
    width: 30%;
  }

  .shot_list_detail .text-Lens-time span {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    font-weight: normal;
  }

  .shot_list_detail .text-Lens-time .item {
    margin-right: 0;
    padding: 12px 4px;
    margin-left: 0;
    border-radius: 0;
    border: none;
    border-right: 1px solid;
    line-height: 0;
    font-size: 14px;
  }

  .shot_list_detail .text-Lens-link {
    text-align: right;
  }
</style>

