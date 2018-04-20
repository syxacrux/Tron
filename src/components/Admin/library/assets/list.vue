<template>
  <div class="asset_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/assets/list' }">库管理</el-breadcrumb-item>
        <el-breadcrumb-item>资产库管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <router-link class="btn-link-large add-btn" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加资产
      </router-link>
    </div>
    <div class="m-b-20 pos-rel">
      <div class="pos-abs">
        <el-row :gutter="10" class="m-b-5">
          <el-col :span="5">
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="getAssetType">
              <el-option label="请选择项目" value=""></el-option>
              <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                         :key="item.id"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id" placeholder="请选择资产类型" @change="getAllAssetsList">
              <el-option label="请选择资产类型" value=""></el-option>
              <el-option v-for="item in fieldList" :label="item.explain" :value="item.id" :key="item.id"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.priority_level" placeholder="请选择资产优先级" @change="getAllAssetsList">
              <el-option label="请选择资产优先级" value=""></el-option>
              <el-option label="D" value="1"></el-option>
              <el-option label="C" value="2"></el-option>
              <el-option label="B" value="3"></el-option>
              <el-option label="A" value="4"></el-option>
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-input placeholder="请输入资产名称或简称" v-model.trim="search.asset_content">
              <el-button slot="append" icon="el-icon-search" @click="searchAsset"></el-button>
            </el-input>
          </el-col>
        </el-row>
      </div>
      <div class="tx-r">
        <el-tooltip effect="dark" content="资产进度" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = false"><i class="el-icon-menu"></i></el-button>
        </el-tooltip>
        <el-tooltip effect="dark" content="资产列表" placement="bottom-start">
          <el-button type="primary" plain size="mini" @click="isList = true"><i class="el-icon-document"></i>
          </el-button>
        </el-tooltip>
      </div>
    </div>
    <div class="m-b-20 ovf-hd">
      <div v-if="isList">
        <el-table ref="multipleTable" :data="tableData" tooltip-effect="dark"
                  @selection-change="handleSelectionChange" @row-click="assetDetail">
          <el-table-column type="selection" width="55"></el-table-column>
          <el-table-column label="缩略图">
            <template slot-scope="scope">
              <img :src="address + scope.row.asset_image" alt="" class="dp-b h-60">
            </template>
          </el-table-column>
          <el-table-column prop="project_name" label="所属项目"></el-table-column>
          <el-table-column prop="asset_name" label="资产名称"></el-table-column>
          <el-table-column prop="priority_level" label="优先级"></el-table-column>
          <el-table-column prop="difficulty" label="难度"></el-table-column>
          <el-table-column prop="tache" label="进度" width="150">
            <template slot-scope="scope">
              <el-tag v-for="value in scope.row.tache_info"
                      v-if="value.finish_degree!==''?true:false" :key="value.id"
                      :type="value.finish_degree<100?'warning':'success'">
                {{ value.tache_byname }}:{{ value.finish_degree?value.finish_degree: '0' }}%
              </el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="plan_start_time" label="计划开始"></el-table-column>
          <el-table-column prop="plan_end_time" label="计划结束"></el-table-column>
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
        <el-tab-pane label="资产制作中" name="assetsInDevelopment">
          <div class="asset_card ovf-hd">
            <el-col v-if="inDevelopmentShow" :span="12">
              <div class="grid-content">
                <h2 class="m-0">制作中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="item in inProductionList" :key="item.id" @click="assetDetail(item.id)">
                    <el-card>
                      <div>
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">
                            {{item.project_name}}：<span>{{item.shot_number}}</span>
                          </p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tooltip class="pointer" effect="dark" content="资产难度" placement="bottom-start">
                              <el-tag type="warning">{{item.difficulty}}</el-tag>
                            </el-tooltip>
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="资产优先级"
                                        placement="bottom-start">
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
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产剩余天数" placement="bottom-start">
                                <span>{{ assetRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产建立时间" placement="bottom-start">
                                <span>{{ assetCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产制作中时间"
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
              <div class="grid-content bg-purple-light">
                <h2 class="m-0">反馈中</h2>
                <ul class="p-l-0 m-0">
                  <li v-for="item in feedbackList" :key="item.id" @click="assetDetail(item.id)">
                    <el-card class="">
                      <div class="">
                        <div class="text-Lens pos-rel">
                          <p class="text-Lens-name h-28 ">
                            {{item.project_name}}：<span>{{item.shot_number}}</span>
                          </p>
                          <p class="text-Lens-rank pos-abs">
                            <el-tooltip class="pointer" effect="dark" content="资产难度" placement="bottom-start">
                              <el-tag type="warning">{{item.difficulty}}</el-tag>
                            </el-tooltip>
                            <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="资产优先级"
                                        placement="bottom-start">
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
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产剩余天数" placement="bottom-start">
                                <span>{{ assetRemainDay(item.plan_end_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产建立时间" placement="bottom-start">
                                <span>{{ assetCreateTime(item.create_timestamp) }}天</span>
                              </el-tooltip>
                              <el-tooltip class="m-r-5 pointer" effect="dark" content="资产制作中时间"
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
        <el-tab-pane label="资产未制作" name="assetsNotDevelopment">
          <div class="ovf-hd">
            <el-col :span="12" v-for="item in waitingList" :key="item.id">
              <div class="grid-content p-b-5" @click="assetDetail(item.id)">
                <el-card class="ovf-hd">
                  <el-col :span="7">
                    <img :src="address + item.asset_image" alt="" class="w-100p h-140">
                  </el-col>
                  <el-col :span="17" class="p-l-20 pos-rel">
                    <p>{{item.asset_name}}</p>
                    <p>
                      <span class="m-r-15">{{item.project_name}}</span><span class="m-r-15">{{ item.type_name }}</span>
                    </p>
                    <p>
                      <el-tag class="m-r-5" v-for="value in item.tache_info"
                              :key="value.id" type="warning">
                        {{ value.tache_byname }}
                      </el-tag>
                    </p>
                    <div class="pos-abs r-0 t-0">
                      <p class="m-b-0 m-t-10 tx-r">
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产制作中时间" placement="bottom-start">
                          <span>0天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产剩余天数" placement="bottom-start">
                          <span>{{ assetRemainDay(item.plan_end_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="pointer" effect="dark" content="资产难度" placement="bottom-start">
                          <el-tag type="warning">{{item.difficulty}}</el-tag>
                        </el-tooltip>
                      </p>
                      <p class="m-b-0 m-t-10 tx-r">
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="预计结束时间" placement="bottom-start">
                          <span>{{ j2time(item.plan_end_timestamp) }}</span>
                        </el-tooltip>
                        <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="资产优先级"
                                    placement="bottom-start">
                          <el-tag type="danger">{{item.priority_level}}</el-tag>
                        </el-tooltip>
                      </p>
                    </div>
                  </el-col>
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
        </el-tab-pane>
        <el-tab-pane v-if="suspendShow" label="资产暂停" name="assetsSuspend">
          <el-col :span="12" v-for="item in pauseList" :key="item.id" @click="assetDetail(item.id)">
            <div class="grid-content p-b-5">
              <el-card>
                <div class="text-Lens pos-rel">
                  <p class="text-Lens-name h-28 ">
                    {{item.project_name}}：<span>{{item.shot_number}}</span>
                  </p>
                  <p class="text-Lens-rank pos-abs">
                    <el-tooltip class="pointer" effect="dark" content="资产难度" placement="bottom-start">
                      <el-tag type="warning">{{item.difficulty}}</el-tag>
                    </el-tooltip>
                    <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="资产优先级"
                                placement="bottom-start">
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
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产剩余天数" placement="bottom-start">
                          <span>{{ assetRemainDay(item.plan_end_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产建立时间" placement="bottom-start">
                          <span>{{ assetCreateTime(item.create_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产制作中时间" placement="bottom-start">
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
        <el-tab-pane v-if="finishShow" label="资产完成" name="assetsFinish">
          <el-col :span="12" v-for="item in finishList" :key="item.id" @click="assetDetail(item.id)">
            <div class="grid-content p-b-5">
              <el-card>
                <div class="text-Lens pos-rel">
                  <p class="text-Lens-name h-28 ">
                    {{item.project_name}}：<span>{{item.shot_number}}</span>
                  </p>
                  <p class="text-Lens-rank pos-abs">
                    <el-tooltip class="pointer" effect="dark" content="资产难度" placement="bottom-start">
                      <el-tag type="warning">{{item.difficulty}}</el-tag>
                    </el-tooltip>
                    <el-tooltip v-if="item.priority_level" class="pointer" effect="dark" content="资产优先级"
                                placement="bottom-start">
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
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产剩余天数" placement="bottom-start">
                          <span>{{ assetRemainDay(item.plan_end_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产建立时间" placement="bottom-start">
                          <span>{{ assetCreateTime(item.create_timestamp) }}天</span>
                        </el-tooltip>
                        <el-tooltip class="m-r-5 pointer" effect="dark" content="资产制作中时间" placement="bottom-start">
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
        <div v-show="isAssetDetailShow" class="asset_detail fr">
          <el-card>
            <div slot="header" class="clearfix">
              <span>资产详情</span>
              <i v-if="editShow" class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editAsset"></i>
              <i v-if="deleteShow" class="el-icon-delete m-l-5 fz-14 c-light-gray pointer" @click="deleteAsset"></i>
              <i class="el-icon-close fr pointer" @click="isAssetDetailShow = !isAssetDetailShow"></i>
            </div>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产缩略图：<img :src="address + editAssetDetail.asset_image" alt="" class="vtcal-mid h-40">
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产简称：<span>{{ editAssetDetail.asset_byname }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">资产所属项目：<span>{{ editAssetDetail.project_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产名称：<span>{{ editAssetDetail.asset_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">资产类型：<span>{{ editAssetDetail.type_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">计划开始时间：<span>{{ j2time(editAssetDetail.plan_start_timestamp) }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">计划结束时间：<span>{{ j2time(editAssetDetail.plan_end_timestamp) }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产优先级：<span>{{ editAssetDetail.priority_level_name }}</span></p>
              </el-col>
              <el-col :span="12">
                <p class="m-0">资产难度：<span>{{ editAssetDetail.difficulty_name }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">
                  环节所属工作室：
                  <span class="tache_studio dp-b" v-for="(item, index) in editAssetDetail.tache_info" :key="index">
                    <i class="el-icon-close m-l-5 c-light-gray pointer" v-if="deleteShowTache"
                       @click="deleteTache(index)"></i>
                    <span>{{ index }}：</span>
                    <el-tag size="mini" v-for="studio in item" :closable="deleteShowTacheStudio" type="info"
                            @close="deleteTacheStudio(index, studio)" :key="studio.id">
                      {{ studio.name }}
                    </el-tag>
                  </span>
                </p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">二级公司：<span>{{ editAssetDetail.second_company }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="12">
                <p class="m-0">资产备注：<span>{{ editAssetDetail.asset_explain }}</span></p>
              </el-col>
            </el-row>
            <el-row :gutter="20" class="m-b-5">
              <el-col :span="24">
                <p class="m-0">制作要求：<span>{{ editAssetDetail.make_demand }}</span></p>
              </el-col>
            </el-row>
          </el-card>
        </div>
      </transition>
    </div>
    <editAssets :message="editAssetDetail" @updataAssetDetail="assetDetail" ref="editAssets"></editAssets>
  </div>
</template>
<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import editAssets from '../assets/edit.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {
        multipleSelection: [],
        currentPage: 1, //        listLimit: ,
        listCurrentPage: 1,
        isAssetDetailShow: false,    //是否显示资产详情
        activeName: 'assetsNotDevelopment', //资产tab当前选中值
        isList: false,    // 是否显示资产列表
        address: window.baseUrl + '/',
        inProductionDataCount: 0,  //制作中总数量
        inProductionList: [],  //制作中列表
        feedbackDataCount: 0,  //反馈中总数量
        feedbackList: [],  //反馈中列表
        waitingDataCount: 0,  //等待资产总数量
        waitingList: [],  //等待资产列表
        pauseDataCount: 0,  //资产暂停总数量
        pauseList: [],  //资产暂停列表
        finishDataCount: 0,  //资产完成总数量
        finishList: [],  //资产完成列表
        listDataCount: 0,  //资产表格列表总数量
        tableData: [], //资产表格列表
        page: 1,
        limit: 10,
        editAssetDetail: {},
        search: {
          project_id: '',
          field_id: '',
          priority_level: '',
          asset_content: ''
        },
        projectList: [],
        fieldList: []
      }
    },
    methods: {
      searchAsset () {
        if (this.search.shot_number.length === 6) {
          this.search.project_id = ''
          this.search.field_id = ''
          this.search.shot_id = ''
          this.getAllAssetsList()
        }
        if (!this.search.field_id && this.search.shot_number.length === 3) {
          _g.toastMsg('warning', '请选择所属项目及场号')
          return
        } else {
          this.search.shot_id = ''
          this.getAllAssetsList()
        }
      },
      //      点击编辑资产执行方法
      editAsset () {
        this.$refs.editAssets.open()
      },
//      点击资产显示资产详情
      assetDetail (id) {
//        判断资产详情传入值是否为数字
        if (!/^[0-9]*$/.test(id)) {
          id = id.id
        }
        this.id = id
        this.apiGet('admin/assets/' + id).then((res) => {
          this.handelResponse(res, (data) => {
            this.editAssetDetail = data
          })
        })
        if (!this.isAssetDetailShow) {
          this.isAssetDetailShow = !this.isAssetDetailShow
        }else {
          this.getAllAssetsList()
        }
      },
      //      资产详情删除环节
      deleteTache (tache_name) {
        this.$confirm('确认删除该环节?', '提示', {
          confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          const data = {
            id: this.id,
            tache_name: tache_name
          }
          this.apiPost('asset/tache_del', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.assetDetail(this.id)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      //      资产详情删除环节所属工作室
      deleteTacheStudio (tache_name, item) {
        this.$confirm('确认删除该环节所属工作室?', '提示', {
          confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          const data = {
            id: this.id, tache_name: tache_name, studio_id: item.id
          }
          this.apiPost('asset/studio_del', data).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.assetDetail(this.id)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      //      删除资产
      deleteAsset (id) {
        this.$confirm('确认删除该资产?', '提示', {
          confirmButtonText: '确定', cancelButtonText: '取消', type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/assets/', this.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.init(this.activeName)
              this.getAssetList(this.page)
              this.isAssetDetailShow = false
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      //      制作中切换分页
      inProductionCurrentChange (page) {
        this.getAssets('in_production', page)
      },
      //      反馈中切换分页
      feedbackCurrentChange (page) {
        this.getAssets('feedback', page)
      },
      //      等待资产中切换分页
      waitingCurrentChange (page) {
        this.getAssets('waiting', page)
      },
      //      资产暂停切换分页
      pauseCurrentChange (page) {
        this.getAssets('pause', page)
      },
      //      资产完成切换分页
      finishCurrentChange (page) {
        this.getAssets('finish', page)
      },
      listCurrentChange (page) {
        this.getAssetList(page)
      },
      /*
       * 资产列表批量点击checkbox
       * params: {
       *   val: 当前已选中的checkbox群的value
       * }
       * */
      handleSelectionChange (val) {
        this.multipleSelection = val;
      },
      /*
       * 切换资产tab方法
       *   params: {
       *     tab: 传入当前点击tab信息
       *   }
       * */
      tabClick (tab, event) {
        this.init(tab.name)
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
      //      资产剩余天数 = 预计结束时间戳 - 当前时间戳
      assetRemainDay (end_time) {
        return Math.ceil((end_time - new Date() / 1000) / 86400)
      },
      //      资产建立时间 = 当前时间戳 - 资产创建时间戳
      assetCreateTime (create_time) {
        return Math.ceil((new Date() / 1000 - create_time) / 86400)
      },
      //      获取所有项目
      getProjects () {
        this.apiGet('admin/projects').then((res) => {
          this.handelResponse(res, (data) => {
            this.projectList = data.list
          })
        })
      },
      /*
       * 获取某个状态的资产看板内容
       * params: {
       *   asset_status: 区分请求接口的地址（in_production、feedback、waiting、pause、finish）
       * }
       * */
      getAssets (asset_status, page) {
        const data = {
          params: {
            page: page, limit: this.limit, keywords: this.search
          }
        }
        let url = `asset/${asset_status}`
        this.loading = true
        this.apiGet(url, data).then((res) => {
          this.handelResponse(res, (data) => {
            switch (asset_status) {
              case 'in_production':
                this.inProductionDataCount = data.dataCount
                this.inProductionList = data.list
                break;
              case 'feedback':
                this.feedbackDataCount = data.dataCount
                this.feedbackList = data.list
                break;
              case 'waiting_assets':
                console.log(data.list)
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
      //      初始化资产列表
      getAssetList (page) {
        this.page = page
        const data = {
          params: {
            keywords: this.search, page: page, limit: this.limit
          }
        }
        this.apiGet('asset/index_list', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.listDataCount = data.dataCount
          })
        })
      },
      //      获取所有资产类型
      getAssetType () {
        this.getAllAssetsList()
        const data = {
          params: {
            project_id: this.search.project_id,
            type: 2
          }
        }
        this.apiGet('admin/get_fields', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.fieldList = data
          })
        })
      },
      getAllAssetsList () {
        this.init(this.activeName)
        this.getAssetList(1)
      },
      /*
       * 初始化资产看板内容
       * params: {
       *   tab_name : 当点击切换资产tab时的传入值
       * }
       * */
      init (tab_name) {
        switch (tab_name) {
          case 'assetsInDevelopment':
            this.getAssets('in_production', 1)
            this.getAssets('feedback', 1)
            break;
          case 'assetsNotDevelopment':
            this.getAssets('waiting_assets', 1)
            break;
          case 'assetsSuspend':
            this.getAssets('pause', 1)
            break;
          case 'assetsFinish':
            this.getAssets('finish', 1)
            break;
          default:
            this.activeName = 'assetsInDevelopment'
            this.init('assetsInDevelopment')
            break;
        }
      }
    },
    created () {
      this.getAllAssetsList()
      this.getProjects()
    },
    components: {
      editAssets,
      btnGroup
    },
    mixins: [http],
    computed: {
      //      编辑资产按钮
      editShow () {
        return _g.getHasRule('assets-update')
      },
      //      删除资产按钮
      deleteShow () {
        return _g.getHasRule('assets-delete')
      },
      //      删除资产所属环节按钮
      deleteShowTache () {
        return _g.getHasRule('assets-delete_tache')
      },
      //      删除资产环节所属工作室按钮
      deleteShowTacheStudio () {
        return _g.getHasRule('assets-delete_studio')
      },
      //      制作中按钮
      inDevelopmentShow () {
        return _g.getHasRule('assets-in_production_data')
      },
      //      反馈中按钮
      feedbackShow () {
        return _g.getHasRule('assets-feedback_data')
      },
      //      资产暂停按钮
      suspendShow () {
        return _g.getHasRule('assets-pause_data')
      },
      //      资产完成按钮
      finishShow () {
        return _g.getHasRule('assets-finish_data')
      }
    }
  }
</script>
<style>
  .asset_list .item {
    margin-right: 20px;
  }

  .asset_list .el-card__body {
    padding: 15px;
    overflow: hidden;
  }

  .asset_list .el-table {
    width: 95%;
  }

  .asset_list .el-tabs {
    width: 95%;
  }

  .asset_list .el-tag {
    padding: 0 8px;
    margin-bottom: 2px;
  }

  .asset_list .asset_detail {
    width: 25%;
    height: 75%;
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    overflow: scroll;
  }

  .asset_list .asset_detail p {
    font-size: .8rem;
    color: #666;
  }

  .asset_list .asset_detail p span {
    color: #333;
  }

  .asset_list .asset_detail p span.tache_studio {
    font-size: .75rem;
  }

  .asset_list .asset_detail .el-card__header {
    padding: 10px 15px;
    font-size: 14px;
  }

  .asset_list .asset_card h2 {
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
</style>

