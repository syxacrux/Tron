<template>
  <div class="assets_add"> 
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/assets/list' }">资产管理</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/assets/list' }">资产汇总</el-breadcrumb-item>
        <el-breadcrumb-item>添加资产</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-r-30 m-b-20 m-t-30 tx-r">
      <el-button type="primary" size="small" plain v-if="importAddShow" @click="isImportAssets = true">批量导入</el-button>
      <el-button type="text" size="mini" class="fz-12 m-0">点击获取模板</el-button>
    </div>
    <div class="m-l-50 m-t-30 w-1000">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="assets_add">
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="项目名称:" prop="project_id">
                <el-select v-model="form.project_id" placeholder="请选择项目" @change="getFields">
                  <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                             :key="item.id"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产类型:" prop="field_id">
                <el-select v-model="form.field_id" placeholder="请选择资产类型" :class="{ 'w-130': add_assetsShow }">
                  <el-option v-for="item in fieldList" :label="item.explain" :value="item.id"
                             :key="item.id"></el-option>
                </el-select>
                <el-button type="text" size="small" @click="isAddField = true" v-if="add_assetsShow">添加</el-button>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item class="is-required" label="资产缩略图:" prop="image">
              <el-upload
                  class="avatar-uploader"
                  :action="uploadImageUrl"
                  :show-file-list="false"
                  :on-success="handleAvatarSuccess"
                  :before-upload="beforeAvatarUpload">
                <img v-if="form.asset_image" :src="image" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产简称:" prop="asset_byname">
                <el-input v-model.trim="form.asset_byname" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产名称:" prop="asset_name">
                <el-input v-model.trim="form.asset_name" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产难度:" prop="difficulty">
                <el-select v-model="form.difficulty" placeholder="请资产资产难度" class="h-40 w-200">
                  <el-option label="D" value="1"></el-option>
                  <el-option label="C" value="2"></el-option>
                  <el-option label="B" value="3"></el-option>
                  <el-option label="A" value="4"></el-option>
                  <el-option label="S" value="5"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="16">
            <div class="grid-content">
              <el-form-item label="计划起止时间:" prop="plan_time" class="is-required">
                <el-date-picker v-model="plan_time" type="datetimerange" range-separator="至" start-placeholder="计划开始时间"
                                end-placeholder="计划结束时间">
                </el-date-picker>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产优先级:" prop="priority_level">
                <el-select v-model="form.priority_level" placeholder="请选择资产优先级" class="h-40 w-200">
                  <el-option label="D" value="1"></el-option>
                  <el-option label="C" value="2"></el-option>
                  <el-option label="B" value="3"></el-option>
                  <el-option label="A" value="4"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="24">
            <div class="grid-content">
              <el-form-item label="环节:" prop="hahah" class="is-required">
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isArt" @change="changeTache">美术部</el-checkbox>
                  <el-select v-if="isArt" v-model="artOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isModel" @change="changeTache">模型部</el-checkbox>
                  <el-select v-if="isModel" v-model="modelOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isMap" @change="changeTache">贴图部</el-checkbox>
                  <el-select v-if="isMap" v-model="mapOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isBind" @change="changeTache">绑定部</el-checkbox>
                  <el-select v-if="isBind" v-model="bindOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isTrack" @change="changeTache">跟踪部</el-checkbox>
                  <el-select v-if="isTrack" v-model="trackOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isAni" @change="changeTache">动画部</el-checkbox>
                  <el-select v-if="isAni" v-model="animateOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isPaint" @change="changeTache">数字绘景部</el-checkbox>
                  <el-select v-if="isPaint" v-model="paintOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isSpec" @change="changeTache">特效部</el-checkbox>
                  <el-select v-if="isSpec" v-model="specialOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isLight" @change="changeTache">灯光部</el-checkbox>
                  <el-select v-if="isLight" v-model="lightOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
                <el-col :span="12" class="h-45">
                  <el-checkbox v-model="isSynch" @change="changeTache">合成部</el-checkbox>
                  <el-select v-if="isSynch" v-model="synchOfStudio" multiple collapse-tags style="margin-left: 20px;"
                             placeholder="请选择">
                    <el-option v-for="item1 in studiosList" :key="item1.id" :label="item1.name" :value="item1.id">
                    </el-option>
                  </el-select>
                </el-col>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="二级公司:" prop="second_company">
                <!--<el-select v-model="form.second_company" multiple collapse-tags placeholder="请选择二级公司"-->
                <!--class="h-40 w-200">-->
                <!--<el-option label="我是二级公司1" value="1"></el-option>-->
                <!--<el-option label="我是二级公司2" value="2"></el-option>-->
                <!--</el-select>-->
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产备注:" prop="asset_explain">
                <el-input type="textarea" :rows="3" placeholder="请输入资产备注" v-model="form.asset_explain"
                          class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="制作要求:" prop="make_demand">
                <el-input type="textarea" :rows="3" placeholder="请输入制作要求" v-model="form.make_demand"
                          class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-form-item>
            <el-button type="primary" @click="add_assets" :loading="isLoading">提交</el-button>
            <el-button @click="goback()">返回</el-button>
          </el-form-item>
        </el-row>
      </el-form>
    </div>
    <el-dialog title="添加资产类型" :visible.sync="isAddField" width="30%" center>
      <el-form :model="fieldForm" :rules="addFieldRules" label-width="130px">
        <el-form-item label="项目名称：" prop="project_id">
          <el-select v-model="fieldForm.project_id" placeholder="请选择项目" class="w-200">
            <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                       :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="资产类型：" prop="name">
          <el-input v-model.trim="fieldForm.name" class="w-200"></el-input>
        </el-form-item>
        <el-form-item label="资产类型备注：" prop="explain">
          <el-input v-model.trim="fieldForm.explain" class="w-200"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="isAddField = false">取 消</el-button>
        <el-button type="primary" @click="addField(fieldForm)">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog title="批量导入资产" :visible.sync="isImportAssets" width="30%" center>
      <el-form :model="importForm" :rules="importShotRules" label-width="130px">
        <el-form-item label="项目名称：" prop="project_id">
          <el-select v-model="importForm.project_id" placeholder="请选择项目" class="w-200">
            <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                       :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <!--<el-form-item label="场号/集号：" prop="name">-->
          <!--<el-input v-model.trim="importForm.name" class="w-200"></el-input>-->
        <!--</el-form-item>-->
        <el-form-item label="资产文件：" prop="assets_file">
          <el-upload class="upload_assetsFile" ref="upload"
                     action="https://jsonplaceholder.typicode.com/posts/"
                     :on-preview="handlePreview"
                     :file-list="fileList"
                     :limit="1"
                     :auto-upload="false">
            <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
            <div slot="tip" class="el-upload__tip">只能上传一个xls/xlsx格式文件</div>
          </el-upload>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="isImportAssets = false">取 消</el-button>
        <el-button type="primary" @click="importShot(importForm)">确 认</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<style type="text/css">
  .assets_add .form-checkbox:first-child {
    margin-left: 15px;
  }

  .assets_add .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .assets_add .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .assets_add .el-upload {
    width: 300px;
  }

  .assets_add .upload_assetsFile .el-upload{
    text-align: left;
  }

  .assets_add .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }

  .assets_add .avatar {
    /*width: 178px;*/
    /*height: 178px;*/
    display: block;
    width: 100% !important;
    height: 100% !important;
  }

  .assets_add .assets_add_add .el-form-item {
    display: inline-block;
  }

  .assets_add .el-transfer-panel {
    width: 150px;
  }

  .assets_add .el-transfer__buttons {
    padding: 0 15px;
  }

  .assets_add .el-textarea__inner {
    resize: none;
  }

  .assets_add .form_studio_id {
    width: 350px;
  }

  .assets_add .block {
    /* margin-top: 20px; */
  }
</style>
<script>
  import fomrMixin from '@/assets/js/form_com'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  import axios from 'axios'

  export default {
    data() {
      let assetsNumber = (rule, value, callback) => {
        console.log(this.form.field_id)
        console.log(value)
        const data = {
          params: {
            field_id: parseInt(this.form.field_id),
            asset_byname: value
          }
        }
        if(value == ''){
          callback(new Error('请输入资产简称'));
        }else{
          axios.get('asset/check_byname', data).then((res) => {
            console.log(res.data.code, 'suc')
            if (res.data.code === 400) {
              callback(new Error(res.data.error));
            }else{
              callback()
            }
          })
        }
      };
      return {
        isArt: false,
        isModel: false,
        isMap: false,
        isBind: false,
        isTrack: false,
        isAni: false,
        isPaint: false,
        isSpec: false,
        isLight: false,
        isSynch: false,
        artOfStudio: [],
        modelOfStudio: [],
        mapOfStudio: [],
        bindOfStudio: [],
        trackOfStudio: [],
        animateOfStudio: [],
        paintOfStudio: [],
        specialOfStudio: [],
        lightOfStudio: [],
        synchOfStudio: [],
        isAddField: false,
        isImportAssets: false,
        isLoading: false,
        uploadImageUrl: window.HOST + '/admin/upload_image',
        projectList: [],
        studiosList: [],
        fieldList: [],
        importForm: {
          project_id: '',  //所属项目id
          assets_file: ''    //导入资产文件
//          name: ''    //场号/集号
        },
        fileList: [],
        fieldForm: {
          project_id: '',  //所属项目id
          name: '',   //场号/集号
          explain:'',//资产类型备注
          type:2
        },
        form: {
          project_id: '',    //所属项目id
          field_id: '',     //类型id
          asset_image: '',    //资产缩略图地址
          asset_byname: '',    //资产简称
          asset_name: '',    //资产名称
          plan_start_timestamp: '',    //计划开始时间
          plan_end_timestamp: '',    //计划结束时间
          asset_explain: '',    //资产备注
          priority_level: "1",    //资产优先级
          difficulty: "1",    //资产难度
          // material_number: '',    //素材号
          second_company: '',    //二级公司
          make_demand: '',    //制作要求
          status: 1    //资产状态
        },
        image: '',
        plan_time: '',
        frame_range1: '',
        frame_range2: '',
        handle_frame1: '',
        handle_frame2: '',
        rules: {
          project_id: [{required: true, message: '请选择项目'}],
          field_id: [{required: true, message: '请选择场号'}],
          asset_image: [{required: true, message: '请插入资产缩略图'}],
          asset_byname: [
            {required: true, message: '请输入资产简称'}, {pattern: /^[a-zA-Z]+$/, message: '资产简称必须为字母'},{
            pattern: /^[a-zA-Z]+$/,
            validator: assetsNumber
          }],
          asset_name: [{required: true, message: '请输入资产名称'}, {pattern: /^[\u4E00-\u9FA5]+$/, message: '资产名称必须为汉字'}],
          difficulty: [{required: true, message: '请选择资产难度'}],
          priority_level: [{required: true, message: '请选择资产优先级'}]
        },
        addFieldRules: {
          project_id: [{required: true, message: '请选择项目'}],
          name: [{required: true, message: '请输入资产'},{pattern: /^[a-zA-Z]+$/, message: '资产类型必须为字母'}],
          explain: [{required: true, message: '请输入资产类型备注'}]
        },
        importShotRules: {
          project_id: [{required: true, message: '请选择项目'}],
          assets_file: [{required: true, message: '请输入资产'}]
//          name: [{required: true, message: '请输入场号/集号'}]
        }
      }
    },
    methods: {
      handleAvatarSuccess(res, file) {
        this.image = URL.createObjectURL(file.raw);
        this.form.asset_image = res.data;
      },
      beforeAvatarUpload(file) {
        //  const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 0.195;
        //  if (!isJPG) {
        //    this.$message.error('上传图片只能是 JPG 格式!');
        //  }
        if (!isLt2M) {
          this.$message.error('上传图片大小不能超过 200KB!');
        }
        return isLt2M;
      },
//      添加资产类型
      addField(form) {
        if (this.fieldForm.project_id && this.fieldForm.name && this.fieldForm.explain) {
          this.isLoading = !this.isLoading
          this.apiPost('admin/save_field', this.fieldForm).then((res) => {
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '添加成功')
              setTimeout(() => {
                this.isAddField = false
                this.getFields()
              }, 1500)
              this.isLoading = !this.isLoading
            }, () => {
              this.isLoading = !this.isLoading
            })
          })
        }
      },
      handlePreview(file) {
        console.log(file);
      },
//      批量导入资产
      importShot() {
        console.log(this.$refs.upload.uploadFiles)

//        this.$refs.upload.submit();
      },
//      改变环节选项中的任意复选框时执行方法
      changeTache() {
        this.artOfStudio = this.isArt ? this.artOfStudio : []
        this.modelOfStudio = this.isModel ? this.modelOfStudio : []
        this.mapOfStudio = this.isMap ? this.mapOfStudio : []
        this.bindOfStudio = this.isBind ? this.bindOfStudio : []
        this.trackOfStudio = this.isTrack ? this.trackOfStudio : []
        this.animateOfStudio = this.isAni ? this.animateOfStudio : []
        this.paintOfStudio = this.isPaint ? this.paintOfStudio : []
        this.specialOfStudio = this.isSpec ? this.specialOfStudio : []
        this.lightOfStudio = this.isLight ? this.lightOfStudio : []
        this.synchOfStudio = this.isSynch ? this.synchOfStudio : []
      },
//      添加资产
      add_assets(form) {
        // if (
        //   this.artOfStudio.length === 0 && this.modelOfStudio.length === 0 && this.mapOfStudio.length === 0 &&
        //   this.bindOfStudio.length === 0 && this.trackOfStudio.length === 0 && this.animateOfStudio.length === 0 &&
        //   this.paintOfStudio.length === 0 && this.specialOfStudio.length === 0 && this.lightOfStudio.length === 0 &&
        //   this.synchOfStudio.length === 0
        // ) {
        //   _g.toastMsg('warning', '请选择至少一个环节所属工作室')
        //   return
        // }

        let obj = {}
        if(this.isArt) {
          obj[3] = this.artOfStudio
        }
        if(this.isModel) {
          obj[4] = this.modelOfStudio
        }
        if(this.isMap) {
          obj[5] = this.mapOfStudio
        }
        if(this.isBind) {
          obj[6] = this.bindOfStudio
        }
        if(this.isTrack) {
          obj[7] = this.trackOfStudio
        }
        if(this.isAni) {
          obj[8] = this.animateOfStudio
        }
        if(this.isPaint) {
          obj[9] = this.paintOfStudio
        }
        if(this.isSpec) {
          obj[10] = this.specialOfStudio
        }
        if(this.isLight) {
          obj[11] = this.lightOfStudio
        }
        if(this.isSynch) {
          obj[12] = this.synchOfStudio
        }
        this.form.tache = obj
        if (!this.form.asset_image) {
          _g.toastMsg('warning', '请插入资产缩略图')
          return
        }
        if (this.plan_time.length === 0) {
          _g.toastMsg('warning', '请输入计划起止时间')
          return
        }
//        必填项
        this.form.project_id = parseInt(this.form.project_id)
        this.form.field_id = parseInt(this.form.field_id)
        this.form.time = parseInt(this.form.time)
        this.form.ambient = parseInt(this.form.ambient)
//        this.form.is_parse = parseInt(this.form.is_parse)
        this.form.plan_start_timestamp = _g.j2time(this.plan_time[0])
        this.form.plan_end_timestamp = _g.j2time(this.plan_time[1])
        this.form.priority_level = this.form.priority_level ? parseInt(this.form.priority_level) : 1
        this.form.difficulty = this.form.difficulty ? parseInt(this.form.difficulty) : 1
//        选填项
        // this.form.asset_ids = this.form.asset_ids.join('')
        this.form.frame_range = this.frame_range1 && this.frame_range2 ? this.frame_range1 + ',' + this.frame_range2 : ''
        this.form.handle_frame = this.handle_frame1 && this.handle_frame2 ? this.handle_frame1 + ',' + this.handle_frame2 : ''
        this.form.clip_frame_length = this.form.clip_frame_length ? parseInt(this.form.clip_frame_length) : 0
        this.form.material_frame_length = this.form.material_frame_length ? parseInt(this.form.material_frame_length) : ''
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.apiPost('admin/assets', this.form).then((res) => {
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
//			获取所有工作室
      getAllStudios() {
        this.apiGet('asset/get_studio').then((res) => {
          this.handelResponse(res, (data) => {
            this.studiosList = data.list
          })
        })
      },
//      获取所有项目
      getProjects() {
        this.apiGet('admin/projects').then((res) => {
          this.handelResponse(res, (data) => {
            this.projectList = data.list
          })
        })
      },
//      获取所有资产类型
      getFields() {
        this.form.field_id = ''
        const data = {
          params: {
            project_id: this.form.project_id,
            type:2
          }
        }
        this.apiGet('admin/get_fields', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.fieldList = data
          })
        })
      }
    },
    created() {
      this.getProjects()
      this.getAllStudios()
    },
    mixins: [http, fomrMixin],
    computed: {
//      添加资产按钮
      add_assetsShow() {
        return _g.getHasRule('assets-save_filed')
      },
//      批量导入资产按钮
      importAddShow() {
        return _g.getHasRule('assets_import')
      }
    }
  }
</script>