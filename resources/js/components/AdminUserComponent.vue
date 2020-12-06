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
                               <th>AdminUser Name</th>
                               <th>Email</th>
                               <th>User Type</th>
                               <th>Updated at</th>
                               <th>Action</th>
                           </tr>
                           <!-- TABLE TITLE -->
                           <!-- ITEMS -->
                           <tr v-for="(adminUser, index) in adminUsers" :key="index">
                               <td>{{index + 1}}</td>
                               <td class="_table_name">{{adminUser.fullName}}</td>
                               <td>{{adminUser.email}}</td>
                               <td>{{adminUser.role.roleName}}</td>
                               <td>{{ adminUser.updated_at | formatDate }}</td>
                               <td>
                                   <Button type="info" size="small" @click="showEditModal(adminUser,index)" v-if="isUpdating">Update</Button>
                                   <Button type="error" size="small" @click="showDeleteModal(adminUser,index)" v-if="isDeleting">Delete</Button>
                               </td>
                           </tr>
                       </table>
                   </div>
                   <!--                add modal-->
                   <Modal
                       v-model="addModal"
                       title="Thêm thông tin AdminUser">
                       <div class="space">
                           <Input v-model="dataAdd.fullName" type="text" placeholder="Nhập tên..."  />
                       </div>
                       <div class="space">
                           <Input v-model="dataAdd.email" type="email" placeholder="Nhập email..."  />
                       </div>
                       <div class="space">
                           <Input v-model="dataAdd.password" type="password" placeholder="Nhập password..."  />
                       </div>
                       <div class="space">
                           <Select v-model="dataAdd.role"  placeholder="Chọn loại ">
                               <Option v-for="(role,index) in roles" :key="index" :value="role.id" >{{role.roleName}}</Option>
                           </Select>
                       </div>
                       <div slot="footer">
                           <Button @click="addModal = false">Thoát</Button>
                           <Button type="info"  :loading="loading" @click="addAdminUser">{{loading  ? 'Adding...' : 'AddAdmin' }}</Button>
                       </div>
                   </Modal>
                   <!--                edit modal-->
                   <Modal
                       v-model="editModal"
                       title="Thêm thông tin AdminUser">
                       <div class="space">
                           <Input v-model="dataEdit.fullName" type="text" placeholder="Nhập tên..."  />
                       </div>
                       <div class="space">
                           <Input v-model="dataEdit.email" type="email" placeholder="Nhập email..."  />
                       </div>
                       <div class="space">
                           <Input v-model="dataEdit.password" type="password" placeholder="******"  />
                       </div>
                       <div class="space">
                           <Select v-model="dataEdit.role"  placeholder="Chọn loại " >
                               <Option  v-for="(role,index) in roles" :key="index" :value="role.id" >{{role.roleName}}</Option>
                           </Select>
                       </div>
                       <div slot="footer">
                           <Button @click="editModal = false" >Thoát</Button>
                           <Button type="info"  :loading="loading" @click="editAdminUser">{{loading  ? 'Editing...' : 'EditAdd' }}</Button>
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
       data() {
           return {
               dataAdd: {
                   fullName: '',
                   email: '',
                   password:'',
                   role:null
               },
               addModal: false,
               loading: false,
               adminUsers: [],
               addIndex: -1,
               dataEdit: {
                   fullName: '',
                   email: '',
                   password:'',
                   role:null
               },
               editModal: false,
               editIndex: -1,
               roles:[]
           }
       },
        methods:{
           //hiển thị modal add
             async   addAdminUser(){
                this.loading = true
                //call api send data to server with post method
                const res = await this.callApi('post','/app/create_adminUser',this.dataAdd);
                console.log(res.data[0]);
                //receive response data
                if (res.status === 200){
                    //đưa response data in first adminUsers array
                    this.adminUsers.unshift(res.data[0])
                    this.success("Thành công", "Thêm adminUser thành công");
                    //set lại các giá trị ban dầu
                    this.addModal = false
                    this.loading = false
                    this.dataAdd.fullName = ''
                    this.dataAdd.email = ''
                    this.dataAdd.role = null
                }else {
                    if (res.status === 422){
                        for (let error in res.data.errors){
                            this.error('Thất bại',res.data.errors[error][0])
                        }
                    }else {
                        this.e();
                    }
                }
                this.loading = false
            },
            //hiển thì edit modal
            showEditModal(adminUser,index){
                this.editModal = true
                let obj = {
                    id:adminUser.id,
                    fullName: adminUser.fullName,
                    email: adminUser.email,
                    role: adminUser.role.id
                }
                this.dataEdit = obj
                this.editIndex = index

            },
            async editAdminUser(){
                this.loading = true
                const res = await this.callApi('post','/app/edit',this.dataEdit)
                console.log(res.data[0])
                if (res.status === 200){
                    this.adminUsers[this.editIndex].fullName = this.dataEdit.fullName
                    this.adminUsers[this.editIndex].email = this.dataEdit.email
                    this.adminUsers[this.editIndex].role.id = this.dataEdit.role
                    this.adminUsers[this.editIndex].role.roleName = res.data[0].role.roleName
                    this.editModal = false;
                    this.loading = false
                    this.success("Thành công", "Thay đổi adminUser thành công");
                }else {
                    if (res.status === 422){
                        for (let error in res.data.errors){
                            this.error('Thất bại',res.data.errors[error][0])
                        }
                    }
                }
                this.loading = false
            },
            showDeleteModal(adminUser,index){
                const  deleteModalObj =  {
                    showDeleteModal:true,
                    deleteUrl: '/app/delete',
                    message:'AdminUser',
                    data: adminUser,
                    deleteIndex: index,
                    isDeleted: false
                }
                this.$store.commit('setDeleteModalObj',deleteModalObj)
            }
        },
        async created() {
           const [res,resRol] = await Promise.all([
               this.callApi('get','/app/display'),
               this.callApi('get','/app/display_role')
           ])
           //lấy thông tin Admin user
            console.log(res)
          if (res.status === 200){
              this.adminUsers = res.data
          }
          //hiển thị danh sách các roleName
          if (resRol.status === 200){
              this.roles = resRol.data
          }
        },
        computed:{
            ...mapGetters([
                'getDeleteModalObj'
            ])
        },
        components:{
            DeleteModalComponent
        },
        watch:{
            getDeleteModalObj(obj){
                if (obj.isDeleted){
                    this.adminUsers.splice(obj.deleteIndex,1)
                }
            }
        }

    }
</script>
