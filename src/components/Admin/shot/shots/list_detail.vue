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
    <div class="m-b-10">
      <div class="tx-r">
        <el-tooltip effect="dark" content="镜头进度" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = false"><i class="el-icon-menu"></i></el-button>
        </el-tooltip>
        <el-tooltip effect="dark" content="镜头列表" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = true"><i class="el-icon-document"></i></el-button>
        </el-tooltip>
      </div>
    </div>
    <div class="m-b-20 ovf-hd">
      <el-table v-if="isList" :data="tableData" stripe class="fl">
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
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text item">
                        <!-- {{'列表内容 ' + o }} -->
                        <div class="text-Lens">
                          <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                        </div>
                        <div class="text-Lens">
                          <div class="text-Lens-assets">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                          </div>
                          <div class="text-Lens-time">
                            <div>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>8天</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>32分</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>9天</el-button>
                              </el-tooltip>
                            </div>
                            <div>2018/02/08 14:00</div>
                          </div>
                        </div>
                        <div class="text-Lens-link">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0 h-40 tx-c c-white" style="background: yellowgreen">反馈中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text item">
                        <!-- {{'列表内容 ' + o }} -->
                        <div class="text-Lens">
                          <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                        </div>
                        <div class="text-Lens">
                          <div class="text-Lens-assets">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                          </div>
                          <div class="text-Lens-time">
                            <div>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>8天</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>32分</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>9天</el-button>
                              </el-tooltip>
                            </div>
                            <div>2018/02/08 14:00</div>
                          </div>
                        </div>
                        <div class="text-Lens-link">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
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
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text item">
                        <!-- {{'列表内容 ' + o }} -->
                        <div class="text-Lens">
                          <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                        </div>
                        <div class="text-Lens">
                          <div class="text-Lens-assets">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                          </div>
                          <div class="text-Lens-time">
                            <div>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>8天</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>32分</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>9天</el-button>
                              </el-tooltip>
                            </div>
                            <div>2018/02/08 14:00</div>
                          </div>
                        </div>
                        <div class="text-Lens-link">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
              </div>
            </el-col>
            <el-col :span="12">
              <div class="grid-content bg-purple-light">
                <h2 class="m-0 h-40 tx-c c-white" style="background: yellowgreen">等待上游</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="i in 4" :key="i" class="text">
                    <el-card class="box-card">
                      <div class="text item">
                        <!-- {{'列表内容 ' + o }} -->
                        <div class="text-Lens">
                          <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                        </div>
                        <div class="text-Lens">
                          <div class="text-Lens-assets">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                          </div>
                          <div class="text-Lens-time">
                            <div>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>8天</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>32分</el-button>
                              </el-tooltip>
                              <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                                <el-button>9天</el-button>
                              </el-tooltip>
                            </div>
                            <div>2018/02/08 14:00</div>
                          </div>
                        </div>
                        <div class="text-Lens-link">
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
                            <el-tag type="info">标签:一签</el-tag>
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
          <el-col :span="12" v-for="i in 7" :key="i">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card">
                <div class="text item">
                  <!-- {{'列表内容 ' + o }} -->
                  <div class="text-Lens">
                    <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                  </div>
                  <div class="text-Lens">
                    <div class="text-Lens-assets">
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                    </div>
                    <div class="text-Lens-time">
                      <div>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>8天</el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>32分</el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>9天</el-button>
                        </el-tooltip>
                      </div>
                      <div>2018/02/08 14:00</div>
                    </div>
                  </div>
                  <div class="text-Lens-link">
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
        <el-tab-pane label="镜头完成" name="shotsFinish">
          <el-col :span="12" v-for="i in 7" :key="i">
            <div class="grid-content bg-purple p-b-5">
              <el-card class="box-card">
                <div class="text item">
                  <!-- {{'列表内容 ' + o }} -->
                  <div class="text-Lens">
                    <div class="text-Lens-name">FUY:001002:{{o}}</div><div class="text-Lens-rank"><el-tag>A</el-tag><el-tag>S</el-tag></div>
                  </div>
                  <div class="text-Lens">
                    <div class="text-Lens-assets">
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                    </div>
                    <div class="text-Lens-time">
                      <div>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>8天</el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>32分</el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="Bottom Left 提示文字" placement="bottom-start">
                          <el-button>9天</el-button>
                        </el-tooltip>
                      </div>
                      <div>2018/02/08 14:00</div>
                    </div>
                  </div>
                  <div class="text-Lens-link">
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                      <el-tag type="info">标签:一签</el-tag>
                  </div>
                </div>
              </el-card>
            </div>
          </el-col>
        </el-tab-pane>
      </el-tabs>
      <div class="task_detail fr">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>镜头详情</span>
          </div>
          <div v-for="o in 4" :key="o" class="text item">
            {{'我是镜头详情' + o }}
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
        activeName: 'shotInDevelopment',
        isList: false,
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }]
      }
    },
    methods: {
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
  .el-table{
    width: 75%;
  }
  .el-tabs{
    width: 75%;
  }

  .task_detail{
    width: 25%;
    /*margin-top: 55px;*/
  }
  .waiting h2{
    font-size: .8rem;
    background: lightpink;

  }
  .drag-container{
    margin: 0;
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
  .text-Lens:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }
  .text-Lens .text-Lens-name{
    float: left;
  }
  .text-Lens .text-Lens-rank{
    float: right;
  }
  .text-Lens-assets{
    max-width: 70%;
    float: left;
  }
  .text-Lens-time{
    float: right;
    width: 30%;
    /* border: none; */
    /* border-right: 1px solid; */

  }
  .text-Lens-time div{
    margin-left:32%;
  }
  .text-Lens-time .item{
    margin-right:0;
    padding: 12px 4px;
    margin-left: 0;
    border-radius:0;
    border: none;
    border-right: 1px solid;
    line-height: 0;
    font-size: 14px;
  }
  .text-Lens-link{
    padding-top: 10px;
    text-align: right;
  }
</style>

