<template>
    <page title="用户管理">
        <el-table
                :data="tableData"
                style="width: 100%"
                row-key="id"
                border
                lazy
                :load="load"
                :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
            <el-table-column
                    prop="purview_name"
                    label="权限名称"
                    width="200">
                <template slot-scope="scope">
                    <el-tag size="medium">{{ scope.row.purview_name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="url"
                    width="200">
                <template slot-scope="scope">
                    <el-tag size="medium" type="danger">{{ scope.row.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="created_at"
                    label="添加时间">
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleEdit(scope.$index, scope.row)">添加子权限</el-button>
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
                api.get('/api/admin/permissions', this.query).then(data => {
                    this.tableData = data.data.list;
                    this.total = data.data.total;
                })
            },
            load(tree, treeNode, resolve) {
                api.get('/api/admin/permissions', {
                    pid: tree.id,
                }).then(data => {
                    resolve(data.data.list)
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
