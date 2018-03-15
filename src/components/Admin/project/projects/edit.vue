<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/home' }">项目</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/home/projects/list' }">项目管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加项目</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-l-50 m-t-30 w-1000">
      <el-form ref="form" :model="form" :rules="rules" label-width="130px">
        <el-form-item label="项目名称:" prop="project_name">
          <el-input v-model.trim="form.project_name" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item label="项目简称:" prop="project_byname">
          <el-input v-model.trim="form.project_byname" class="h-40 w-200"></el-input>
        </el-form-item>
        <el-form-item class="tsf_ProjectMap" label="项目缩略图:" prop="project_image">
          <!-- 上传照片地址 action="" -->
          <el-upload
              class="avatar-uploader"
              :action="uploadImageUrl"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload">
            <img v-if="form.project_image" :src="form.project_image" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
          </el-upload>
        </el-form-item>
        <!-- <el-form-item label="总时长:" prop="duration">
          <el-input v-model.trim="form.duration" class="h-40 w-200"></el-input>
        </el-form-item> -->
        <el-form-item label="遮幅比:" prop="aspect_ratio">
          <el-select v-model="form.aspect_ratio" placeholder="请选择">
            <el-option label="16：9" value="1"></el-option>
            <el-option label="10：5" value="2"></el-option>
            <el-option label="1：1" value="3"></el-option>
          </el-select>
        </el-form-item>
        <!-- <el-form-item label="总帧长:" prop="frame_sum">
          <el-input v-model.trim="form.frame_sum" class="h-40 w-200"></el-input>
        </el-form-item> -->
        <el-form-item label="分辨率:" prop="resolutic">
          <el-select v-model="form.resolutic" placeholder="请选择">
            <el-option label="1024*768" value="1"></el-option>
            <el-option label="2560*1680" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="手柄帧:" prop="handle_frame">
          <el-input v-model.trim="form.handle_frame.handle_frame1" class="h-40" style="width: 100px;"></el-input>
          <el-input v-model.trim="form.handle_frame.handle_frame2" class="h-40" style="width: 100px;"></el-input>
        </el-form-item>
        <el-form-item label="项目帧率:" prop="frame_rate">
          <el-select v-model="form.frame_rate" placeholder="请选择">
            <el-option label="24fps" value="1"></el-option>
            <el-option label="25fps" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="计划起止时间:" prop="plan_time">
          <el-date-picker
              v-model="plan_time"
              type="datetimerange"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>
        <!--<el-form-item label="所属工作室:" prop="studio_id">-->
        <!--<el-select v-model="form.studio_id" multiple placeholder="请选择">-->
        <!--<el-option v-for="item in studioOptions" :key="item.id" :label="item.studio_names"-->
        <!--:value="item.id"></el-option>-->
        <!--</el-select>-->
        <!--</el-form-item>-->
        <el-form-item label="所属工作室:" prop="studio_ids">
          <el-select :span="8" v-model="form.studio_ids" multiple placeholder="可多选" class="ts-studio_id">
            <el-option v-for="item in studiosOptions" :key="item.id" :label="item.studio_name" :value="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="现场指导:" prop="scene_director">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入拼音"
              v-model="scene_director"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="负责制片:" prop="producer">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="producer"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="现场制片:" prop="scene_producer">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="scene_producer"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="视效总监:" prop="visual_effects_boss">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="visual_effects_boss"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="视效总制片:" prop="visual_effects_producer">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="visual_effects_producer"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="二级公司制片:" prop="second_company_producer">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="second_company_producer"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="内部协调制片:" prop="inside_coordinate">
          <el-transfer
              :titles="['请选择', '已选择']"
              filterable
              :filter-method="filterMethod"
              filter-placeholder="请输入城市拼音"
              v-model="inside_coordinate"
              :data="userList">
          </el-transfer>
        </el-form-item>
        <el-form-item label="项目说明:" prop="project_explain">
          <el-input
              type="textarea"
              :rows="2"
              v-model.trim="form.project_explain"
              class="h-40 w-400">
          </el-input>
        </el-form-item>
        <el-form-item class="tsf_ProjectMap">
          <el-button type="primary" @click="add('form')" :loading="isLoading">提交</el-button>
          <el-button @click="goback()">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<style type="text/css">
  .project-div .el-form-item {
    display: inline-block;
  }

  .project-div .tsf_ProjectMap {
    display: block;
  }

  .form-checkbox:first-child {
    margin-left: 15px;
  }

  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }

  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }

  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }

  .block {
    /* margin-top: 20px; */
  }
</style>
<script>
  import fomrMixin from '@/assets/js/form_com'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        isLoading: false,
        id: null,
        uploadImageUrl: window.HOST + '/admin/upload_project_image',
        userList: [],
        studiosOptions: [],
        plan_time: [],
        form: {
          project_name: "", //项目名称
          project_byname: '', //项目简称
          project_image: '', //项目缩略图
          status: 0, //项目状态
//          duration: '',//总时长
          aspect_ratio: '',//遮幅比
//          frame_sum: '', //总帧长
          resolutic: '',//分辨率
          handle_frame: {
            handle_frame1: '',//手柄帧1
            handle_frame2: '',//手柄帧2
          },
//          screenings_count: "0",//场次数量
//          lens_count: "0",//镜头数量
//          task_count: "0",//任务数量
          frame_rate: '',//项目帧率
          plan_start_timestamp: "",//计划起止时间
          plan_end_timestamp: "",
          studio_ids: [], //所属工作室
          scene_director: '',//现场指导
          producer: '',//负责制片
          scene_producer: '',//现场制片
          visual_effects_boss: '',//视效总监
          visual_effects_producer: '',//视效总制片
          second_company_producer: '',//二级公司制片
          inside_coordinate: '',//内部协调制片
          project_explain: '',//项目说明
        },
        scene_director: [],//现场指导
        producer: [],//负责制片
        scene_producer: [],//现场制片
        visual_effects_boss: [],//视效总监
        visual_effects_producer: [],//视效总制片
        second_company_producer: [],//二级公司制片
        inside_coordinate: [],//内部协调制片
        imageUrl: '',
        filterMethod(query, item) {
          return item.pinyin.indexOf(query) > -1;
        },
        rules: {
          project_name: [
            {required: true, message: '请输入项目名称'}
          ],
          project_byname: [
            {required: true, message: '请输入项目简称'}
          ],
          project_image: [
            {required: true, message: '请输入项目缩略图'}
          ],
          duration: [
            {required: true, message: '请输入总时长'}
          ],
          aspect_ratio: [
            {required: true, message: '请输入遮幅比'}
          ],
          frame_sum: [
            {required: true, message: '请输入总帧长'}
          ],
          resolutic: [
            {required: true, message: '请输入分辨率'}
          ],
          // handle_frame: [
          //   {required: true, message: '请输入手柄帧'}
          // ],
          frame_rate: [
            {required: true, message: '请选择项目帧率'}
          ],
          // plan_time: [
          //   {required: true, message: '请选择计划起止时间'}
          // ],
          studio_ids: [
            {required: true, message: '请选择所属工作室'}
          ],
//          guide: [
//            {required: true, message: '请选择现场指导'}
//          ],
//          charge_producer: [
//            {required: true, message: '请选择负责制片'}
//          ],
//          scene_producer: [
//            {required: true, message: '请选择现场制片'}
//          ],
//          vision_major: [
//            {required: true, message: '请选择视效总监'}
//          ],
//          vision_producer: [
//            {required: true, message: '请选择视效总制片'}
//          ],
//          second_company_producer: [
//            {required: true, message: '请选择二级公司制片'}
//          ],
//          internal_coordination_producer: [
//            {required: true, message: '请选择内部协调制片'}
//          ],
        }
      }
    },
    methods: {
      handleAvatarSuccess(res, file) {
        this.imageUrl = URL.createObjectURL(file.raw);
        console.log(this.imageUrl)
      },
      beforeAvatarUpload(file) {
        const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 2;

        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 2MB!');
        }
        return isJPG && isLt2M;
      },
      add(form) {
        // if (!this.selectStudiosCheckbox()) {
        //   _g.toastMsg('warning', '请选择至少一个工作室')
        //   return
        // }
        // if (!this.selectTachesCheckbox()) {
        //   _g.toastMsg('warning', '请选择至少一个环节')
        //   return
        // }
        // console.log(form)
        this.form.handle_frame = this.form.handle_frame.handle_frame1 + ',' + this.form.handle_frame.handle_frame2
        this.form.resolutic = parseInt(this.form.resolutic)
        this.form.frame_rate = parseInt(this.form.frame_rate)
        this.form.aspect_ratio = parseInt(this.form.aspect_ratio)
        this.form.studio_ids = this.form.studio_ids.join(',')
        this.form.scene_director = this.scene_director.join(',')
        this.form.producer = this.producer.join(',')
        this.form.scene_producer = this.scene_producer.join(',')
        this.form.visual_effects_boss = this.visual_effects_boss.join(',')
        this.form.visual_effects_producer = this.visual_effects_producer.join(',')
        this.form.second_company_producer = this.second_company_producer.join(',')
        this.form.inside_coordinate = this.inside_coordinate.join(',')
        this.form.plan_start_timestamp = _g.j2time(this.plan_time[0])
        this.form.plan_end_timestamp = _g.j2time(this.plan_time[1])

        this.$refs.form.validate((pass) => {
          if (pass) {
            this.isLoading = !this.isLoading
            this.apiPost('admin/projects', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '添加成功')
                _g.clearVuex('setUsers')
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
      //获取工作室
      getAllStudios() {
        this.apiGet('admin/studios?type=tree').then((res) => {
          this.handelResponse(res, (data) => {
            console.log(data.list)
            this.studiosOptions = data.list
            // this.nodes = this.nodes.concat(data)
          })
        })
      },
      getAllUsers() {
        this.loading = true
        this.apiGet('admin/users').then((res) => {
          this.handelResponse(res, (data) => {
            const temp = [];
            data.list.forEach((name, index) => {
              temp.push({
                label: name.realname,
                key: name.id,
                pinyin: name.username
              });
            });
            this.userList = temp
          })
        })
      },

      getCompleteData() {
        //   this.apiGet('admin/project/' + this.id).then((res) => {
        //     this.handelResponse(res, (data) => {
        //       console.log(data)
        this.form.project_name='1'
        //       this.form.project_byname=data.project_byname
        //       this.form.project_image=data.project_image
        //       this.form.status=data.status
        //       this.form.aspect_ratio=data.aspect_ratio

        //     })
        //   })
      }
    },
    created() {
      this.id = this.$route.params.id
      this.getAllStudios()
      this.getAllUsers()
      this.getCompleteData()
    },
    mixins: [http, fomrMixin]
  }
</script>