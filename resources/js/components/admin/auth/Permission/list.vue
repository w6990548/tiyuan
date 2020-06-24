<template>
    <page title="权限管理">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="addPermissions">添加权限</el-button>
        </div>
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
                    width="300">
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
                            @click="deletePermissions(scope.$index, scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog title="添加权限" :visible.sync="isAdd" width="450px" center>
            <purview-form @cancel="isAdd = false" @save="doAddPermissions" :PermissionsData1="PermissionsData"></purview-form>
        </el-dialog>
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
    import PurviewForm from './form';

    export default {
        name: 'list',
        data(){
            return {
                tableData: [],
                PermissionsData: [],
                total: 0,
                query: {
                    page: 1,
                    pageSize: 10,
                },
                isAdd: false,
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
            addPermissions() {
                this.isAdd = true
                api.get('/api/admin/permissions').then(data => {
                    this.PermissionsData = data.data.list;
                })
            },
            doAddPermissions(form) {
                api.post('/api/admin/permissions/create', form).then(data => {
                    this.$message.success('添加成功');
                    this.isAdd = false;
                    this.getList();
                })
            },
            deletePermissions(index, row) {
                this.$confirm('确认删除该权限吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    api.post('/api/admin/permissions/delete', {
                        id: row.id
                    }).then(data => {
                        this.$message.success('删除成功');
                        this.getList();
                    })
                }).catch(() => {

                });
            },
            handleEdit(index, row) {
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
            PurviewForm
        }
    }
</script>
<style scoped>

</style>
