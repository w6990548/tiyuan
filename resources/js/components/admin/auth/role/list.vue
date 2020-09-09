<template>
    <page title="角色管理">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="operateRole('addTitle')">添加角色</el-button>
        </div>
        <el-table :data="tableData" style="width: 100%">
            <el-table-column
                label="ID"
                width="180" prop="id">
            </el-table-column>
            <el-table-column
                label="标识"
                width="180">
                <template slot-scope="scope">
                    <el-tag type="danger">
                        {{ scope.row.name }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                label="名称"
                width="180">
                <template slot-scope="scope">
                    <span>{{ scope.row.alias_name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="添加时间"
                width="200">
                <template slot-scope="scope">
                    <span>{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column
                label="更新时间"
                width="200">
                <template slot-scope="scope">
                    <span>{{ scope.row.updated_at }}</span>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                        v-if="scope.row.name !== 'administrator'"
                        size="mini"
                        @click="operateRole('editTitle', scope.row)">编辑
                    </el-button>
                    <el-button
                        v-if="scope.row.name !== 'administrator'"
                        size="mini"
                        type="danger"
                        @click="deleteRole(scope.row)">删除
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
        <el-dialog :title="titleMap[dialogTitle]"
                   :visible.sync="dialogFormVisible"
                   v-if="dialogFormVisible"
                   @cancel="closeAddDialog" width="450px" center>
            <role-form @cancel="closeAddDialog"
                       :permisionData="permisionData"
                       :rowData="currentData"
                       :dialogTitle="dialogTitle"
                       @save="doOperateRole"/>
        </el-dialog>
    </page>
</template>
<script>

import RoleForm from './form';

export default {
    name: 'list',
    data() {
        return {
            titleMap: {
                addTitle: "添加角色",
                editTitle: "编辑角色"
            },
            dialogTitle: "",
            dialogFormVisible: false,
            currentData: {},
            query: {
                page: 1,
                pageSize: 10,
            },
            tableData: [],
            permisionData: [],
            total: 0,
        }
    },
    computed: {},
    methods: {
        getList() {
            api.get('admin/roles', this.query).then(data => {
                this.tableData = data.data.roles;
                this.permisionData = data.data.permissions;
                this.total = data.data.total;
            })
        },
        operateRole(title, row) {
            this.dialogFormVisible = true;
            this.dialogTitle = title;
            if (title === 'editTitle') {
                this.currentData = row;
            }
        },
        doOperateRole(form, type) {
            let url, message = '';
            if (type === 'addTitle') {
                url = 'admin/roles/create';
                message = '添加成功';
            }

            if (type === 'editTitle') {
                url = 'admin/roles/edit';
                message = '修改成功';
            }
            api.post(url, form).then(() => {
                this.dialogFormVisible = false;
                this.$notify.success({'title': '提示', message: message});
                this.getList();
            })
        },
        closeAddDialog() {
            this.dialogFormVisible = false;
        },
        deleteRole(row) {
            this.$confirm('确认要删除该角色吗？', '确认', {
                type: 'warning',
            }).then(() => {
                api.post('admin/roles/delete', {
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
        RoleForm
    }
}
</script>
<style scoped>

</style>
