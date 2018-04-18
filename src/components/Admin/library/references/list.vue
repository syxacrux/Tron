<template>
  <div class="shot_list">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/references/list' }">库管理</el-breadcrumb-item>
        <el-breadcrumb-item>参考库管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 pos-rel">
      <router-link class="btn-link-large add-btn" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加资料
      </router-link>
    </div>
    <div class="ovf-hd">

    </div>
  </div>
</template>
<script>

  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {}
    },
    methods: {

    },
    components: {

    },
    mixins: [http],
    computed: {

    }
  }
</script>
<style>

</style>

