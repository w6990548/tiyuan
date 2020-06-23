<template>
    <page title="角色管理">
        <el-table :data="tableData" style="width: 100%" border>
            <el-table-column
                    label="角色名"
                    width="180">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="拥有权限组"
                    width="180">
                <template slot-scope="scope">
                    <el-tag size="medium">{{ scope.row.username }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="状态"
                    width="180">
                <template slot-scope="scope">
                    <span>{{ scope.row.username }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="添加时间"
                    width="180">
                <template slot-scope="scope">
                    <span>{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
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
        data(){
            return {
                tableData: [],
                total: 0,
                query: {
                    page: 1,
                    pageSize: 10,
                }
            }
        },
        computed:{

        },
        methods: {
            getList() {
                api.get('/api/admin/roles', this.query).then(data => {
                    this.tableData = data.data.list;
                    this.total = data.data.total;
                })
            },
            handleEdit(index, row) {
                console.log(index, row);
            },
            handleDelete(index, row) {
                console.log(index, row);
            },
            handleClose(){
                this.$refs.userForm.cancel()
            }
        },
        created(){
            this.getList();

        },
        components: {
            // UserForm
        }
    }
</script>
<style scoped>

</style>
