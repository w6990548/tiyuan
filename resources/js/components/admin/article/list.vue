<template>
    <page title="文章列表">
        <div class="m-b-20">
            <el-button type="primary" size="small">发布文章</el-button>
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
                    label="文章ID"
                    width="100">
                <template slot-scope="scope">
                    <el-tag size="medium" v-if="scope.row.level === 1" type="danger">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 2" type="warning">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 3">{{ scope.row.purview_name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="purview_name"
                    label="文章标题">
                <template slot-scope="scope">
                    <el-tag size="medium" v-if="scope.row.level === 1" type="danger">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 2" type="warning">{{ scope.row.purview_name }}</el-tag>
                    <el-tag size="medium" v-if="scope.row.level === 3">{{ scope.row.purview_name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="文章标签"
                    >
                <template slot-scope="scope">
                    <el-tag size="medium" type="info">{{ scope.row.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    prop="created_at"
                    label="发布时间"
                    width="200">
            </el-table-column>
            <el-table-column label="操作" width="200">
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
    </page>
</template>

<script>
    export default {
        name: "list",
        data(){
            return {
                tableData: [],
            }
        },
    }
</script>

<style scoped>

</style>
