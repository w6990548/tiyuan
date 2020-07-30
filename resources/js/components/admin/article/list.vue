<template>
    <page title="文章列表">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="addArticle">发布文章</el-button>
        </div>
        <el-table
                :data="tableData"
                style="width: 100%"
                border
                ref="table">
            <el-table-column
                    prop="id"
                    label="文章ID"
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="文章标题"
                    width="400"
                    show-overflow-tooltip>
            </el-table-column>
            <el-table-column label="文章标签">
                <template slot-scope="scope">
                    <el-tag size="medium" class="m-r-5" type="warning"
                            v-for="item in scope.row.labels"
                            :key="item.id">
                        {{ item.name }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="is_top"
                    label="是否置顶"
                    width="80">
                <template slot-scope="scope">
                    <el-switch v-model="scope.row.is_top"
                               active-color="#13ce66"
                               inactive-color="#ff9090"
                               @change="changeStatus(scope.row)"></el-switch>
                </template>
            </el-table-column>
            <el-table-column
                    prop="status"
                    label="上架"
                    width="80">
                <template slot-scope="scope">
                    <el-switch v-model="scope.row.status"
                               active-color="#13ce66"
                               inactive-color="#ff9090"
                               @change="changeStatus(scope.row)"></el-switch>
                </template>
            </el-table-column>
            <el-table-column
                    prop="created_at"
                    label="发布时间"
                    width="200">
            </el-table-column>
            <el-table-column label="操作" width="300">
                <template slot-scope="scope">
                    <el-button size="mini" @click="detail(scope.row)">查看</el-button>
                    <el-button type="primary" size="mini" @click="editArticle(scope.row)">编辑</el-button>
                    <el-button type="danger" size="mini" @click="deleteArticle(scope.$index, scope.row)">删除</el-button>
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
        name: "list",
        data(){
            return {
                tableData: [],
                total: 0,
                query: {
                    page: 1,
                    pageSize: 10,
                },
            }
        },
        methods: {
            getList() {
                api.get('admin/articles', this.query).then(data => {
                    this.tableData = data.data.list;
                    this.total = data.data.total;
                })
            },
            addArticle() {
                this.$router.push('/articles/create');
            },
            detail(row) {
                this.$router.push({
                    path: '/articles/detail',
                    query: {
                        id: row.id
                    },
                })
            },
            changeStatus(row) {
                api.post('/admin/articles/changeStatus', {
                    id: row.id,
                    is_top: row.is_top,
                    status: row.status,
                })
            },
            editArticle(row) {
                this.$router.push({
                    path: '/articles/edit',
                    query: {
                        id: row.id
                    },
                })
            },
            deleteArticle(index, row) {
                this.$confirm('确认要删除该文章吗？', '确认', {
                    type: 'warning',
                }).then(() => {
                    api.post('admin/articles/delete', {
                        id: row.id
                    }).then(() => {
                        this.$message.success('删除成功');
                        this.getList();
                    })
                }).catch(() => {

                })
            },
        },
        created() {
            this.getList();
        }
    }
</script>

<style scoped>

</style>
