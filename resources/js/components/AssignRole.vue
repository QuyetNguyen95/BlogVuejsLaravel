<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">
                        Role Manager
                        <Select v-model="data.role_id"  placeholder="Chọn loại " style="width: 140px" @on-change="changeRole">
                            <Option  v-for="(role,index) in roles" :key="index" :value="role.id" >{{role.roleName}}</Option>
                        </Select>
                    </p>
                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Resource Name</th>
                                <th>Read</th>
                                <th>write</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            <!-- TABLE TITLE -->
                            <!-- ITEMS -->
                            <tr v-for="(r,index) in resources" :key="index">
                                <td>{{r.resourceName}}</td>
                                <td><Checkbox v-model="r.read"></Checkbox></td>
                                <td><Checkbox v-model="r.write"></Checkbox></td>
                                <td><Checkbox v-model="r.update"></Checkbox></td>
                                <td><Checkbox v-model="r.delete"></Checkbox></td>
                            </tr>
                        </table>
                        <div class="footer_button">
                            <Button type="info" @click="assign"  :loading="isSending">{{isSending ? 'Assigning...' : 'Assign'}}</Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                data:{
                    role_id:''
                },
                isSending:false,
                roles:[],
                resources:[
                    {resourceName:'Dashboard',read:false, write:false, update:false, delete:false,name:'dashboard'},
                    {resourceName:'Tags',read:false, write:false, update:false, delete:false,name:'tags'},
                    {resourceName:'Category',read:false, write:false, update:false, delete:false,name:'category'},
                    {resourceName:'AdminUser',read:false, write:false, update:false, delete:false,name:'adminUser'},
                    {resourceName:'RoleComponent',read:false, write:false, update:false, delete:false,name:'role'},
                    {resourceName:'AssignRole',read:false, write:false, update:false, delete:false,name:'assign'},
                ],
                defaultResources:[
                    {resourceName:'Dashboard',read:false, write:false, update:false, delete:false,name:'dashboard'},
                    {resourceName:'Tags',read:false, write:false, update:false, delete:false,name:'tags'},
                    {resourceName:'Category',read:false, write:false, update:false, delete:false,name:'category'},
                    {resourceName:'AdminUser',read:false, write:false, update:false, delete:false,name:'adminUser'},
                    {resourceName:'RoleComponent',read:false, write:false, update:false, delete:false,name:'role'},
                    {resourceName:'AssignRole',read:false, write:false, update:false, delete:false,name:'assign'},
                ]
            }
        },
        methods:{
            async assign(){
                const permission = JSON.stringify(this.resources);//chuyển js objects -> json
                const res = await this.callApi('post','/app/assign',{permission: permission , id: this.data.role_id})
                if (res.status === 200){
                    let index = this.roles.findIndex(role => role.id == this.data.role_id)
                    this.roles[index].permission = permission
                    this.success('Thành công','Thêm Assign thành công')
                }else {
                    this.e();
                }
            },
            changeRole(){
                //đẩy dữ liệu từ permission(ở dạng json) of roles vào resources
               let index = this.roles.findIndex(role => role.id == this.data.role_id)
               this.resources = JSON.parse(this.roles[index].permission)//json -> js objects
                if (this.roles[index].permission == null){
                    this.resources = this.defaultResources
                }
            }
        },
        async created(){
            const res = await this.callApi('get','/app/role');
            console.log(res)
            if (res.status === 200){
                this.roles = res.data
                if (res.data.length){
                    this.data.role_id = res.data[0].id
                    if (res.data[0].permission !== null){
                        this.resources = JSON.parse(res.data[0].permission)
                    }
                }
            }else {
                this.e();
            }
        },
    }
</script>
<style scoped>
    .footer_button{
        margin-top: 20px;
    }
</style>
