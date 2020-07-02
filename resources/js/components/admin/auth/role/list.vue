<template>
    <page title="角色管理">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="addRoles">添加角色</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%" border>
            <el-table-column type="expand">
                <template slot-scope="scope">
                    <el-form label-position="left" inline class="demo-table-expand">
                        <div class="fl-div">拥有的权限：</div>
                        <span v-for="item in scope.row.permissions">
                            <el-tag v-if="item.level === 1" class="fl-el-tag" type="danger">
                                {{ item.purview_name+'【' + item.name+'】' }}
                            </el-tag>
                            <el-tag v-else-if="item.level === 2"  class="fl-el-tag" type="warning">
                                {{ item.purview_name+'【' + item.name+'】' }}
                            </el-tag>
                            <el-tag v-else class="fl-el-tag">
                                {{ item.purview_name+'【' + item.name+'】' }}
                            </el-tag>
                        </span>
                    </el-form>
                </template>
            </el-table-column>
            <el-table-column
                    label="角色名"
                    width="180">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
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
                            @click="deleteRole(scope.$index, scope.row)">删除</el-button>
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
        <el-dialog title="添加角色" :visible.sync="isAdd" width="450px" center v-if="isAdd">
            <role-form @cancel="isAdd = false" @save="doAddRoles"/>
        </el-dialog>
    </page>
</template>
<script>

    import RoleForm from './form';

    export default {
        name: 'list',
        data(){
            return {
                tableData: [],
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
                api.get('/roles', this.query).then(data => {
                    this.tableData = data.data.list;
                    this.total = data.data.total;
                })
            },
            addRoles() {
                this.isAdd = true;
            },
            doAddRoles(form) {
                api.post('/roles/create', form).then(() => {
                    this.$message.success('添加成功');
                    this.isAdd = false;
                    this.getList();
                })
            },
            handleEdit(index, row) {
                console.log(index, row);
            },
            deleteRole(index, row) {
                this.$confirm('确认要删除该角色吗？', '确认', {
                    type: 'warning',
                }).then(() => {
                    api.post('/roles/delete', {
                        id: row.id
                    }).then(() => {
                        this.$message.success('删除成功');
                        this.getList();
                    })
                }).catch(() => {

                })
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
            RoleForm
        }
    }
</script>
<style scoped>
    .fl-div {
        color: #99a9bf;
        line-height: 32px;
        display: inline-block;
    }
    .fl-el-tag {
        margin: 0 10px 5px 0;
    }
</style>
