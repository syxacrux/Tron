<template>
  <div class="help">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin' }">系统管理</el-breadcrumb-item>
        <el-breadcrumb-item>问题反馈</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <a class="btn-link-large add-btn" @click="isAddHelps = true" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;创建问题
      </a>
      <router-link class="btn-link-large add-btn" to="add" v-if="addShow">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;创建文章
      </router-link>
      <div class="pos-abs r-0 t-0">
        <el-col>
          <el-input placeholder="请输入关键字" class="input-with-select">
            <el-button slot="append" icon="el-icon-search"></el-button>
          </el-input>
        </el-col>
      </div>
    </div>
    <div class="help_list">
      <ul>
        <li class="bor-b-gray m-b-10" v-for="item in helpList">
          <router-link v-if="item.type === 1" :to="{ name: 'helpDetail', params: {id: item.id} }">
            {{ item.type_name }}   {{ item.title }}
          </router-link>
          <router-link v-if="item.type === 2" :to="{ name: 'helpDetail', params: {id: item.id} }">
            {{ item.type_name }}   {{ item.content }}
          </router-link>
          <p class="tx-r fr fz-14">{{ item.user_name }} 发布于  {{ item.create_time }}</p>
          <div v-if="item.type === 1" class="h-40 w-1000 fz-12 c-black space_nowr">
            {{ item.content }}
          </div>
        </li>
      </ul>
    </div>
    <div class="pos-rel p-t-20">
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
    <el-dialog title="问题反馈" :visible.sync="isAddHelps" width="30%">
      <el-form ref="form" :model="form" label-width="120px" :rules="rules">
        <el-form-item label="反馈类型：" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择问题类型">
            <el-option v-for="item in options" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="反馈类型：" prop="degree">
          <el-select v-model="form.degree" placeholder="请选择紧急程度">
            <el-option v-for="item in degreeOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="反馈内容：" prop="content">
            <el-input
                type="textarea"
                :autosize="{ minRows: 2, maxRows: 4}"
                placeholder="请输入反馈内容"
                v-model="form.content">
            </el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="">取 消</el-button>
        <el-button type="primary" @click="addHelps(form)">确 定</el-button>
      </div>
    </el-dialog>
    <editHelps :message="editHelpDetail" @updataHelpDetail="helpDetail" ref="editHelps"></editHelps>
  </div>
</template>
<script>
  import editHelps from './edit.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  export default {
    data () {
      return {
        isAddHelps: false,
        activeName: '1',
        dataCount: null,
        helpList: [],
        currentPage: 1,
        keywords: '',
        options: [],
        degreeOptions: [],
        limit: 10,
        page: 1,
        form: {
          category_id: '',
          content: '',
          degree: '',
          type: 2
        },
        rules: {
          category_id: [{required: true, message: '请选择问题类型', trigger: 'blur'}],
          content: [{required: true, message: '请输入反馈内容', trigger: 'blur'}],
          degree: [{required: true, message: '请输入反馈内容', trigger: 'blur'}]
        },
        editHelpDetail: {}
      }
    },
    methods: {
      helpDetail () {

      },
      addHelps (form) {
        console.log(this.form)
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.apiPost('admin/helps', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                setTimeout(() => {
                  this.isLoading = !this.isLoading
                  this.form = {
                    category_id: '',
                    content: '',
                    degree: '',
                    type: 2
                  }
                  this.isAddHelps = !this.isAddHelps
                  this.getAllHelps(this.page)
                }, 1500)
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
          }
        })
      },
//      切换页码
      handleCurrentChange (page) {
        this.getAllParameter(page)
      },
//      获取问题反馈列表
      getAllHelps (page) {
        this.page = page
        this.loading = true
        const data = {
          params: {
            keywords: this.keywords,
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
      },
//      获取关键字
      getKeywords () {
        let data = this.$route.query
        if (data) {
          if (data.keywords) {
            this.keywords = data.keywords
          } else {
            this.keywords = ''
          }
        }
      },
//      获取父级分类
      getParameters() {
        const data = {
          params: {
            keywords: {
              pid:  4
            }
          }
        }
        this.apiGet('admin/parameters',data).then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
      },
      //      获取父级分类
      getDegreeList() {
        const data = {
          params: {
            keywords: {
              pid:  9
            }
          }
        }
        this.apiGet('admin/parameters',data).then((res) => {
          this.handelResponse(res, (data) => {
            this.degreeOptions = data.list
          })
        })
      },
//      初始化问题反馈列表内容
      init () {
        this.getAllHelps(1)
        this.getParameters()
        this.getDegreeList()
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
//      添加问题反馈按钮
      addShow () {
        return _g.getHasRule('helps-save')
      },
//      编辑问题反馈按钮
      editShow () {
        return _g.getHasRule('helps-update')
      },
//      删除问题反馈按钮
      deleteShow () {
        return _g.getHasRule('helps-delete')
      }
    },
    components: {
      editHelps
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
</style>