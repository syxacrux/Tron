<template>
  <div class="help">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin' }">系统管理</el-breadcrumb-item>
        <el-breadcrumb-item>问题反馈</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <!--<div class="m-b-20 pos-rel">-->
      <!--<a class="btn-link-large add-btn" @click="isAddHelps = true;getOptions();getDegreeList()" v-if="addProblem">-->
        <!--<i class="el-icon-plus"></i>&nbsp;&nbsp;发起提问-->
      <!--</a>-->
      <!--<router-link class="btn-link-large add-btn" to="add" v-if="addArticle">-->
        <!--<i class="el-icon-plus"></i>&nbsp;&nbsp;发起文章-->
      <!--</router-link>-->
    <!--</div>-->
    <!--<div class="m-b-10 h-40 pos-rel">-->
      <!--<div class="pos-abs t-0 l-0">-->
        <!--<el-row :gutter="10" class="m-b-5">-->
          <!--<el-col :span="6  ">-->
            <!--<el-select v-model="search.system_category_id" placeholder="请选择系统">-->
              <!--<el-option label="所有系统" :value="0"></el-option>-->
              <!--<el-option label="Mac" :value="1"></el-option>-->
              <!--<el-option label="Linux" :value="2"></el-option>-->
              <!--<el-option label="Window" :value="3"></el-option>-->
            <!--</el-select>-->
          <!--</el-col>-->
          <!--<el-col :span="15">-->
            <!--<el-input placeholder="请输入关键字" v-model="search.keywords" class="input-with-select">-->
              <!--<el-select class="w-80" v-model="search.type" slot="prepend" placeholder="类型">-->
                <!--<el-option label="文章" value="1"></el-option>-->
                <!--<el-option label="问题" value="2"></el-option>-->
              <!--</el-select>-->
              <!--<el-button slot="append" icon="el-icon-search" @click="getAllHelps(1)"></el-button>-->
            <!--</el-input>-->
          <!--</el-col>-->
        <!--</el-row>-->
      <!--</div>-->
    <!--</div>-->
    <div class="help_list" v-if="helpList.length">
      <ul>
        <li class="bor-b-gray m-b-10" v-for="item in helpList">
          <transition name="el-zoom-in-center">
            <el-checkbox-group v-show="show2" v-model="multipleSelection">
              <el-checkbox :label="item" :key="item.id">{{ item }}</el-checkbox>
            </el-checkbox-group>
          </transition>
          <router-link v-if="isHelpResolve" :to="{ name: 'helpsResolve', params: {id: item.id} }">
            <span class="degree" v-if="item.type === 2">【{{ item.degree_name }}】</span>
            {{ item.type_name }}
            <span>{{ item.title }}</span>
          </router-link>
          <router-link v-if="!isHelpResolve" :to="{ name: 'helpsDetail', params: {id: item.id} }">
            <span class="degree" v-if="item.type === 2">【{{ item.degree_name }}】</span>
            {{ item.type_name }}
            <span>{{ item.title }}</span>
          </router-link>
          <p class="tx-r fr fz-14">{{ item.user_name }} 发布于  {{ item.create_time }}</p>
          <div class="h-40 w-1000 fz-12 c-black space_nowr">
            <span class="highlight">{{ item.content }}</span>
          </div>
        </li>
      </ul>
    </div>
    <div class="pos-rel p-t-20" v-if="helpList.length">
      <el-button @click="$router.push({ name: 'helpsIndex' })" size="mini">返回</el-button>
      <el-button v-if="show2 == false" size="small" @click="show2 = !show2">选择</el-button>
      <el-button v-if="show2 == true" size="small" @click="show2 = !show2">取消选择</el-button>
      <btnGroup v-show="show2" :selectedData="multipleSelection" :type="'helps'"></btnGroup>
      <div class="block pages">
        <el-pagination
            @current-change="handleCurrentChange"
            layout="prev, pager, next"
            :page-size="limit"
            :current-page="currentPage"
            :total="dataCount">
        </el-pagination>
      </div>
    </div>
  </div>
</template>
<script>
  import editHelps from './edit.vue'
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  export default {
    data () {
      return {
        show2: false,
        isHelpResolve: false,
        dataCount: null,
        multipleSelection: [],
        helpList: [],
        currentPage: 1,
        keywords: '',
        filterName: '',
        limit: 10,
        page: 1,
        search: {
          keywords: '',
          system_category_id: '',
          type: ''
        }
      }
    },
    methods: {
//      切换页码
      handleCurrentChange (page) {
        this.getAllHelps(page)
      },
//      获取问题反馈列表
      getAllHelps (page) {
        this.page = page
        if (this.$route.query.word) {
          console.log(1)
          this.isHelpResolve = true
          this.filterName = this.$route.query.word
          var data = {
            params: {
              keywords: {
                category_id : this.$route.query.category_id,
                word: this.$route.query.word,
              },
              page: page,
              limit: this.limit
            }
          }
          this.apiGet('help/new_ask_word', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.dataCount = data.dataCount
              this.helpList = data.list
            })
          })
        } else {
          console.log(3)
          this.isHelpResolve = false
          var data = {
            params: {
              keywords: {},
              page: page,
              limit: this.limit
            }
          }
          this.apiGet('admin/helps', data).then((res) => {
            this.handelResponse(res, (data) => {
              this.dataCount = data.dataCount
              this.helpList = data.list
            })
          })
        }
      },
//      初始化问题反馈列表内容
      init () {
        this.getAllHelps(1)
      }
    },
    created () {
      this.init()
    },
    computed: {
//      问题反馈列表
      listShow () {
        return _g.getHasRule('helps-index')
      },
//      发起文章按钮
      addArticle () {
        return _g.getHasRule('helps-save')
      },
//      发起文章按钮
      addProblem () {
        return _g.getHasRule('helps-save_problem')
      },
//      编辑文章按钮
      editShow () {
        return _g.getHasRule('helps-update')
      },

    },
    components: {
      editHelps,
      btnGroup
    },
    mixins: [http]
  }
</script>
<style>
  .el-collapse {
    border: none;
  }
  .el-collapse-item__content {
    padding-bottom: 10px;
  }
  .help .help_list {
    padding: 15px;
    border-radius: 5px;
    background: #fff;
  }
  .help .help_list li a{
    color: #409eff;
  }
  .help .help_list li a:hover{
    text-decoration: underline;
  }
  .help .help_list li p{
    margin: 0;
    color: #666;
  }
  .help .help_list .degree{
    color: orangered;
    font-weight: 500;
  }
  .help .el-checkbox__label{
    display: none;
  }
  .help .el-checkbox-group{
    display: inline-block;
  }
</style>