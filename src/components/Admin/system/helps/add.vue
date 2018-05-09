<template>
  <div class="help_add">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/helps/list' }">问题反馈</el-breadcrumb-item>
        <el-breadcrumb-item>创建文章</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-l-50 m-t-30 w-900">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px">
        <el-form-item label="反馈类型：" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择问题类型">
            <el-option label="电脑" value="1"></el-option>
            <el-option label="服务器" value="2"></el-option>
            <el-option label="会议室" value="3"></el-option>
            <el-option label="其他" value="4"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="标题：" prop="title">
          <el-input v-model="form.title" class="h-40"></el-input>
        </el-form-item>
        <el-form-item label="反馈内容：" prop="content" class="h-500">
          <el-input
              type="textarea"
              placeholder="请输入反馈内容"
              v-model="form.content"
              >
          </el-input>
        </el-form-item>
        <el-form-item label="关键字：">
          <el-tag
              :key="tag"
              v-for="tag in dynamicTags"
              closable
              :disable-transitions="false"
              @close="handleClose(tag)">
            {{tag}}
          </el-tag>
          <el-input
              class="input-new-tag w-200"
              v-if="inputVisible"
              v-model="inputValue"
              ref="saveTagInput"
              size="small"
              @keyup.enter.native="handleInputConfirm"
              @blur="handleInputConfirm"
          >
          </el-input>
          <el-button v-else class="button-new-tag" size="small" @click="showInput">+ 添加</el-button>
        </el-form-item>
        <el-form-item label="备注" prop="explain">
          <el-input v-model.trim="form.explain" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="add('form')" :loading="isLoading">提交</el-button>
          <el-button @click="goback()">返回</el-button>
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
        form: {
          category_id: '',
          title: '',
          content: '',
          keywords: '',
          explain: '',
          type: 1
        },
        dynamicTags: [],
        inputVisible: false,
        inputValue: '',
        rules: {
          category_id: [
            {required: true, message: '请输入配置项名称', trigger: 'blur'}
          ],
          title: [
            {required: true, message: '请输入标题', trigger: 'blur'}
          ],
          content: [
            {required: true, message: '请输入反馈内容', trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      /*
       * 配置项添加
       * @params
       *   form:{
       *     category      配置项名称（string）
       *     pid           父级
       *     explain       备注（string）
       *   }
       * */
      add(form) {
        this.form.keywords = this.dynamicTags.join(',')
        console.log(this.form)
        this.$refs[form].validate((valid) => {
          if (valid) {
            this.isLoading = !this.isLoading
            this.apiPost('admin/helps', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                setTimeout(() => {
                  this.goback()
                }, 1500)
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
          }
        })
      },
      showInput() {
        this.inputVisible = true;
        this.$nextTick(_ => {
          this.$refs.saveTagInput.$refs.input.placeholder = '请按回车键确认';
          this.$refs.saveTagInput.$refs.input.focus();
        });
      },
      handleInputConfirm() {
        let inputValue = this.inputValue;
        if (inputValue) {
          this.dynamicTags.push(inputValue);
        }
        this.inputVisible = false;
        this.inputValue = '';
      },
      handleClose(tag) {
        this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
      },
//      获取父级分类
      getParameters() {
        this.apiGet('admin/parameters').then((res) => {
          this.handelResponse(res, (data) => {
            this.options = data.list
          })
        })
      },
      init() {
        this.getParameters()
      }
    },
    created() {
//      关闭全局loading
      _g.closeGlobalLoading()
      this.init()
    },
    mixins: [http, fomrMixin]
  }
</script>
<style>
  .help_add textarea {
    height: 500px;
  }
</style>