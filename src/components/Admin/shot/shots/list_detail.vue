<template>
  <div>
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
      <el-tabs v-if="!isList" v-model="activeName" @tab-click="handleClick" class="fl">
        <el-tab-pane label="镜头制作中" name="shotsInDevelopment">
          <div class="waiting ovf-hd">
            <el-col :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0 h-40 tx-c c-white">制作中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 3" :key="i" class="text" @click="show2 = !show2">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">A</el-tag>
                            <el-tag type="danger">S</el-tag>
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
                          <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                          <el-tag type="success">数绘: 100%</el-tag>
                          <el-tag type="success">跟踪: 100%</el-tag>
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">特效: 0%</el-tag>
                          <el-tag type="danger">灯光: 0%</el-tag>
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
                <h2 class="m-0 h-40 tx-c c-white" style="background: cadetblue">反馈中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 3" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">A</el-tag>
                            <el-tag type="danger">S</el-tag>
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
                          <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                          <el-tag type="success">数绘: 100%</el-tag>
                          <el-tag type="success">跟踪: 100%</el-tag>
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">特效: 0%</el-tag>
                          <el-tag type="danger">灯光: 0%</el-tag>
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
        <el-tab-pane label="镜头未制作" name="shotsNotDevelopment">
          <div class="waiting ovf-hd">
            <el-col :span="12">
              <div class="grid-content bg-purple">
                <h2 class="m-0 h-40 tx-c c-white">等待资产</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 3" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">A</el-tag>
                            <el-tag type="danger">S</el-tag>
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
                          <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                          <el-tag type="success">数绘: 100%</el-tag>
                          <el-tag type="success">跟踪: 100%</el-tag>
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">特效: 0%</el-tag>
                          <el-tag type="danger">灯光: 0%</el-tag>
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
                <h2 class="m-0 h-40 tx-c c-white" style="background: cadetblue">等待上游</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 3" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tag type="warning">A</el-tag>
                            <el-tag type="danger">S</el-tag>
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
                          <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                          <el-tag type="success">数绘: 100%</el-tag>
                          <el-tag type="success">跟踪: 100%</el-tag>
                          <el-tag type="warn">动画: 60%</el-tag>
                          <el-tag type="danger">特效: 0%</el-tag>
                          <el-tag type="danger">灯光: 0%</el-tag>
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
        <el-tab-pane label="镜头暂停" name="shotsSuspend">
          <el-col :span="12" v-for="i in 4" :key="i">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card">
                <div class="text">
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tag type="warning">A</el-tag>
                      <el-tag type="danger">S</el-tag>
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
                    <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                    <el-tag type="success">数绘: 100%</el-tag>
                    <el-tag type="success">跟踪: 100%</el-tag>
                    <el-tag type="warn">动画: 60%</el-tag>
                    <el-tag type="danger">特效: 0%</el-tag>
                    <el-tag type="danger">灯光: 0%</el-tag>
                    <el-tag type="danger">合成: 0%</el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
        <el-tab-pane label="镜头完成" name="shotsFinish">
          <el-col :span="12" v-for="i in 5" :key="i">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card">
                <div class="text">
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">FUY：<span>001002</span></p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tag type="warning">A</el-tag>
                      <el-tag type="danger">S</el-tag>
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
                    <p class="text-Lens-time tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务剩余天数"
                                          placement="bottom-start">
                                <span>8天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务建立时间"
                                          placement="bottom-start">
                                <span>32分</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="任务制作中时间"
                                          placement="bottom-start">
                                <span>9天</span>
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
                    <el-tag type="success">数绘: 100%</el-tag>
                    <el-tag type="success">跟踪: 100%</el-tag>
                    <el-tag type="warn">动画: 60%</el-tag>
                    <el-tag type="danger">特效: 0%</el-tag>
                    <el-tag type="danger">灯光: 0%</el-tag>
                    <el-tag type="danger">合成: 0%</el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
      </el-tabs>
      <transition name="el-zoom-in-top">
        <div v-show="show2" class="task_detail fr">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>镜头详情</span>
              <i class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editShot"></i>
              <i class="el-icon-delete m-l-5 fz-14 c-light-gray pointer"></i>
              <i class="el-icon-close fr pointer" @click="show2 = !show2"></i>
            </div>
            <div v-for="o in 4" :key="o" class="text item">
              {{'我是镜头详情' + o }}
            </div>
          </el-card>
        </div>
      </transition>
    </div>
    <editShots :data="shotDetail" ref="editShots"></editShots>
  </div>
</template>

<script>
  import editShots from '../shots/edit.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        show2: false,
        activeName: 'shotInDevelopment',
        isList: false,
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: 'S'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: 'A'
        }]
      }
    },
    methods: {
      editShot() {
        this.$refs.editShots.open()
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
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
      this.activeName = this.$route.query.type
      console.log(this.activeName)
      this.isList = this.$route.query.list
      this.init()
      console.log(store)
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
  .item {
    margin-right: 20px;
  }

  .el-card__body {
    padding: 15px;
  }

  .el-table {
    width: 95%;
  }

  .el-tabs {
    width: 95%;
  }

  .el-tag {
    padding: 0 8px;
  }

  .task_detail {
    width: 25%;
    position: fixed;
    right: 20px;
    top: 100px;
    z-index: 1;
  }

  .waiting h2 {
    font-size: .8rem;
    background: steelblue;
  }

  .text-Lens:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }

  .text-Lens p {
    margin: 0;
    display: inline-block;
    font-weight: 500;
  }

  .text-Lens .text-Lens-rank {
    top: 0;
    right: 0;
  }

  .text-Lens-assets {
    max-width: 70%;
    float: left;
  }

  .text-Lens-assets .el-tag {
    margin-bottom: 5px;
  }

  .text-Lens-time {
    width: 30%;
  }

  .text-Lens-time span {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    font-weight: normal;
  }

  .text-Lens-time .item {
    margin-right: 0;
    padding: 12px 4px;
    margin-left: 0;
    border-radius: 0;
    border: none;
    border-right: 1px solid;
    line-height: 0;
    font-size: 14px;
  }

  .text-Lens-link {
    text-align: right;
  }
</style>

