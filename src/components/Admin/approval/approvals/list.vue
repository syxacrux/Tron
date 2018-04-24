<template>
  <div class="approvals_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/approvals/list' }">审批管理</el-breadcrumb-item>
        <el-breadcrumb-item>dailies管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 ovf-hd">
      <div class="fl w-1500">
        <!-- <template> -->
        <el-row :gutter="10" class="m-b-5">
          <el-col :span="4">
            <el-select v-model="search.project_id" placeholder="请选择项目" @change="screenChange(1)">
              <el-option
                v-for="item in screeningProject"
                :key="item.id"
                :label="item.project_name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="4">
            <el-select v-model="search.type"  style="margin-left: 10px;" placeholder="请选择类型" @change="screenChange(2)">
              <el-option label="镜头" value="1"></el-option>
              <el-option label="资产" value="2"></el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.field_id"  style="margin-left: 10px;" placeholder="请选择场号或资产类型" @change="screenChange(3)">
              <el-option
                v-for="item in screeningSite"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="5">
            <el-select v-model="search.shot_id"  style="margin-left: 10px;" placeholder="请选择镜头号或资产简称" @change="screenChange()">
              <el-option
                v-for="item in screeningShot"
                :key="item.id"
                :label="item.shot_number"
                :value="item.id">
              </el-option>
            </el-select>
          </el-col>
        </el-row> 
        <!-- </template> -->
      </div>
    </div>
    <div>
      <el-row>
        <el-col :span="14">
            <el-col :span="6">
                <div class="grid-content p-b-5">
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
            <el-col :span="6">
                <div class="grid-content p-b-5">
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
            <el-col :span="6">
                <div class="grid-content p-b-5">
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
            <el-col :span="6">
                <div class="grid-content p-b-5">
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
            <el-col :span="6">
                <div class="grid-content p-b-5">
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
        </el-col>
        <el-col :span="10">
            <div class="imagebox" id="signx">
				<img id="video" src="/Users/tengshifei/code/tron/uploads/Projects/images/20180409/1b8aec24c2d643f471c3860180fb4a4f.jpg" >
		    </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'
  import $ from 'jquery'
//   import sign from '@/assets/js/jquery-sign.js'

   export default {
       data () {
        return{
          screeningProject:[],//项目下拉列表数据
        // screeningType:[],//类型
          screeningSite:[],//场号下拉列表数据
          screeningShot:[],
          search:{
            project_id:'',//项目下拉框选中的值
            type:'',//类型
            field_id:'',//场号下拉框选中的值
            shot_id:'',//镜头选中的值
            shot_number:''//输入狂的值
          },
        } 
       },
       created() {
           console.log($)
       }
   }
</script>
<style>
.imagebox{
	width:800px;
	height:auto;
}
.imagebox img{
	width:100%;
}
</style>

