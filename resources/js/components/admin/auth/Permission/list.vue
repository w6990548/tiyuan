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
                default-expand-all
                ref="table"
                :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
            <el-table-column
                    prop="purview_name"
                    label="权限名称"
                    width="200">
                <template slot-scope="scope">
                    <el-tag size="medium" v-if="scope.row.level === 1" type="danger">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 2" type="warning">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 3">{{ scope.row.purview_name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="url"
                    width="300">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info">{{ scope.row.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="created_at"
                    label="添加时间">
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button v-if="scope.row.level < 3"
                            type="primary"
                            size="mini"
                            @click="openDialog('isAdd', scope.row)">添加子权限</el-button>
                    <el-button
                            size="mini"
                            @click="openDialog('isEdit', scope.row)">编辑</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="deletePermissions(scope.$index, scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog title="添加权限" :visible.sync="isAdd" width="450px" center v-if="isAdd" @close="closeAddDialog">
            <purview-form
                    @cancel="closeAddDialog"
                    @save="doAddPermissions"
                    :PermissionsData="topPermissionData"
                    :id="id"
                    :isAddOrEdit="isAddOrEdit"
            />
        </el-dialog>
        <el-dialog title="编辑权限" :visible.sync="isEdit" width="450px" center v-if="isEdit" @close="closeAddDialog">
            <purview-form
                    @cancel="closeAddDialog"
                    @save="doEditPermissions"
                    :PermissionsData="topPermissionData"
                    :id="id"
                    :isAddOrEdit="isAddOrEdit"
            />
        </el-dialog>
    </page>
</template>
<script>
    import PurviewForm from './form';

    export default {
        name: 'list',
        data(){
            return {
                tableData: [],
                topPermissionData: [],
                id: 0,
                isAddOrEdit: '',
                isAdd: false,
                isEdit: false,
            }
        },
        computed:{

        },
        methods: {
            getList() {
                api.get('/permissions').then(data => {
                    this.tableData = data.data;
                    this.topPermissionData = data.data;
                })
            },
            addPermissions() {
                this.isAddOrEdit = 'isAdd';
                this.isAdd = true;
            },
            doAddPermissions(form) {
                api.post('/permissions/create', form).then(data => {
                    this.$message.success('添加成功');
                    this.isAdd = false;
                    this.getList();
                })
            },
            doEditPermissions(form) {
                api.post('/permissions/edit', form).then(data => {
                    this.$message.success('修改成功');
                    this.isEdit = false;
                    this.getList();
                })
            },
            deletePermissions(index, row) {
                this.$confirm('确认删除该权限吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    api.post('/permissions/delete', {
                        id: row.id,
                    }).then(data => {
                        this.$message.success('删除成功');
                        this.getList();
                    })
                }).catch(() => {

                });
            },
            openDialog(isAddOrEdit, row) {
                isAddOrEdit === 'isAdd' ? this.isAdd = true : this.isEdit = true;
                this.isAddOrEdit = isAddOrEdit;
                let newData = [];
                newData.push(row);
                this.id = row.id;
                this.topPermissionData = newData;
            },
            closeAddDialog() {
                this.isAdd = false;
                this.isEdit = false;
                this.id = 0;
            },
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
