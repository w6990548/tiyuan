<template>
    <page title="用户管理">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="addUser">添加用户</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%" border>
            <el-table-column
                    label="ID"
                    width="80">
                <template slot-scope="scope">
                    <span>{{ scope.row.id }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="用户名"
                    width="180">
                <template slot-scope="scope">
                    <el-tag size="medium">{{ scope.row.username }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="所属角色"
                    width="180">
                <template slot-scope="scope">
                    <el-tag size="medium" type="danger"
                            v-for="item in scope.row.roles"
                            :key="item.id">
                        {{ item.name }}
                    </el-tag>
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
                            type="primary"
                            size="mini"
                            @click="editUser(scope.$index, scope.row)">编辑
                    </el-button>
                    <el-button
                            v-if="(userId !== 1 && scope.row.id > 1) || userId == 1"
                            type="warning"
                            size="mini"
                            @click="resetPwd(scope.row)">重置密码
                    </el-button>
                    <el-button
                            v-if="scope.row.id !== 1"
                            size="mini"
                            type="danger"
                            @click="deleteUser(scope.$index, scope.row)">删除
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
        <el-dialog title="添加用户" :visible.sync="isAdd" width="450px" center v-if="isAdd">
            <user-form @cancel="isAdd = false" :roleData="roleData" @save="doAddUser"/>
        </el-dialog>
        <el-dialog title="编辑用户" :visible.sync="isEdit" width="450px" center v-if="isEdit">
            <user-form @cancel="isEdit = false" :roleData="roleData" :rowData="rowData" @save="doEditUser"/>
        </el-dialog>
    </page>
</template>
<script>

    import UserForm from './form';

    export default {
        name: 'list',
        data() {
            return {
                tableData: [],
                total: 0,
                isAdd: false,
                isEdit: false,
                roleData: [],
                rowData: [],
                query: {
                    page: 1,
                    pageSize: 10,
                }
            }
        },
        computed: {
            userId() {
                let user = localStorage.getItem('user');
                return user ? JSON.parse(user).id : '';
            }
        },
        methods: {
            getList() {
                api.get('admin/users', this.query).then(data => {
                    this.tableData = data.data.list;
                    this.roleData = data.data.roles;
                    this.total = data.data.total;
                })
            },
            addUser() {
                this.isAdd = true;
            },
            doAddUser(form) {
                api.post('admin/users/create', form).then(() => {
                    this.$message.success('添加成功');
                    this.isAdd = false;
                    this.getList();
                })
            },
            editUser(index, row) {
                this.isEdit = true;
                this.rowData.showPwd = false;
                this.rowData.role = row;
            },
            doEditUser(form) {
                api.post('admin/users/edit', form).then(() => {
                    this.$message.success('修改成功');
                    this.isEdit = false;
                    this.getList();
                })
            },
            resetPwd(row) {
                this.$prompt('', '重置用户 【' + row.username + '】 的密码', {
                    type: 'warning',
                    inputPlaceholder: '请输入新密码'
                }).then(({value}) => {
                    console.log(row,value);
                    api.post('admin/users/reset', {
                        id: row.id,
                        password: value,
                    }).then(() => {
                        this.$message.success('重置成功');
                    })
                }).catch(() => {

                })
            },
            deleteUser(index, row) {
                this.$confirm('确认要删除该用户吗？', '确认', {
                    type: 'warning',
                }).then(() => {
                    api.post('admin/users/delete', {
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
        },
        components: {
            UserForm
        }
    }
</script>
<style scoped>

</style>
