<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">
                        Tags
                        <Button v-on:click="addModal = true" v-if="isWriting">
                            <Icon type="md-add"/>
                            Add adminUser
                        </Button>
                    </p>
                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>Role Name</th>
                                <th>Updated at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->
                            <!-- ITEMS -->
                            <tr v-for="(role, index) in roles" :key="index">
                                <td>{{index + 1}}</td>
                                <td class="_table_name">{{role.roleName}}</td>
                                <td>{{ role.updated_at | formatDate }}</td>
                                <td>
                                    <Button type="info" size="small" @click="showEditModal(role,index)" v-if="isUpdating">Update</Button>
                                    <Button type="error" size="small" @click="showDeleteModal(role,index)" v-if="isDeleting">Delete</Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--                add modal-->
                    <Modal
                        v-model="addModal"
                        title="Thêm thông tin AdminUser">
                        <div class="space">
                            <Input v-model="dataAdd.roleName" type="text" placeholder="Nhập tên..."  />
                        </div>
                        <div slot="footer">
                            <Button @click="addModal = false">Thoát</Button>
                            <Button type="info"  :loading="loading" @click="addAdminUser">{{loading  ? 'Adding...' : 'AddRole' }}</Button>
                        </div>
                    </Modal>
                    <!--                edit modal-->
                    <Modal
                        v-model="editModal"
                        title="Thêm thông tin AdminUser">
                        <div class="space">
                            <Input v-model="dataEdit.roleName" type="text" placeholder="Nhập tên..."  />
                        </div>
                        <div slot="footer">
                            <Button @click="editModal = false" >Thoát</Button>
                            <Button type="info"  :loading="loading" @click="editRole">{{loading  ? 'Editing...' : 'EditRole' }}</Button>
                        </div>
                    </Modal>
                    <!--                delete modal-->
                    <DeleteModalComponent></DeleteModalComponent>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import DeleteModalComponent from "./DeleteModalComponent";
import { mapGetters } from 'vuex'
export default {
    data(){
        return {
            dataAdd:{
                roleName:''
            },
            dataEdit:{
                roleName:''
            },
            loading:false,
            roles:[],
            addModal:false,
            editModal:false,
            addIndex:-1,
            editIndex:-1
        }
    },
    methods:{
        async addAdminUser(){
            this.loading = true
            const res = await this.callApi('post','/app/create_role',this.dataAdd);
            if (res.status === 201){
                this.addModal = false
                this.roles.unshift(res.data)
                this.success('Thành công','Thêm role thành công');
                this.loading = false
                this.dataAdd.roleName = ''
            }else {
                if (res.status === 422){
                    this.error('Thất bại',res.data.errors.roleName[0])
                }else {
                    this.e();
                }
            }
            this.loading = false

        },
        showEditModal(role,index){
            this.editModal = true
            let obj = {
                id:role.id,
                roleName: role.roleName
            }
            this.dataEdit = obj
            this.editIndex = index
        },
        async editRole(){
            this.loading = true
            const res = await this.callApi('post','/app/edit_role',this.dataEdit);
            if (res.status === 200){
                this.roles[this.editIndex].roleName = this.dataEdit.roleName
                this.editModal = false
                this.loading = false
                this.success("Thành công", "Thay đổi role thành công");
            }else {
                if (res.status === 422){
                    this.error('Thất bại',res.data.errors.roleName[0])
                }else {
                    this.e();
                }
            }
            this.loading = false
        },
        showDeleteModal(role,index){
            const deleteModalObj = {
                showDeleteModal:true,
                deleteUrl: '/app/delete_role',
                data: role,
                message:'role',
                deleteIndex: index,
                isDeleted: false
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
        }
    },
    //hiển thị dữ liệu
    async created(){
        const res = await this.callApi('get','/app/role');
        if (res.status === 200){
            this.roles = res.data
        }
    },
    components:{
        DeleteModalComponent
    },
    computed:{
        ...mapGetters([
            'getDeleteModalObj'
        ])
    },
    watch:{
        getDeleteModalObj(obj){
            if (obj.isDeleted){
                this.roles.splice(obj.deleteIndex,1)
            }
        }
    }
}
</script>
