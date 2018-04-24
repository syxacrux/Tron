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
    <div class="m-b-20 pos-rel">
      <div class="pos-abs">
        <el-row :gutter="10" class="m-b-5">
          <el-col :span="5">
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="getFields">
              <el-option label="请选择项目" value=""></el-option>
              <el-option v-for="item in projectList" :label="item.project_name" :value="item.id" :key="item.id"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id" placeholder="请选择场号" @change="getShotsNum">
              <el-option label="请选择场号" value=""></el-option>
              <el-option v-for="item in fieldList" :label="item.name" :value="item.id" :key="item.id"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.shot_id" placeholder="请选择镜头号" @change="getAllShotsList">
              <el-option label="请选择镜头号" value=""></el-option>
              <el-option v-for="(item, index) in shotList" :label="item.shot_number" :value="item.id" :key="index"></el-option>
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-input placeholder="请输入场号镜头号" v-model.trim="search.shot_number">
              <el-button slot="append" icon="el-icon-search" @click="searchShot"></el-button>
            </el-input>
          </el-col>
        </el-row>
      </div>
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
      <div v-if="isList">
        <el-table ref="multipleTable" :data="tableData" tooltip-effect="dark"
                  @selection-change="handleSelectionChange" @row-click="shotDetail">
          <el-table-column type="selection" width="55"></el-table-column>
          <el-table-column prop="shot_image" label="缩略图">
            <template slot-scope="scope">
              <!--<img :src="address + 'uploads/Projects/images/20180315/0ac2a237e3803fed26471175554c180a.jpg'" alt="" class="dp-b h-60">-->
              <img :src="address + scope.row.shot_image" alt="" class="dp-b h-60">
            </template>
          </el-table-column>
          <el-table-column prop="field_number" label="场号"></el-table-column>
          <el-table-column prop="shot_number" label="镜头号"></el-table-column>
          <el-table-column prop="difficulty" label="难度"></el-table-column>
          <el-table-column prop="priority_level" label="优先级"></el-table-column>
          <el-table-column prop="tache" label="进度" width="150">
            <template slot-scope="scope">
              <el-tag v-for="value in scope.row.tache_info"
                      v-if="value.finish_degree!==''?true:false" :key="value.id"
                      :type="value.finish_degree<100?'warning':'success'">
                {{ value.tache_byname }}:{{ value.finish_degree }}%
              </el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="plan_start_time" label="计划开始"></el-table-column>
          <el-table-column prop="plan_end_time" label="计划结束"></el-table-column>
          <el-table-column prop="actual_start_timestamp" label="实际开始"></el-table-column>
          <el-table-column prop="actual_end_timestamp" label="实际结束"></el-table-column>
          <!--<el-table-column prop="make_demand" label="备注"></el-table-column>-->
        </el-table>
        <div class="pos-rel p-t-20">
          <!--<btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup>-->
          <div class="block tx-r">
            <el-pagination
                @current-change="listCurrentChange"
                layout="total, prev, pager, next"
                :page-size="limit"
                :current-page="listCurrentPage"
                :total="listDataCount">
            </el-pagination>
          </div>
        </div>
      </div>
      <el-tabs v-if="!isList" v-model="activeName" @tab-click="tabClick" class="fl">
        <el-tab-pane label="镜头制作中" name="shotsInDevelopment">
          <div class="shot_card ovf-hd">
            <el-col v-if="inDevelopmentShow" :span="12">
              <div class="grid-content">
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
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级"
                                        placement="bottom-start">
                              <el-tag type="danger">{{item.priority_level}}</el-tag>
                            </el-tooltip>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10">
                          <p class="text-Lens-assets">
                            <el-tag type="info" v-if="item.asset_info.length" v-for="(asset, index) in item.asset_info" :key="asset.id">
                              {{asset.name}}:{{asset.type}}
                            </el-tag>
                          </p>
                          <p class="text-Lens-time fr tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                                <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                                <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间"
                                          placement="bottom-start">
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
                          <el-tag class="m-l-5 m-b-5" v-for="value in item.tache_info"
                                  v-if="value.finish_degree!==''?true:false" :key="value.id"
                                  :type="value.finish_degree<100?'warning':'success'">
                            {{ value.tache_byname }}：{{ value.finish_degree }}%
                          </el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <div class="pos-rel p-t-20" v-if="inProductionList.length">
                  <div class="block tx-r">
                    <el-pagination
                        @current-change="inProductionCurrentChange"
                        :current-page.sync="currentPage"
                        :page-size="10"
                        layout="prev, pager, next, jumper"
                        :total="inProductionDataCount">
                    </el-pagination>
                  </div>
                </div>
              </div>
            </el-col>
            <el-col v-if="feedbackShow" :span="12">
              <div class="grid-content">
                <h2 class="m-0">反馈中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="item in feedbackList" :key="item.id" @click="shotDetail(item.id)">
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
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级"
                                        placement="bottom-start">
                              <el-tag type="danger">{{item.priority_level}}</el-tag>
                            </el-tooltip>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10">
                          <p class="text-Lens-assets">
                            <el-tag type="info" v-if="item.asset_info.length" v-for="(asset, index) in item.asset_info" :key="asset.id">
                              {{asset.name}}:{{asset.type}}
                            </el-tag>
                          </p>
                          <p class="text-Lens-time fr tx-r">
                            <span>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头剩余天数" placement="bottom-start">
                                <span>{{ shotRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头建立时间" placement="bottom-start">
                                <span>{{ shotCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="镜头制作中时间"
                                          placement="bottom-start">
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
                          <el-tag class="m-l-5" v-for="value in item.tache_info"
                                  v-if="value.finish_degree !== ''?true:false" :key="value.id"
                                  :type="value.finish_degree<100?'warning':'success'">
                            {{ value.tache_byname }}：{{ value.finish_degree }}%
                          </el-tag>
                        </div>
                      </div>
                    </el-card>
                  </li>
                </ul>
                <div class="pos-rel p-t-20" v-if="feedbackList.length">
                  <div class="block tx-r">
                    <el-pagination
                        @current-change="feedbackCurrentChange"
                        :current-page.sync="currentPage"
                        :page-size="10"
                        layout="prev, pager, next, jumper"
                        :total="feedbackDataCount">
                    </el-pagination>
                  </div>
                </div>
              </div>
            </el-col>
          </div>
        </el-tab-pane>
        <el-tab-pane label="镜头未制作" name="shotsNotDevelopment">
          <div class="shot_card ovf-hd">
            <div class="grid-content">
              <h2 class="m-0">等待资产</h2>
              <div class="ovf-hd">
                <el-col :span="12" v-for="item in waitingList" :key="item.id" @click="shotDetail(item.id)">
                  <div class="grid-content p-b-5">
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
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级"
                                        placement="bottom-start">
                              <el-tag type="danger">{{item.priority_level}}</el-tag>
                            </el-tooltip>
                          </p>
                        </div>
                        <div class="text-Lens m-t-10">
                          <p class="text-Lens-assets">
                            <el-tag type="info" v-if="item.asset_info.length" v-for="(asset, index) in item.asset_info" :key="asset.id">
                              {{asset.name}}:{{asset.type}}
                            </el-tag>
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
                          <el-tag class="m-l-5" v-for="value in item.tache_info"
                                  v-if="value.finish_degree !== ''?true:false" :key="value.id"
                                  :type="value.finish_degree<100?'warning':'success'">
                            {{ value.tache_byname }}：{{ value.finish_degree }}%
                          </el-tag>
                        </div>
                      </div>
                    </el-card>
                  </div>
                </el-col>
              </div>
              <div class="pos-rel p-t-20" v-if="waitingList.length">
                <div class="block tx-r">
                  <el-pagination
                      @current-change="waitingCurrentChange"
                      :current-page.sync="currentPage"
                      :page-size="10"
                      layout="prev, pager, next, jumper"
                      :total="waitingDataCount">
                  </el-pagination>
                </div>
              </div>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="suspendShow" label="镜头暂停" name="shotsSuspend">
          <div class="ovf-hd">
            <el-col :span="12" v-for="item in pauseList" :key="item.id" @click="shotDetail(item.id)">
              <div class="grid-content p-b-5">
                <el-card>
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">
                      {{item.project_name}}：<span>{{item.shot_number}}</span>
                    </p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                        <el-tag type="warning">{{item.difficulty}}</el-tag>
                      </el-tooltip>
                      <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级"
                                  placement="bottom-start">
                        <el-tag type="danger">{{item.priority_level}}</el-tag>
                      </el-tooltip>
                    </p>
                  </div>
                  <div class="text-Lens m-t-10">
                    <p class="text-Lens-assets">
                      <el-tag type="info" v-if="item.asset_info.length" v-for="(asset, index) in item.asset_info" :key="asset.id">
                        {{asset.name}}:{{asset.type}}
                      </el-tag>
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
                    <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false"
                            :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                      {{ value.tache_byname }}：{{ value.finish_degree }}%
                    </el-tag>
                  </div>
                </el-card>
              </div>
            </el-col>
          </div>
          <div class="pos-rel p-t-20" v-if="pauseList.length">
            <div class="block tx-r">
              <el-pagination
                  @current-change="pauseCurrentChange"
                  :current-page.sync="currentPage"
                  :page-size="10"
                  layout="prev, pager, next, jumper"
                  :total="pauseDataCount">
              </el-pagination>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane v-if="finishShow" label="镜头完成" name="shotsFinish">
          <div class="ovf-hd">
            <el-col :span="12" v-for="item in finishList" :key="item.id" @click="shotDetail(item.id)">
              <div class="grid-content p-b-5">
                <el-card>
                  <div class="text-Lens pos-rel">
                    <p class="text-Lens-name h-28 ">
                      {{item.project_name}}：<span>{{item.shot_number}}</span>
                    </p>
                    <p class="text-Lens-rank pos-abs">
                      <el-tooltip class="pointer" effect="dark" content="镜头难度" placement="bottom-start">
                        <el-tag type="warning">{{item.difficulty}}</el-tag>
                      </el-tooltip>
                      <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="镜头优先级"
                                  placement="bottom-start">
                        <el-tag type="danger">{{item.priority_level}}</el-tag>
                      </el-tooltip>
                    </p>
                  </div>
                  <div class="text-Lens m-t-10">
                    <p class="text-Lens-assets">
                      <el-tag type="info" v-if="item.asset_info.length" v-for="(asset, index) in item.asset_info" :key="asset.id">
                        {{asset.name}}:{{asset.type}}
                      </el-tag>
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
                    <el-tag class="m-l-5" v-for="value in item.tache_info" v-if="value.finish_degree !== ''?true:false"
                            :key="value.id" :type="value.finish_degree<100?'warning':'success'">
                      {{ value.tache_byname }}：{{ value.finish_degree }}%
                    </el-tag>
                  </div>
                </el-card>
              </div>
            </el-col>
          </div>

          <div class="pos-rel p-t-20" v-if="finishList.length">
            <div class="block tx-r">
              <el-pagination
                  @current-change="finishCurrentChange"
                  :current-page.sync="currentPage"
                  :page-size="10"
                  layout="prev, pager, next, jumper"
                  :total="finishDataCount">
              </el-pagination>
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
      <transition name="el-zoom-in-top">
        <div v-show="isShotDetailShow" class="shot_detail fr">
          <el-card>
            <div slot="header" class="clearfix">
              <span>镜头详情</span>
              <i v-if="editShow" class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editShot"></i>
              <i v-if="deleteShow" class="el-icon-delete m-l-5 fz-14 c-light-gray pointer" @click="deleteShot"></i>
              <i class="el-icon-close fr pointer" @click="isShotDetailShow = !isShotDetailShow"></i>
            </div>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头缩略图：<img :src="address + editShotDetail.shot_image" alt="" class="vtcal-mid h-40"></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0 vtcal-mid h-40">场号/集号：<span>{{ editShotDetail.field_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头简称：<span>{{ editShotDetail.shot_byname }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">所属项目：<span>{{ editShotDetail.project_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头编号：<span>{{ editShotDetail.shot_number }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">镜头名称：<span>{{ editShotDetail.shot_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">计划开始时间：<span>{{ j2time(editShotDetail.plan_start_timestamp) }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">计划结束时间：<span>{{ j2time(editShotDetail.plan_end_timestamp) }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">资产：
                  <span>
                    <el-tag size="mini" type="info" v-if="editShotDetail.asset_info.length" v-for="(asset, index) in editShotDetail.asset_info" :key="asset.id">
                              {{ asset.name }}:{{ asset.type }}
                            </el-tag>
                  </span>
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头优先级：<span>{{ editShotDetail.priority_level_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">镜头难度：<span>{{ editShotDetail.difficulty_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">时刻：<span>{{ editShotDetail.time_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">环境：<span>{{ editShotDetail.ambient_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">
                  环节所属工作室：
                  <span class="tache_studio dp-b" v-for="(item, index) in editShotDetail.tache_info" :key="index">
                    <i class="el-icon-close m-l-5 c-light-gray pointer" v-if="deleteShowTache" @click="deleteTache(index)" ></i>
                    <span>{{ index }}：</span>
                    <el-tag v-if="item.length" size="mini" v-for="studio in item" :closable="deleteShowTacheStudio" type="info" @close="deleteTacheStudio(index, studio)" :key="studio.id">
                      {{ studio.name }}
                    </el-tag>
                  </span>
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">帧长范围：<span>{{ editShotDetail.frame_range }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">手柄帧：<span>{{ editShotDetail.handle_frame }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">剪辑帧长：<span>{{ editShotDetail.clip_frame_length }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">素材帧长：<span>{{ editShotDetail.material_frame_length }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">素材号：<span>{{ editShotDetail.material_number }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">二级公司：<span>{{ editShotDetail.second_company }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">镜头备注：<span>{{ editShotDetail.shot_explain }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">变速信息：<span>{{ editShotDetail.change_speed_info }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">制作要求：<span>{{ editShotDetail.make_demand }}</span></p>
              </el-col>
            </el-row>
          </el-card>
        </div>
      </transition>
    </div>
    <editShots :message="editShotDetail" @updataShotDetail="shotDetail" ref="editShots"></editShots>
  </div>
</template>
<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import editShots from '../shots/edit.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        multipleSelection: [],
        currentPage: 1,
        listCurrentPage: 1,
        isShotDetailShow: false,    //是否显示镜头详情
        activeName: 'shotInDevelopment', //镜头tab当前选中值
        isList: false,    // 是否显示镜头列表
        address: window.baseUrl + '/',
        inProductionDataCount: 0,  //制作中总数量
        inProductionList: [],  //制作中列表
        feedbackDataCount: 0,  //反馈中总数量
        feedbackList: [],  //反馈中列表
        waitingDataCount: 0,  //等待资产总数量
        waitingList: [],  //等待资产列表
        pauseDataCount: 0,  //镜头暂停总数量
        pauseList: [],  //镜头暂停列表
        finishDataCount: 0,  //镜头完成总数量
        finishList: [],  //镜头完成列表
        listDataCount: 0,  //镜头表格列表总数量
        tableData: [], //镜头表格列表
        page: 1,
        limit: 10,
        editShotDetail: {},
        search: {
          project_id: '',
          field_id: '',
          shot_id: '',
          shot_number: ''
        },
        projectList: [],
        fieldList: [],
        shotList: []
      }
    },
    methods: {
      searchShot() {
        if(this.search.shot_number.length === 6) {
          this.search.project_id = ''
          this.search.field_id = ''
          this.search.shot_id = ''
          this.getAllShotsList()
        }
        if(!this.search.field_id && this.search.shot_number.length === 3) {
          _g.toastMsg('warning', '请选择所属项目及场号')
          return
        } else {
          this.search.shot_id = ''
          this.getAllShotsList()
        }
      },
//      点击编辑镜头执行方法
      editShot() {
        this.$refs.editShots.open()
      },
//      点击镜头显示镜头详情
      shotDetail(id) {
//        判断镜头详情传入值是否为数字
        if(!/^[0-9]*$/.test(id)){
          id = id.id
        }
        this.id = id
        this.apiGet('admin/shots/' + id).then((res) => {
          this.handelResponse(res, (data) => {
            this.editShotDetail = data
          })
        })
        if (!this.isShotDetailShow) {
          this.isShotDetailShow = !this.isShotDetailShow
        } else {
          this.getAllShotsList()
        }
      },
//      镜头详情删除环节
      deleteTache(tache_name) {
        this.$confirm('确认删除该环节?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          const data = {
            id: this.id,
            tache_name: tache_name
          }
          this.apiPost('shot/tache_del', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.shotDetail(this.id)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
//      镜头详情删除环节所属工作室
      deleteTacheStudio(tache_name, item) {
        console.log(item)
        this.$confirm('确认删除该环节所属工作室?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          const data = {
            id: this.id,
            tache_name: tache_name,
            studio_id: item.id
          }
          this.apiPost('shot/studio_del', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.shotDetail(this.id)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
//      删除镜头
      deleteShot(id) {
        this.$confirm('确认删除该镜头?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/shots/', this.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.init(this.activeName)
              this.getShotList(this.page)
              this.isShotDetailShow = false
            })
          })
        }).catch(() => {
          // catch error
        })
      },
//      制作中切换分页
      inProductionCurrentChange(page) {
        this.getShots('in_production', page)
      },
//      反馈中切换分页
      feedbackCurrentChange(page) {
        this.getShots('feedback', page)
      },
//      等待资产中切换分页
      waitingCurrentChange(page) {
        this.getShots('waiting_assets', page)
      },
//      镜头暂停切换分页
      pauseCurrentChange(page) {
        this.getShots('pause', page)
      },
//      镜头完成切换分页
      finishCurrentChange(page) {
        this.getShots('finish', page)
      },
      listCurrentChange(page) {
        this.getShotList(page)
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
//      获取所有项目
      getProjects() {
        this.apiGet('admin/projects').then((res) => {
          this.handelResponse(res, (data) => {
            this.projectList = data.list
          })
        })
      },
      /*
      * 获取某个状态的镜头看板内容
      * params: {
      *   shot_status: 区分请求接口的地址（in_production、feedback、waiting_assets、pause、finish）
      * }
      * */
      getShots(shot_status, page) {
        const data = {
          params: {
            page: page,
            limit: this.limit,
            keywords: this.search
          }
        }
        let url = `shot/${shot_status}`
        this.loading = true
        this.apiGet(url, data).then((res) => {
          this.handelResponse(res, (data) => {
            switch (shot_status) {
              case 'in_production':
                this.inProductionDataCount = data.dataCount
                this.inProductionList = data.list
                break;
              case 'feedback':
                this.feedbackDataCount = data.dataCount
                this.feedbackList = data.list
                break;
              case 'waiting_assets':
                this.waitingDataCount = data.dataCount
                this.waitingList = data.list
                break;
              case 'pause':
                this.pauseDataCount = data.dataCount
                this.pauseList = data.list
                break;
              case 'finish':
                this.finishDataCount = data.dataCount
                this.finishList = data.list
                break;
            }
          })
        })
      },
//      初始化镜头列表
      getShotList(page) {
        this.page = page
        const data = {
          params: {
            keywords: this.search,
            page: page,
            limit: this.limit
          }
        }
        this.apiGet('admin/shots', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.listDataCount = data.dataCount
          })
        })
      },
//      获取所有场号、集号
      getFields() {
        this.getAllShotsList()
        const data = {
          params: {
            project_id: this.search.project_id,
            type: 1
          }
        }
        this.apiGet('admin/get_fields', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.fieldList = data
          })
        })
      },
//      获取所有场号集号下的镜头号
      getShotsNum() {
        this.getAllShotsList()
        const data = {
          params: {
            field_id: this.search.field_id
          }
        }
        this.apiGet('admin/get_shot_num', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.shotList = data
          })
        })
      },
      getAllShotsList() {
        this.init(this.activeName)
        this.getShotList(1)
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
            this.getShots('in_production', 1)
            this.getShots('feedback', 1)
            break;
          case 'shotsNotDevelopment':
            this.getShots('waiting_assets', 1)
            break;
          case 'shotsSuspend':
            this.getShots('pause', 1)
            break;
          case 'shotsFinish':
            this.getShots('finish', 1)
            break;
          default:
            this.activeName = 'shotsInDevelopment'
            this.init('shotsInDevelopment')
            break;
        }
      }
    },
    created() {
      this.activeName = this.$route.query.type
      this.isList = this.$route.query.list
      this.getAllShotsList()
      this.getProjects()
    },
    components: {
      editShots,
      btnGroup
    },
    mixins: [http],
    computed: {
//      编辑镜头按钮
      editShow() {
        return _g.getHasRule('shots-update')
      },
//      删除镜头按钮
      deleteShow() {
        return _g.getHasRule('shots-delete')
      },
//      删除镜头所属环节按钮
      deleteShowTache() {
        return _g.getHasRule('shots-delete_tache')
      },
//      删除镜头环节所属工作室按钮
      deleteShowTacheStudio() {
        return _g.getHasRule('shots-delete_studio')
      },
//      制作中按钮
      inDevelopmentShow() {
        return _g.getHasRule('shots-in_production_data')
      },
//      反馈中按钮
      feedbackShow() {
        return _g.getHasRule('shots-feedback_data')
      },
//      镜头暂停按钮
      suspendShow() {
        return _g.getHasRule('shots-pause_data')
      },
//      镜头完成按钮
      finishShow() {
        return _g.getHasRule('shots-finish_data')
      }
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
    margin-bottom: 2px;
  }

  .shot_list_detail .shot_detail {
    width: 25%;
    height: 75%;
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    overflow: scroll;
  }
  .shot_list_detail .shot_detail p{
    font-size: .8rem;
    color: #666;
  }
  .shot_list_detail .shot_detail p span{
    color: #333;
  }
  .shot_list_detail .shot_detail p span.tache_studio{
    font-size: .75rem;
  }
  .shot_list_detail .shot_detail .el-card__header{
    padding: 10px 15px;
    font-size: 14px;
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

