<template>
  <div class="help_add">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>问题反馈</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <router-link class="btn-link-large add-btn" to="add_article" v-if="isAddArticle">
      <i class="el-icon-plus"></i>&nbsp;&nbsp;发起文章
      </router-link>
    </div>
    <div class="m-l-50 m-t-30 w-900">
      <h2>提问</h2>
      <el-form ref="form" :model="form" label-width="120px" :rules="rules">
        <el-form-item label="问题类型：" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择问题类型" @change="getCategories">
            <el-option v-for="item in typeOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <!--<el-form-item label="不知道：" prop="category_id">-->
          <!--<el-select v-model="form.category_id" placeholder="请选择buzhdiao">-->
            <!--<el-option v-for="item in categoryOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>-->
          <!--</el-select>-->
        <!--</el-form-item>-->
        <el-form-item label="紧急程度：" prop="degree">
          <el-select v-model="form.degree" placeholder="请选择紧急程度">
            <el-option v-for="item in degreeOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="标题：" prop="title">
          <el-input v-model="form.title" class="h-40 w-200" @input="change"></el-input>
        </el-form-item>
        <el-form-item label="问题描述：" prop="content">
          <el-input
              type="textarea"
              :autosize="{ minRows: 2, maxRows: 4}"
              placeholder="请输入问题内容"
              v-model="form.content">
          </el-input>
        </el-form-item>
        <transition name="el-zoom-in-top">
          <div v-show="show1" class="transition-box">
            <ul>
              <li v-for="item in keywordList">
                <router-link :to="{ name: 'helpsResolve', params: {id: item.id} }">
                  {{ item.title }}
                </router-link>
                <div class="h-40 w-1000 fz-12 c-black space_nowr">
                  {{ item.content }}
                </div>
              </li>
              <div @click="getKeywordList">查看所有相关</div>
            </ul>
          </div>
        </transition>
        <el-form-item>
          <el-button type="primary" @click="addHelps(form)">确 定</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import fomrMixin from '../../../../assets/js/form_com'
  import _g from '../../../../assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        show1: false,
        typeOptions: [],
        categoryOptions: [],
        degreeOptions: [],
        keywordList: [],
        keyword: {
          category_id: '',
          title: '',
        },
        form: {
          category_id: '',
          title: '',
          content: '',
          degree: '',
          type: 2
        },
        rules: {
          category_id: [{required: true, message: '请选择问题类型', trigger: 'blur'}],
          title: [{required: true, message: '请输入标题', trigger: 'blur'}],
          content: [{required: true, message: '请输入问题描述', trigger: 'blur'}],
          degree: [{required: true, message: '请选择紧急程度', trigger: 'blur'}]
        }
      }
    },
    methods: {
      addHelps (form) {
        console.log(this.form)
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.apiPost('admin/helps', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                setTimeout(() => {
                  this.$router.push({ name: 'helpsList' })
                }, 1500)
              })
            })
          }
        })
      },
      change () {
        if(this.form.title === '') {
          this.show1 = false
          return
        }
        this.keyword = {
          category_id : this.form.category_id,
          word: this.form.title,
        }
        const data = {
          params: {
            keywords: this.keyword
          }
        }
        this.apiGet('help/new_ask_word', data).then((res) => {
          this.handelResponse(res, (data) => {
            if(data.list.length === 0) {
              this.show1 = false
              return
            }
            this.keywordList = data.list
            this.show1 = true
          })
        })
      },
//      获取问题类型
      getTypeOptions() {
        const data = {
          params: {
            keywords: {
              pid:  4
            }
          }
        }
        this.apiGet('admin/parameters',data).then((res) => {
          this.handelResponse(res, (data) => {
            this.typeOptions = data.list
          })
        })
      },
//      获取主要原因
      getCategories() {
        const data = {
          params: {
            keywords: {
              pid: this.form.category_id
            }
          }
        }
        this.apiGet('admin/parameters',data).then((res) => {
          this.handelResponse(res, (data) => {
            this.categoryOptions = data.list
          })
        })
      },
//      获取紧急程度
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
      getKeywordList() {
        this.$router.push({ name: 'helpsList', query: this.keyword })
      }
    },
    created() {
//      关闭全局loading
      _g.closeGlobalLoading()
      this.getTypeOptions();
      this.getDegreeList()
    },
    mixins: [http, fomrMixin],
    computed: {
//      发起文章按钮
      isAddArticle () {
        return _g.getHasRule('helps-save')
      },
    }
  }
</script>
<style>

</style>