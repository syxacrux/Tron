<template>
  <div>
    <div class="m-b-20">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>成员管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="m-b-20 ovf-hd">
      <div class="fl" v-if="addShow">
        <router-link class="btn-link-large add-btn" to="add">
          <i class="el-icon-plus"></i>&nbsp;&nbsp;添加成员
        </router-link>
      </div>
      <div class="fl w-200" :class="{'m-l-30':addShow}">
        <el-input placeholder="请输入成员" v-model="keywords.name">
          <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
        </el-input>
      </div>
    </div>
    <el-table v-if="listShow" :data="tableData" style="width: 100%" @selection-change="selectItem">
      <el-table-column type="expand">
        <template slot-scope="props">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="所属工作室">
              <span>{{ props.row.studio_name }}</span>
            </el-form-item>
            <el-form-item label="所属环节">
              <span>{{ props.row.tache_name }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <!--<el-table-column type="selection" width="50"></el-table-column>-->
      <el-table-column label="成员姓名" prop="realname"></el-table-column>
      <el-table-column label="用户名" prop="username"></el-table-column>
      <el-table-column label="角色" prop="role_name"></el-table-column>
      <el-table-column label="操作" width="200" v-if="editShow || deleteShow">
        <template slot-scope="scope">
          <div>
            <span v-if="editShow">
              <router-link :to="{ name: 'usersEdit', params: { id: scope.row.id }}">
                <el-button size="small" type="primary">编辑</el-button>
              </router-link>
            </span>
            <span v-if="deleteShow">
              <el-button size="small" type="danger" @click="confirmDelete(scope.row)">删除</el-button>
            </span>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="pos-rel p-t-20">
      <!--<btnGroup :selectedData="multipleSelection" :type="'users'"></btnGroup>-->
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
  </div>
</template>

<script>
  import btnGroup from '../../../Common/btn-group.vue'
  import http from '../../../../assets/js/http'
  import _g from '@/assets/js/global'

  export default {
    data() {
      return {
        tableData: [],
        dataCount: null,
        currentPage: 1,
        keywords: {
          name: ''
        },
        multipleSelection: [],
        limit: 10
      }
    },
    methods: {
      search() {
        this.getAllUsers(1)
      },
      selectItem(val) {
        this.multipleSelection = val
      },
      handleCurrentChange(page) {
        this.getAllUsers(page)
      },
      confirmDelete(item) {
        this.$confirm('确认删除该成员?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/users/', item.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              setTimeout(() => {
                _g.shallowRefresh(this.$route.name)
              }, 1500)
            })
          })
        }).catch(() => {
          // catch error
        })
      },
      getAllUsers(page) {
        this.loading = true
        const data = {
          params: {
            keywords: this.keywords,
            page: page,
            limit: this.limit
          }
        }
        this.apiGet('admin/users', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.dataCount = data.dataCount
          })
        })
      },
      getKeywords() {
        let data = this.$route.query
        if (data) {
          if (data.keywords) {
            this.keywords = data.keywords
          } else {
            this.keywords = ''
          }
        }
      },
      init() {
        this.getKeywords()
        this.getAllUsers()
      }
    },
    created() {
      this.init()
    },
    computed: {
//      成员列表
      listShow() {
        return _g.getHasRule('users-index')
      },
//      添加成员按钮
      addShow() {
        return _g.getHasRule('users-save')
      },
//      编辑成员按钮
      editShow() {
        return _g.getHasRule('users-update')
      },
//      删除成员按钮
      deleteShow() {
        return _g.getHasRule('users-delete')
      }
    },
    watch: {
      '$route'(to, from) {
        this.init()
      }
    },
    components: {
      btnGroup
    },
    mixins: [http]
  }
</script>
<style>
  .demo-table-expand {
    font-size: 0;
  }
  .demo-table-expand label {
    width: 90px;
    color: #99a9bf;
  }
  .demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
  }
</style>