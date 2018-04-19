<template>
  <div class="shot_list_detail">
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item :to="{ path: '/admin/assets/list' }">库管理</el-breadcrumb-item>
        <el-breadcrumb-item>资产库管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
  </div>
</template>
<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data () {
      return {

      }
    },
    methods: {
//      socket(){
//        var ws = new WebSocket("ws://localhost:8080");
//        console.log(ws,123)
////        let _this = this
//        ws.onopen = () => {
//          // Web Socket 已连接上，使用 send() 方法发送数据
//          ws.send('Holle')
//          console.log('数据发送中...')
//        }
//        ws.onmessage = evt => {
//           console.log('数据已接收...')
//        }
//        ws.onclose = function () {
//          // 关闭 websocket
//          console.log('连接已关闭...')
//        }
//      }
    },
    created () {

    },
    components: {},
    mixins: [http],
    computed: {}
  }
</script>
<style>
  .shot_list_detail .item {
    margin-right: 20px;
  }

  .shot_list_detail .el-card__body {
    padding: 15px;
  }

  .shot_list_detail .el-table {
    width: 95%;
  }

  .shot_list_detail .el-tabs {
    width: 95%;
  }

  .shot_list_detail .el-tag {
    padding: 0 8px;
    margin-bottom: 2px;
  }

  .shot_list_detail .shot_detail {
    width: 25%;
    height: 75%;
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    overflow: scroll;
  }

  .shot_list_detail .shot_detail p {
    font-size: .8rem;
    color: #666;
  }

  .shot_list_detail .shot_detail p span {
    color: #333;
  }

  .shot_list_detail .shot_detail p span.tache_studio {
    font-size: .75rem;
  }

  .shot_list_detail .shot_detail .el-card__header {
    padding: 10px 15px;
    font-size: 14px;
  }

  .shot_list_detail .shot_card h2 {
    font-size: .8rem;
    height: 35px;
    line-height: 35px;
    background: #fff;
    color: #666;
    border-left: 2px solid #409eff;
    padding-left: 10px;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }

  .shot_list_detail .text-Lens:after {
    content: '';
    height: 0;
    line-height: 0;
    display: block;
    visibility: hidden;
    clear: both;
  }

  .shot_list_detail .text-Lens p {
    margin: 0;
    display: inline-block;
    font-weight: 500;
  }

  .shot_list_detail .text-Lens .text-Lens-rank {
    top: 0;
    right: 0;
  }

  .shot_list_detail .text-Lens-assets {
    max-width: 70%;
    float: left;
  }

  .shot_list_detail .text-Lens-assets .el-tag {
    margin-bottom: 5px;
  }

  .shot_list_detail .text-Lens-time {
    width: 30%;
  }

  .shot_list_detail .text-Lens-time span {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    font-weight: normal;
  }

  .shot_list_detail .text-Lens-time .item {
    margin-right: 0;
    padding: 12px 4px;
    margin-left: 0;
    border-radius: 0;
    border: none;
    border-right: 1px solid;
    line-height: 0;
    font-size: 14px;
  }

  .shot_list_detail .text-Lens-link {
    text-align: right;
  }
</style>

