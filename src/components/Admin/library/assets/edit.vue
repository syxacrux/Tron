<template>
  <div class="assets_edit">
    <el-dialog title="编辑资产" :visible.sync="dialogFormVisible" width="75%" top="6vh">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="assets_add">
        <el-row :gutter="20">
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="项目名称:" prop="project_id">
                <el-select v-model="form.project_id" placeholder="请选择项目" @change="getFields">
                  <el-option v-for="item in projectList" :label="item.project_name" :value="item.id"
                             :key="item.project_name"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <el-form-item label="资产类型:" prop="field_id">
                <el-select v-model="form.field_id" placeholder="请选择资产类型" :class="{ 'w-130': addShow }">
                  <el-option v-for="item in fieldList" :label="item.name" :value="item.id"
                             :key="item.id"></el-option>
                </el-select>
                <el-button type="text" size="small" @click="isAddField = true" v-if="addShow">添加</el-button>
              </el-form-item>
            </div>
          </el-col>
        </el-row>>
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
                <el-select v-model="form.difficulty" placeholder="请选择资产难度" class="h-40 w-200">
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
              <el-form-item label="素材号:" prop="material_number">
                <el-input v-model.trim="form.material_number" class="h-40 w-200"></el-input>
              </el-form-item>
            </div>
          </el-col>
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
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="edit('form')" :loading="isLoading">确 定</el-button>
      </div>
    </el-dialog>
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
  </div>
</template>
<style>
  .assets_edit .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .assets_edit .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .assets_edit .el-upload {
    width: 300px;
  }

  .assets_edit .el-tag__close{
    display: none;
  }

  .assets_edit .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }

  .assets_edit .avatar {
    display: block;
    width: 100% !important;
    height: 100% !important;
  }
</style>
<script>
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  export default {
    data() {
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
        isLoading: false,
        dialogFormVisible: false,
        uploadImageUrl: window.HOST + '/admin/upload_image',
        projectList: [],
        studiosListArt: [],
        studiosListModel: [],
        studiosListMap: [],
        studiosListBind: [],
        studiosListTrack: [],
        studiosListAnimate: [],
        studiosListPaint: [],
        studiosListSpecial: [],
        studiosListLight: [],
        studiosListSynch: [],
        fieldList: [],
        fieldForm: {
          project_id: '',  //所属项目id
          name: '',   //场号/集号
          explain:'',//资产类型备注
          type:2
        },
        form: {
          project_id: '',    //所属项目id
          field_id: '',     //场号id
          asset_ids: '',    //资产id
          asset_image: '',    //资产缩略图地址
          asset_byname: '',    //资产简称
          asset_name: '',    //资产名称
          plan_start_timestamp: '',    //计划开始时间
          plan_end_timestamp: '',    //计划结束时间
          asset_explain: '',    //资产备注
          priority_level: "1",    //资产优先级
          difficulty: "1",    //资产难度
          material_number: '',    //素材号
          second_company: '',    //二级公司
          make_demand: '',    //制作要求
          status: 1    //资产状态
        },
        id: '',
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
            {required: true, message: '请输入资产简称'}, {pattern: /^[a-zA-Z]+$/, message: '资产简称必须为字母'}
          ],
          asset_name: [{required: true, message: '请输入资产名称'}, {pattern: /^[\u4E00-\u9FA5]+$/, message: '资产名称必须为汉字'}],
          difficulty: [{required: true, message: '请选择资产难度'}],
          priority_level: [{required: true, message: '请选择资产优先级'}]
        },
        addFieldRules: {
          project_id: [{required: true, message: '请选择项目'}],
          name: [{required: true, message: '请输入资产'},{pattern: /^[a-zA-Z]+$/, message: '资产类型必须为字母'}],
          explain: [{required: true, message: '请输入资产类型备注'}]
        },
        assetsDetail: {}
      }
    },
    methods: {
      open() {
        this.dialogFormVisible = true
      },
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
        if (this.fieldForm.project_id && this.fieldForm.name) {
          this.isLoading = !this.isLoading
          this.apiPost('admin/save_field', this.fieldForm).then((res) => {
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '添加成功')
              setTimeout(() => {
                this.isAddField = false
                this.getFields()
              }, 1500)
            }, () => {
              this.isLoading = !this.isLoading
            })
          })
        }
      },
//      改变环节选项任意复选框时执行方法
      changeTache() {
//        美术环节
        if(this.isArt){
          if(this.studiosListArt.length === 0){  //美术环节id为3
            this.apiGet(`assets/get_studio?assets_id=${this.id}&tache_id=3`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListArt = data.list
              })
            })
          }
          this.artOfStudio = this.artOfStudio
        }else{
          this.artOfStudio = []
        }
//        模型环节
        if(this.isModel){
          if(this.studiosListModel.length === 0){  //模型环节id为4
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=4`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListModel = data.list
              })
            })
          }
          this.modelOfStudio = this.modelOfStudio
        }else{
          this.modelOfStudio = []
        }
//        贴图环节
        if(this.isMap){
          if(this.studiosListMap.length === 0){  //贴图环节id为5
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=5`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListMap = data.list
              })
            })
          }
          this.mapOfStudio = this.mapOfStudio
        }else{
          this.mapOfStudio = []
        }
//        绑定环节
        if(this.isBind){
          if(this.studiosListBind.length === 0){  //绑定环节id为6
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=6`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListBind = data.list
              })
            })
          }
          this.bindOfStudio = this.bindOfStudio
        }else{
          this.bindOfStudio = []
        }
//        跟踪环节
        if(this.isTrack){
          if(this.studiosListTrack.length === 0){  //跟踪环节id为7
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=7`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListTrack = data.list
              })
            })
          }
          this.trackOfStudio = this.trackOfStudio
        }else{
          this.trackOfStudio = []
        }
//        动画环节
        if(this.isAni){
          if(this.studiosListAnimate.length === 0){  //动画环节id为8
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=8`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListAnimate = data.list
              })
            })
          }
          this.animateOfStudio = this.animateOfStudio
        }else{
          this.animateOfStudio = []
        }
//        数字绘景环节
        if(this.isPaint){
          if(this.studiosListPaint.length === 0){  //数字绘景环节id为9
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=9`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListPaint = data.list
              })
            })
          }
          this.paintOfStudio = this.paintOfStudio
        }else{
          this.paintOfStudio = []
        }
//        特效环节
        if(this.isSpec){
          if(this.studiosListSpecial.length === 0){  //特效环节id为10
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=10`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListSpecial = data.list
              })
            })
          }
          this.specialOfStudio = this.specialOfStudio
        }else{
          this.specialOfStudio = []
        }
//        灯光环节
        if(this.isLight){
          if(this.studiosListLight.length === 0){  //灯光环节id为11
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=11`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListLight = data.list
              })
            })
          }
          this.lightOfStudio = this.lightOfStudio
        }else{
          this.lightOfStudio = []
        }
//        合成环节
        if(this.isSynch){
          if(this.studiosListSynch.length === 0){  //灯光环节id为12
            this.apiGet(`asset/get_studio?assets_id=${this.id}&tache_id=12`).then((res) => {
              this.handelResponse(res, (data) => {
                this.studiosListSynch = data.list
              })
            })
          }
          this.synchOfStudio = this.synchOfStudio
        }else{
          this.synchOfStudio = []
        }
      },
//      编辑资产
      edit(form) {
        if (!this.form.asset_image) {
          _g.toastMsg('warning', '请插入资产缩略图')
          return
        }
        if (this.plan_time.length === 0) {
          _g.toastMsg('warning', '请输入计划起止时间')
          return
        }
//        必填项
        this.form.asset_image = this.form.asset_image.slice(this.form.asset_image.indexOf('uploads'))
        this.form.project_id = parseInt(this.form.project_id)
        this.form.field_id = parseInt(this.form.field_id)
        this.form.time = parseInt(this.form.time)
        this.form.ambient = parseInt(this.form.ambient)
//        this.form.is_parse = parseInt(this.form.is_parse)
        this.form.plan_start_timestamp = _g.j2time(this.plan_time[0])
        this.form.plan_end_timestamp = _g.j2time(this.plan_time[1])
//        选填项
        this.form.asset_ids = this.form.asset_ids ? this.form.asset_ids.join('') : ''
//        this.form.is_assets = this.form.is_assets ? parseInt(this.form.is_assets) : 2
        this.form.frame_range = this.frame_range1 && this.frame_range2 ? this.frame_range1 + ',' + this.frame_range2 : ''
        this.form.handle_frame = this.handle_frame1 && this.handle_frame2 ? this.handle_frame1 + ',' + this.handle_frame2 : ''
        this.form.priority_level = this.form.priority_level ? parseInt(this.form.priority_level) : 1
        this.form.difficulty = this.form.difficulty ? parseInt(this.form.difficulty) : 1

        this.form.tache = {
          3: this.artOfStudio,
          4: this.modelOfStudio,
          5: this.mapOfStudio,
          6: this.bindOfStudio,
          7: this.trackOfStudio,
          8: this.animateOfStudio,
          9: this.paintOfStudio,
          10: this.specialOfStudio,
          11: this.lightOfStudio,
          12: this.synchOfStudio
        }
        console.log(this.form)
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPut('admin/assets/', this.id, this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '编辑成功')
                this.$emit('updataShotDetail', this.id)
//                _g.clearVuex('setUsers')
                setTimeout(() => {
                  this.dialogFormVisible = false
                  this.isArt = this.isModel = this.isMap = this.isBind = this.isTrack = this.isAni = this.isPaint = this.isSpec = this.isLight = this.isSynch = false
                  this.studiosListArt = this.studiosListModel = this.studiosListMap = this.studiosListBind = this.studiosListTrack = this.studiosListAnimate = this.studiosListPaint = this.studiosListSpecial = this.studiosListLight = this.studiosListSynch =[]
                  this.artOfStudio = []
                  this.modelOfStudio = []
                  this.mapOfStudio = []
                  this.bindOfStudio = []
                  this.trackOfStudio = []
                  this.animateOfStudio = []
                  this.paintOfStudio = []
                  this.specialOfStudio = []
                  this.lightOfStudio = []
                  this.synchOfStudio = []
                  this.isLoading = !this.isLoading
                }, 1500)
              }, () => {
                this.isLoading = !this.isLoading
              })
            })
          }
        })
      },
//      获取项目列表
      getAllProjects() {
        return new Promise((resolve, reject) => {
          this.apiGet('admin/projects').then((res) => {
            this.handelResponse(res, (data) => {
              resolve(data.list)
            })
          })
        })
      },
//      获取所有资产类型
      getFields() {
        this.form.field_id = ''
        const data = {
          params: {
            project_id: this.form.project_id
          }
        }
        this.apiGet('admin/get_fields', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.fieldList = data
          })
        })
      },
      async getCompleteData() {
        this.projectList = await this.getAllProjects()
      }
    },
    created() {
      this.getCompleteData()
    },
    mixins: [http],
    props: ['message'],
    watch: {
      message: function(data, o) {
        this.assetsDetail = data
        this.id = data.id
        this.form.project_id = data.project_id
        this.getFields()
        this.form.field_id = data.field_id
        this.form.assets_byname = data.assets_byname
        this.form.assets_name = data.assets_name
        this.form.asset_image = this.image = window.baseUrl + '/' + data.asset_image
        this.plan_time = [new Date(data.plan_start_timestamp * 1000), new Date(data.plan_end_timestamp * 1000)]
        this.form.time = data.time.toString()
        this.form.ambient = data.ambient.toString()
        this.form.priority_level = data.priority_level.toString()
        this.form.difficulty = data.difficulty.toString()
        this.handle_frame1 = data.handle_frame.split(',')[0]
        this.handle_frame2 = data.handle_frame.split(',')[1]
        this.frame_range1 = data.frame_range.split(',')[0]
        this.frame_range2 = data.frame_range.split(',')[1]
        this.form.material_number = data.material_number
        this.form.asset_ids = data.asset_ids
        this.form.second_company = data.second_company
        this.form.assets_explain = data.assets_explain
        this.form.change_speed_info = data.change_speed_info
        this.form.make_demand = data.make_demand
      }
    },
    computed: {
//      添加场号按钮
      addShow() {
        return _g.getHasRule('assets-save_filed')
      },
    }
  }
</script>