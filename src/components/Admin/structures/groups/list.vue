<template>
	<div>
		<div class="m-b-20" v-if="addShow">
			<router-link class="btn-link-large add-btn" to="add">
				<i class="el-icon-plus"></i>&nbsp;&nbsp;添加用户组
			</router-link>
		</div>
		<el-table
		:data="tableData"
		style="width: 100%"
		@selection-change="selectItem">
			<el-table-column
			type="selection"
			width="50">
			</el-table-column>
			<el-table-column
			label="组名"
			prop="title">
			</el-table-column>
      <el-table-column
      label="描述"
      prop="remark">
      </el-table-column>
			<el-table-column
			label="状态"
      prop="status"
			width="100">
        <template slot-scope="scope">
          <div>
            {{ scope.row.status | status }}
          </div>
        </template>
			</el-table-column>
			<el-table-column
			label="操作"
			width="200">
        <template slot-scope="scope">
  				<div>
  					<span v-if="editShow">
  						<router-link :to="{ name: 'groupsEdit', params: { id: scope.row.id }}">
  						  <el-button
                size="small"
                type="primary">
                编辑
                </el-button>
  						</router-link>
  					</span>
  					<span v-if="deleteShow">
  						<el-button
  						size="small"
  						type="danger"
  						@click="confirmDelete(scope.row)">
  						删除
  						</el-button>
  					</span>
  				</div>
        </template>
			</el-table-column>
		</el-table>
		<div class="pos-rel p-t-20">
			<btnGroup :selectedData="multipleSelection" :type="'groups'"></btnGroup>
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
        multipleSelection: [],
        limit: 10,
        currentPage: 1,
        dataCount: null,
      }
    },
    methods: {
      //      当表格列表选项发生改变时出发
      selectItem(val) {
        this.multipleSelection = val
      },
       //      删除角色执行方法
      confirmDelete(item) {
        this.$confirm('确认删除该角色?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _g.openGlobalLoading()
          this.apiDelete('admin/groups/', item.id).then((res) => {
            _g.closeGlobalLoading()
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              setTimeout(() => {
                _g.shallowRefresh(this.$route.name)
              }, 1500)
            })
          })
        }).catch(() => {
          // handle error
        })
      },
      //      切换页码
      handleCurrentChange(page) {
      this.getGroups(page)
      },
      getGroups(page) {
        this.loading = true
        const data = {
          params: {
          keywords: this.keywords,
          page: page,
          limit: this.limit
          }
        }
        this.apiGet('admin/groups', data).then((res) => {
          this.handelResponse(res, (data) => {
            this.tableData = data.list
            this.dataCount = data.dataCount
          })
        })
      }
    },
    created() {
      this.getGroups(1)
    },
    computed: {
      addShow() {
        return _g.getHasRule('groups-save')
      },
      editShow() {
        return _g.getHasRule('groups-update')
      },
      deleteShow() {
        return _g.getHasRule('groups-delete')
      }
    },
    components: {
      btnGroup
    },
    mixins: [http]
  }
</script>