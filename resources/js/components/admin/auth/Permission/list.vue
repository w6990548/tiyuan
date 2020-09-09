<template>
    <page title="权限管理">
        <div class="m-b-20">
            <el-button type="primary" size="small" @click="operatePermission('addTitle')">添加权限</el-button>
        </div>
        <el-table
            :data="tableData"
            style="width: 100%"
            row-key="id"
            default-expand-all
            ref="table"
            :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
            <el-table-column
                prop="alias_name"
                label="权限名称">
                <template slot-scope="scope">
                    <icon-svg class="al-icon" :icon-class="scope.row.icon" :size="24"/>
                    <el-tag size="medium" v-if="scope.row.type === 1">{{ scope.row.alias_name }}</el-tag>
                    <el-tag size="medium" type="danger" v-else-if="scope.row.type === 2">{{ scope.row.alias_name }}</el-tag>
                    <el-tag size="medium" type="warning" v-else-if="scope.row.type === 3">{{ scope.row.alias_name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                prop="name"
                label="权限地址">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info" v-if="scope.row.type === 1">{{ scope.row.name }}</el-tag>
                    <el-tag size="medium" type="danger" v-else-if="scope.row.type === 2">{{ scope.row.name }}</el-tag>
                    <el-tag size="medium" type="warning" v-else-if="scope.row.type === 3">{{ scope.row.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                prop="type"
                label="权限类型">
                <template slot-scope="scope">
                    <el-tag size="medium" v-if="scope.row.type === 1">菜单</el-tag>
                    <el-tag size="medium" type="danger" v-else-if="scope.row.type === 2">API</el-tag>
                    <el-tag size="medium" type="warning" v-else-if="scope.row.type === 3">页面元素</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                prop="slug"
                label="slug">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info" v-if="scope.row.slug && scope.row.type === 1">{{ scope.row.slug }}</el-tag>
                    <el-tag size="medium" type="danger" v-else-if="scope.row.slug && scope.row.type === 2">{{ scope.row.slug }}</el-tag>
                    <el-tag size="medium" type="warning" v-else-if="scope.row.slug && scope.row.type === 3">{{ scope.row.slug }}</el-tag>
                    <span v-else>{{ scope.row.slug }}</span>
                </template>
            </el-table-column>
            <el-table-column
                prop="created_at"
                label="添加时间">
            </el-table-column>
            <el-table-column
                prop="updated_at"
                label="修改时间">
            </el-table-column>
            <el-table-column label="操作" width="280">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        @click="operatePermission('addSubTitle', scope.row)">添加子权限
                    </el-button>
                    <el-button
                        size="mini"
                        @click="operatePermission('editTitle', scope.row)">编辑
                    </el-button>
                    <el-button
                        size="mini"
                        type="danger"
                        @click="deletePermissions(scope.row)">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="titleMap[dialogTitle]"
                   :visible.sync="dialogFormVisible"
                   v-if="dialogFormVisible" width="450px" center @close="closeAddDialog">
            <purview-form
                @cancel="closeAddDialog"
                @save="doOperatePermission"
                :PermissionsData="topPermissionData"
                :currentData="currentData"
                :dialogTitle="dialogTitle"/>
        </el-dialog>
    </page>
</template>
<script>
import PurviewForm from './form';

export default {
    name: 'list',
    data() {
        return {
            titleMap: {
                addTitle: "添加权限",
                addSubTitle: "添加子权限",
                editTitle: "编辑权限"
            },
            dialogTitle: "",
            dialogFormVisible: false,
            tableData: [],
            topPermissionData: [],
            currentData: {},
        }
    },
    computed: {},
    methods: {
        getList() {
            api.get('admin/permissions').then(data => {
                this.tableData = data.data.tree;
            })
        },
        operatePermission(title, row) {
            this.topPermissionData = deepCopy(this.tableData);
            this.dialogFormVisible = true;
            this.dialogTitle = title;
            if (title === 'addSubTitle' || title === 'editTitle') {
                this.currentData = row;
            }
        },
        doOperatePermission(form, type) {
            let url, message = '';
            if (type === 'addTitle' || type === 'addSubTitle') {
                url = 'admin/permissions/create';
                message = '添加成功';
            }

            if (type === 'editTitle') {
                url = 'admin/permissions/edit';
                message = '修改成功';
            }
            api.post(url, form).then(() => {
                this.dialogFormVisible = false;
                this.$notify.success({'title': '提示', message: message});
                this.getList();
            })
        },
        deletePermissions(row) {
            this.$confirm('确认删除该权限吗?', '提示', {
                type: 'warning'
            }).then(() => {
                api.post('admin/permissions/delete', {
                    id: row.id,
                }).then(() => {
                    this.$notify.success({'title': '提示', message: '删除成功'});
                    this.getList();
                })
            }).catch(() => {

            });
        },
        closeAddDialog() {
            this.dialogFormVisible = false;
        },
    },
    created() {
        this.getList();
    },
    components: {
        PurviewForm
    }
}
</script>
<style scoped>
    .al-icon {
        vertical-align: middle;
        display: inline-block;
        padding-bottom: 3px;
    }
    .icon {
      width: 1em; height: 1em;
      vertical-align: -0.15em;
      fill: currentColor;
      overflow: hidden;
    }
</style>
