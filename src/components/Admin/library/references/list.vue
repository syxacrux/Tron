<template>
  <div class="reference_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/references/list' }">库管理</el-breadcrumb-item>
        <el-breadcrumb-item>参考库管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <el-button v-if="addShow" type="primary" class="btn-link-large add-btn fl" @click="addReference">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;上传资料
      </el-button>
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
    <div class="ovf-hd">
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
        <el-tab-pane label="项目" name="project">
          <div class="m-b-20 pos-rel h-40">
            <div class="pos-abs t-0l-0">
              <el-row :gutter="10" class="m-b-5">
                <el-col :span="5">
                  <el-select v-model="search.project_id" placeholder="请选择项目" @change="getFields">
                    <el-option label="请选择项目" value=""></el-option>
                    <el-option v-for="item in projectList" :label="item.project_name" :value="item.id" :key="item.id"></el-option>
                  </el-select>
                </el-col>
                <el-col :span="5">
                  <el-select v-model="search.field_id" placeholder="请选择场号" @change="getShotsNumOrAsset">
                    <el-option label="请选择场号" value=""></el-option>
                    <el-option v-for="item in fieldList" :label="item.name" :value="item.id" :key="item.id"></el-option>
                  </el-select>
                </el-col>
                <el-col :span="5">
                  <el-select v-model="search.shot_id" placeholder="请选择镜头号" @change="getReferences(activeName)">
                    <el-option label="请选择镜头号" value=""></el-option>
                    <el-option v-for="(item, index) in shotList" :label="item.shot_number" :value="item.id" :key="index"></el-option>
                  </el-select>
                </el-col>
                <el-col :span="6">
                  <el-input placeholder="请输入场号镜头号" v-model.trim="search.shot_number">
                    <el-button slot="append" icon="el-icon-search" @click=""></el-button>
                  </el-input>
                </el-col>
              </el-row>
            </div>
          </div>
          <section>
            <div class="ovf-hd w-200 m-r-10 fl">
              <el-input placeholder="输入关键字进行过滤" v-model="filterText"></el-input>
              <el-tree
                  class="filter-tree"
                  :data="data2"
                  :props="defaultProps"
                  default-expand-all
                  :filter-node-method="filterNode"
                  ref="tree2">
              </el-tree>
            </div>
            <div class="ovf-hd">
              <el-col :span="4">
              <!--<el-col :span="12" v-for="item in projectRefList" :key="item.id">-->
                <div class="grid-content p-b-5" @click="referenceDetail">
                <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
              <el-col :span="4">
              <!--<el-col :span="12" v-for="item in projectList" :key="item.id">-->
                <div class="grid-content p-b-5">
                <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
              <el-col :span="4">
              <!--<el-col :span="12" v-for="item in projectList" :key="item.id">-->
                <div class="grid-content p-b-5">
                <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
            </div>
            <!--<div class="pos-rel p-t-20" v-if="projectList.length">-->
            <div class="pos-rel p-t-20">
              <div class="block tx-r">
                <el-pagination
                    @current-change="projectRefCurrentChange"
                    :current-page.sync="currentPage"
                    :page-size="10"
                    layout="prev, pager, next, jumper"
                    :total="projectRefDataCount">
                </el-pagination>
              </div>
            </div>
          </section>
        </el-tab-pane>
        <el-tab-pane label="公共" name="common">
          <section>
            <!--<div class="ovf-hd w-200 m-r-10 fl">-->
              <!--<el-input placeholder="输入关键字进行过滤" v-model="filterText"></el-input>-->
              <!--<el-tree-->
                  <!--class="filter-tree"-->
                  <!--:data="data2"-->
                  <!--:props="defaultProps"-->
                  <!--default-expand-all-->
                  <!--:filter-node-method="filterNode"-->
                  <!--ref="tree2">-->
              <!--</el-tree>-->
            <!--</div>-->
            <div class="ovf-hd">
              <el-col :span="4">
                <!--<el-col :span="12" v-for="item in commonList" :key="item.id">-->
                <div class="grid-content p-b-5">
                  <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
              <el-col :span="4">
                <!--<el-col :span="12" v-for="item in projectList" :key="item.id">-->
                <div class="grid-content p-b-5">
                  <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
              <el-col :span="4">
                <!--<el-col :span="12" v-for="item in projectList" :key="item.id">-->
                <div class="grid-content p-b-5">
                  <!--<div class="grid-content p-b-5" @click="referenceDetail(item.id)">-->
                  <el-card class="ovf-hd">
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                    1233523423423443
                  </el-card>
                </div>
              </el-col>
            </div>
            <!--<div class="pos-rel p-t-20" v-if="commonList.length">-->
            <div class="pos-rel p-t-20">
              <div class="block tx-r">
                <el-pagination
                    @current-change="commonCurrentChange"
                    :current-page.sync="currentPage"
                    :page-size="10"
                    layout="prev, pager, next, jumper"
                    :total="commonDataCount">
                </el-pagination>
              </div>
            </div>
          </section>
        </el-tab-pane>
      </el-tabs>
    </div>
    <transition name="el-zoom-in-center">
      <div v-show="isReferenceDetailShow" class="reference_detail fr">
        <el-card>
          <div slot="header" class="clearfix">
            <span>参考文件详情</span>
            <i v-if="editShow" class="el-icon-edit m-l-5 fz-14 c-light-gray pointer" @click="editReference"></i>
            <i v-if="deleteShow" class="el-icon-delete m-l-5 fz-14 c-light-gray pointer" @click="deleteReference"></i>
            <i class="el-icon-close fr pointer" @click="isReferenceDetailShow = !isReferenceDetailShow"></i>
          </div>
          <!--<el-row :gutter="20" class="m-b-5">-->
            <!--<el-col :span="12">-->
              <!--<p class="m-0">资产缩略图：<img :src="address + editAssetDetail.asset_image" alt="" class="vtcal-mid h-40">-->
              <!--</p>-->
            <!--</el-col>-->
          <!--</el-row>-->
          <el-row :gutter="20" class="m-b-5">
            <el-col :span="12">
              <p class="m-0">名称：<span>aaaaaaaaaaaa</span></p>
            </el-col>
            <el-col :span="12">
              <p class="m-0">场号/资产：<span>aaaaaaaaaaaa</span></p>
            </el-col>
          </el-row>
          <el-row :gutter="20" class="m-b-5">
            <el-col :span="12">
              <p class="m-0">镜头号/资产号：<span>aaaaaaaaaaaa</span></p>
            </el-col>
            <el-col :span="12">
              <p class="m-0">所属项目：<span>aaaaaaaaaaaa</span></p>
            </el-col>
          </el-row>
          <el-row :gutter="20" class="m-b-5">
            <el-col :span="12">
              <p class="m-0">使用环节：<span>aaaaaaaaaaaa</span></p>
            </el-col>
            <el-col :span="12">
              <p class="m-0">提供者：<span>aaaaaaaaaaaa</span></p>
            </el-col>
          </el-row>
        </el-card>
      </div>
    </transition>
    <el-dialog title="上传文件" :visible.sync="isUploadReference" width="30%">
      <el-form :model="form" label-width="120px" :rules="uploadRule">
        <el-form-item label="文件名称:">
          <el-input v-model="form.file_name" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="类型:">
          <el-radio-group v-model="form.resource_type" size="small" @change="getShotOrAsset">
            <el-radio label="1">公共类型</el-radio>
            <el-radio label="2">镜头类型</el-radio>
            <el-radio label="3">资产类型</el-radio>
          </el-radio-group>
        </el-form-item>
        <div v-if="isResourceType">
          <el-form-item label="所属项目:">
            <el-select v-model="form.project_id" placeholder="请选择项目" @change="getFields">
              <el-option label="请选择项目" value=""></el-option>
              <el-option v-for="item in projectList" :label="item.project_name" :value="item.id" :key="item.id"></el-option>
            </el-select>
          </el-form-item>
          <div v-if="this.form.resource_type == 2">
            <el-form-item label="场号/集号:">
              <el-select v-model="form.field_id" placeholder="请选择场号/集号" @change="getShotsNumOrAsset">
                <el-option label="请选择场号/集号" value=""></el-option>
                <el-option v-for="item in fieldList" :label="item.name" :value="item.id" :key="item.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="镜头号:">
              <el-select v-model="form.resource_id" placeholder="请选择镜头号">
                <el-option label="请选择镜头号" value=""></el-option>
                <el-option v-for="item in shotList" :label="item.shot_number" :value="item.id" :key="item.id"></el-option>
              </el-select>
            </el-form-item>
          </div>
          <div v-if="this.form.resource_type == 3">
            <el-form-item label="资产类型:">
              <el-select v-model="form.field_id" placeholder="请选择资产类型" @change="getShotsNumOrAsset">
                <el-option label="请选择资产类型" value=""></el-option>
                <el-option v-for="item in fieldList" :label="item.explain" :value="item.id"
                           :key="item.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="资产号:">
              <el-select v-model="form.resource_id" placeholder="请选择资产号">
                <el-option label="请选择资产号" value=""></el-option>
                <el-option v-for="item in assetList" :label="item.asset_name" :value="item.id"
                           :key="item.id"></el-option>
              </el-select>
            </el-form-item>
          </div>
          <el-form-item label="环节:">
            <el-select v-model="form.tache_info" placeholder="请选择环节" multiple class="">
              <el-option v-for="item in tacheList" :label="item.explain" :value="item.id"
                         :key="item.id"></el-option>
            </el-select>
          </el-form-item>
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isUploadReference = false">取 消</el-button>
        <el-button type="primary" @click="isUploadReference = false">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>

  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {
        isUploadReference: false,
        isList: false,
        isReferenceDetailShow: false,
        isResourceType: false,
        activeName: 'project',
        page: 1,
        limit: 10,
        projectRefCurrentChange: 1,
        commonCurrentChange: 1,
        currentPage: 1,
        projectRefDataCount: 0,
        projectRefList: [],
        commonDataCount: 0,
        commonList: [],
        filterText: '',
        projectList: [],
        fieldList: [],
        shotList: [],
        fileList: [],
        tacheList: [],
        assetList: [],
        search: {
          project_id: '',
          field_id: '',
          shot_id: '',
          shot_number: ''
        },
        data2: [{
          id: 1,
          label: '一级 1',
          children: [{
            id: 4,
            label: '二级 1-1',
            children: [{
              id: 9,
              label: '三级 1-1-1'
            }, {
              id: 10,
              label: '三级 1-1-2'
            }]
          }]
        }, {
          id: 2,
          label: '一级 2',
          children: [{
            id: 5,
            label: '二级 2-1'
          }, {
            id: 6,
            label: '二级 2-2'
          }]
        }, {
          id: 3,
          label: '一级 3',
          children: [{
            id: 7,
            label: '二级 3-1'
          }, {
            id: 8,
            label: '二级 3-2'
          }]
        }],
        defaultProps: {
          children: 'children',
          label: 'label'
        },
        form: {
          file_name: '',
          resource_type: '',
          project_id: '',
          field_id: '',
          resource_id: '',
          tache_info: []
        },
        uploadRule: {
//          file_name: [
//            { required: true, message: '请输入活动名称', trigger: 'blur' },
//            { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
//          ],
//          region: [
//            { required: true, message: '请选择活动区域', trigger: 'change' }
//          ],
//          date1: [
//            { type: 'date', required: true, message: '请选择日期', trigger: 'change' }
//          ],
//          date2: [
//            { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
//          ],
//          type: [
//            { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
//          ]
        }
      }
    },
    methods: {
      filterNode(value, data) {
        if (!value) return true;
        return data.label.indexOf(value) !== -1;
      },
      handlePreview(file) {
        console.log(file);
      },
      referenceDetail(id) {
        //        判断资产详情传入值是否为数字
        if (!/^[0-9]*$/.test(id)) {
          id = id.id
        }
//        this.id = id
//        this.apiGet('admin/references/' + id).then((res) => {
//          this.handelResponse(res, (data) => {
//            this.editAssetDetail = data
//          })
//        })
//        if (!this.isReferenceDetailShow) {
          this.isReferenceDetailShow = !this.isReferenceDetailShow
//        }else {
//          this.getAllReferencesList()
//        }
      },
      editReference() {

      },
      deleteReference() {

      },
      addReference() {
        this.isUploadReference = true
//        获取环节列表
        this.apiGet('admin/taches').then((res) => {
          this.handelResponse(res, (data) => {
            this.tacheList = data.list
          })
        })
      },
//      改变上传文件类型
      getShotOrAsset(value) {
        this.form.project_id = ""
        this.form.field_id = ""
        this.form.resource_id = ""
        this.fieldList = []
        this.shotList = []
        this.assetList = []
        switch (value) {
          case "1" :
            this.form.tache_info = []
            this.isResourceType = false
            console.log(this.form, value)
            break;
          case "2" :
            this.isResourceType = true
            console.log(this.form, value)
            break;
          case "3" :
            this.isResourceType = true
            console.log(this.form, value)
            break;
        }
      },
//      获取所有项目
      getProjects() {
        this.apiGet('admin/projects').then((res) => {
          this.handelResponse(res, (data) => {
            this.projectList = data.list
          })
        })
      },
//      获取所有场号、集号/资产类型
      getFields() {
        let project_id = this.isUploadReference ? this.form.project_id : this.search.project_id
        const data = {
          params: {
            project_id: project_id,
            type: 1
          }
        }
        if(this.form.resource_type == 2){
          this.apiGet('admin/get_fields', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.form.field_id = ''
              this.fieldList = data
            })
          })
        }else if(this.form.resource_type == 3) {
          const data = {
            params: {
              project_id: project_id,
              type:2
            }
          }
          this.apiGet('admin/get_fields', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.form.field_id = ''
              this.fieldList = data
            })
          })

        }
      },
//      获取所有场号集号下的镜头号/资产号
      getShotsNumOrAsset() {
        this.form.resource_id = ''
        let field_id = this.isUploadReference ? this.form.field_id : this.search.field_id
        const data = {
          params: {
            field_id: field_id
          }
        }
        if(this.form.resource_type == 2) {
          this.apiGet('admin/get_shot_num', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.shotList = data
            })
          })
        }else if(this.form.resource_type == 3) {
          this.apiGet('asset/get_asset_name', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.assetList = data
            })
          })
        }

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
      getAllReferencesList () {
        this.init(this.activeName)
        this.getReferenceList(1)
      },
//      初始化资产列表
      getReferenceList (page) {
        this.page = page
        const data = {
          params: {
            keywords: this.search, page: page, limit: this.limit
          }
        }
        this.apiGet('reference/index_list', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.listDataCount = data.dataCount
          })
        })
      },
      /*
       * 获取某个状态的资产看板内容
       * params: {
       *   asset_status: 区分请求接口的地址（in_production、feedback、waiting、pause、finish）
       * }
       * */
      getReferences (reference_status, page) {
        const data = {
          params: {
            page: page, limit: this.limit, keywords: this.search
          }
        }
        let url = `references/${reference_status}`
        this.loading = true
        this.apiGet(url, data).then((res) => {
          this.handelResponse(res, (data) => {
            switch (asset_status) {
              case 'project':
                this.projectRefDataCount = data.dataCount
                this.projectRefList = data.list
                break;
              case 'common':
                this.commonDataCount = data.dataCount
                this.commonList = data.list
                break;
            }
          })
        })
      },
      /*
       * 初始化资产看板内容
       * params: {
       *   tab_name : 当点击切换资产tab时的传入值
       * }
       * */
      init (tab_name) {
        switch (tab_name) {
          case 'project':
            this.getReferences('project', 1)
            break;
          case 'common':
            this.getReferences('common', 1)
            break;
        }
      }
    },
    created () {
      this.getAllReferencesList()
      this.getProjects()
    },
    components: {

    },
    mixins: [http],
    computed: {
      //      添加参考库文件按钮
      addShow () {
        return _g.getHasRule('references-save')
      },
      //      编辑参考库文件按钮
      editShow () {
        return _g.getHasRule('references-update')
      },
      //      删除参考库文件按钮
      deleteShow () {
        return _g.getHasRule('references-delete')
      }
    },
    watch: {
      filterText(val) {
        this.$refs.tree2.filter(val);
      }
    },
  }
</script>
<style>
  .reference_list .item {
    margin-right: 20px;
  }

  .reference_list .el-card__body {
    padding: 15px;
    overflow: hidden;
  }

  .reference_list .el-table {
    width: 100%;
  }

  .reference_list .el-tabs {
    width: 100%;
  }

  .reference_list .el-tag {
    padding: 0 8px;
    margin-bottom: 2px;
  }

  .reference_list .reference_detail {
    width: 40%;
    height: 75%;
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    overflow: scroll;
  }
</style>

