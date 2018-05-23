<template>
  <div class="help_index">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>问题反馈</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel tx-r">
      <el-tooltip class="item" effect="dark" content="列表" placement="bottom">
        <router-link to="list" class="list_btn m-r-10">
          <img src="@/assets/images/helps/help_list.png">
        </router-link>
      </el-tooltip>
      <el-tooltip class="item" effect="dark" content="发起文章" placement="bottom">
        <router-link to="add_article" class="add_article m-r-10" v-if="isAddArticle">
          <i class="el-icon-plus"></i>
        </router-link>
      </el-tooltip>
    </div>
    <div class="w-700 help_question">
      <h2>提&nbsp;&nbsp;问</h2>
      <el-form ref="form" :model="form" label-width="120px" :rules="rules">
        <el-form-item label="标题" prop="title">
          <el-input v-model="form.title" @input="change" placeholder="请输入标题"></el-input>
          <transition name="el-zoom-in-top">
            <div v-show="show1" class="transition-box">
              <ul>
                <li v-for="item in keywordList">
                  <router-link :to="{ name: 'helpsResolve', params: {id: item.id} }">
                    {{ item.title }}
                  </router-link>
                  <p class="fz-12 space_nowr">
                    {{ item.content }}
                  </p>
                </li>
                <div @click="getKeywordList">查看所有相关...</div>
              </ul>
            </div>
          </transition>
        </el-form-item>
        <el-form-item label="问题类型" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择问题类型" @change="getCategories">
            <el-option v-for="item in typeOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <!--<el-form-item label="不知道" prop="category_id">-->
          <!--<el-select v-model="form.category_id" placeholder="请选择buzhdiao">-->
            <!--<el-option v-for="item in categoryOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>-->
          <!--</el-select>-->
        <!--</el-form-item>-->
        <el-form-item label="紧急程度" prop="degree">
          <el-select v-model="form.degree" placeholder="请选择紧急程度">
            <el-option v-for="item in degreeOptions" :label="item.category" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="问题描述" prop="content">
          <el-input
              type="textarea"
              placeholder="请输入问题内容"
              v-model="form.content">
          </el-input>
        </el-form-item>
        <el-button type="primary" @click="addHelps(form)">提 交</el-button>
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
<style lang="less">
  .help_index{
    .add_article {
      border: 2px solid #000;
      border-radius: 5px;
      color: #000;
      font-size: 14px;
      padding: 2px;
    }
    .list_btn{
      display: inline-block;
      width: 25px;
      height: 25px;
      img{
        width: 100%;
        vertical-align: middle;
      }
    }
    .help_question {
      margin: 100px auto;
      border: 1px solid #c2c2c3;
      h2{
        margin: 0;
        height: 70px;
        line-height: 70px;
        text-align: center;
        font-weight: normal;
        color: #77787b;
        background: #eef1f7;
        border-bottom: 1px solid #c2c2c3;
      }
      .el-form-item {
        /*height: 50px;*/
        margin-bottom: 0;
        border-bottom: 1px solid #c2c2c3;
        .transition-box {
          position: absolute;
          top: 50px;
          left: 0;
          width: 100%;
          background: #fff;
          z-index: 1;
          ul {
            border: 1px solid #c2c2c3;
            li {
              padding: 5px 10px;
              border-radius: 8px;
              a{
                display: block;
                line-height: 20px;
                color: #333;

              }
              p {
                height: 25px;
                line-height: 30px;
                margin: 0;
                color: #666;
              };
              &:hover {
                a{
                  text-decoration: underline;
                }
                background: #eef1f7;
              }
            }
            div {
              padding: 0 20px;
              text-align: right;
              line-height: 30px;
              background: #eef1f7;
              color: #333;
              &:hover {
                text-decoration: underline;
                cursor: pointer;
              }
            }
          }
        }
        &:nth-child(2n+1) {
          background: #fff;
        }
        &:nth-child(2n) {
          background: #eef1f7;
        }
        &:last-of-type {
          /*height: 135px;*/
          .el-form-item__label {
            line-height: 115px;
          }
          .el-form-item__content {
            .el-textarea {
              height: 135px;
              .el-textarea__inner {
                height: 100%;
                resize: none;
                border: none;
                outline: none;
                background: transparent;
                font-size: 14px;
              }
            }
          }
        }
        .el-form-item__label {
          line-height: 30px;
          margin: 10px 0;
          border-right: 1px solid #c2c2c3;
          box-sizing: border-box;
        }
        .el-form-item__content {
          .el-input {
            height: 50px;
            .el-input__inner {
              height: 100%;
              border: none;
              outline: none;
              font-size: 14px;
            }
          }
          .el-select {
            width: 100%;
            .el-input__inner {
              background: transparent;
            }
          }
          .el-form-item__error {
            position: relative;
            /*left: initial;*/
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
          }
        }
      }
      .el-button {
        width: 100%;
        font-size: 16px;
        background: #9db0d1;
        border-color: #9db0d1;
        border-radius: 0;
      }
    }
  }

</style>