<template>
    <div>
        <div class="m-b-20">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/admin' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item>环节管理</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="m-b-20">
            <router-link class="btn-link-large add-btn" to="add">
                <i class="el-icon-plus"></i>&nbsp;&nbsp;添加环节
            </router-link>
        </div>
        <el-table :data="tableData" style="width: 100%" @selection-change="selectItem">
            <el-table-column type="selection" width="50"></el-table-column>
            <el-table-column label="环节名称" prop="tache_name"></el-table-column>
            <el-table-column label="备注" prop="explain"></el-table-column>
            <!-- <el-table-column label="创建时间" prop="create_time"></el-table-column> -->
            <el-table-column label="操作" width="200">
                <template slot-scope="scope">
                    <div>
            <span>
              <el-button size="mini" type="primary">
                <router-link :to="{ name: 'tachesEdit', params: { id: scope.row.id }}"
                             class="">编辑</router-link>
              </el-button>
  					</span>
                        <span>
  						<el-button size="mini" type="danger" @click="confirmDelete(scope.row)">删除</el-button>
  					</span>
                    </div>
                </template>
            </el-table-column>
        </el-table>
        <div class="pos-rel p-t-20">
            <!-- <btnGroup :selectedData="multipleSelection" :type="'studios'"></btnGroup> -->
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
		keywords: '',
		multipleSelection: [],
		limit: 10
	  }
	},
	methods: {
	  //      当表格列表选项发生改变时出发
	  selectItem(val) {
		this.multipleSelection = val
	  },
	  //      删除环节执行方法
	  confirmDelete(item) {
			this.$confirm('确认删除该环节?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			}).then(() => {
				_g.openGlobalLoading()
				this.apiDelete('admin/taches/', item.id).then((res) => {				
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
		this.getAllTaches(page)
	  },
	  //      获取环节列表
	  getAllTaches(page) {
		this.loading = true
		const data = {
		  params: {
			keywords: this.keywords,
			page: page,
			limit: this.limit
		  }
		}
		this.apiGet('admin/taches', data).then((res) => {
		  this.handelResponse(res, (data) => {
			this.tableData = data.list
			this.dataCount = data.dataCount
		  })
		})
	  },
	  //      获取关键字
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
	},
	created() {
	  this.getAllTaches(1)
	  this.getKeywords()
	},
	computed: {
	  addShow() {
		return _g.getHasRule('taches-save')
	  },
	  editShow() {
		return _g.getHasRule('taches-update')
	  },
	  deleteShow() {
		return _g.getHasRule('taches-delete')
	  }
	},
	components: {
	  btnGroup
	},
	mixins: [http]
  }
</script>