<template>
    <page title="操作日志">
        <el-table :data="tableData" style="width: 100%">
            <el-table-column label="ID" width="180" prop="id"/>
            <el-table-column label="用户名称" width="180">
                <template slot-scope="scope">
                    <el-tag type="danger" v-if="scope.row.admin_user">
                        {{ scope.row.admin_user.username }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="用户ID" width="180">
                <template slot-scope="scope">
                    <span v-if="scope.row.user_id">
                        {{ scope.row.user_id }}
                    </span>
                    <span v-else></span>
                </template>
            </el-table-column>
            <el-table-column label="请求方法" width="180">
                <template slot-scope="scope">
                    <el-tag type="warning" v-if="scope.row.method === 'POST'">
                        {{ scope.row.method }}
                    </el-tag>
                    <el-tag v-else-if="scope.row.method === 'GET'">
                        {{ scope.row.method }}
                    </el-tag>
                    <span v-else>{{ scope.row.method }}</span>
                </template>
            </el-table-column>
            <el-table-column label="请求地址">
                <template slot-scope="scope">
                    <el-tag type="info">
                        {{ scope.row.path }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="IP">
                <template slot-scope="scope">
                    <el-tag type="info">
                        {{ scope.row.ip }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="输入">
                <template slot-scope="scope">
                    <el-tooltip class="item" v-if="scope.row.input.length > 2" effect="dark" placement="top">
                        <div slot="content">
                            <pre>{{ JSON.parse(scope.row.input) }}</pre>
                        </div>
                        <el-tag type="info">查看</el-tag>
                    </el-tooltip>
                </template>
            </el-table-column>
            <el-table-column label="添加时间" width="200">
                <template slot-scope="scope">
                    <span>{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column label="操作" width="80">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        type="danger"
                        @click="deleteLog(scope.row)">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination class="fr m-t-20 p-d-0"
                       background
                       layout="prev, pager, next"
                       @current-change="getList"
                       :current-page.sync="query.page"
                       :page-size="query.pageSize"
                       :total="total">
        </el-pagination>
    </page>
</template>
<script>

export default {
    name: 'list',
    data() {
        return {
            query: {
                page: 1,
                pageSize: 10,
            },
            tableData: [],
            total: 0,
        }
    },
    computed: {},
    methods: {
        getList() {
            api.get('admin/logs', this.query).then(data => {
                this.tableData = data.data.list;
                this.total = data.data.total;
            })
        },
        deleteLog(row) {
            this.$confirm('确认要删除该日志吗？', '确认', {
                type: 'warning',
            }).then(() => {
                api.post('admin/logs/delete', {
                    id: row.id
                }).then(() => {
                    this.$notify.success({'title': '提示', message: '删除成功'});
                    this.getList();
                })
            }).catch(() => {

            })
        },
    },
    created() {
        this.getList();
    },
    components: {
        // RoleForm
    }
}
</script>
<style scoped>

</style>
