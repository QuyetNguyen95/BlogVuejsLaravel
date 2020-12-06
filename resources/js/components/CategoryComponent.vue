<template>
    <div class="content">
        <div class="container-fluid">
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    Tags
                    <Button v-on:click="addModal = true" v-if="isWriting">
                        <Icon type="md-add"/>
                        Add category
                    </Button>
                </p>
                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>Id</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Updated at</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->

                        <!-- ITEMS -->
                        <tr v-for="(category, index) in categories" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td class="_table_name">{{ category.categoryName }}</td>
                            <td class="table_image">
                                <img :src="category.iconImage">
                            </td>
                            <td>{{ category.updated_at | formatDate }}</td>
                            <td>
                                <Button type="info" size="small" @click="showEditModal(category,index)" v-if="isUpdating">Update</Button>
                                <Button type="error" size="small" @click="showDeleteModal(category,index)" v-if="isDeleting">Delete</Button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--modal add-->
        <Modal
            v-model="addModal"
            title="Nhập thông tin của category" :closable="false">
            <Input v-model="dataAdd.categoryName" placeholder="Nhập tên category"/>
            <!-- upload image-->
           <div class="space">
               <Upload
                   ref="upload"
                   type="drag"
                   action="/app/upload"
                   :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                   :on-success="handleSuccess"
                   :on-error="handleError"
                   :format="['jpg','jpeg','png']"
                   :max-size="2048"
                   :on-format-error="handleFormatError"
                   :on-exceeded-size="handleMaxSize">
                   <div style="padding: 20px 0">
                       <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                       <p>Click or drag files here to upload</p>
                   </div>
               </Upload>
               <Modal title="View Image" v-model="visible">
                   <img :src="`${dataAdd.iconImage}`" v-if="visible" style="width: 100%">
                   <div slot="footer"></div>
               </Modal>
               <div class="demo-upload-list"  v-if="dataAdd.iconImage">
                   <img :src="`${dataAdd.iconImage}`">
                   <div class="demo-upload-list-cover">
                       <Icon type="ios-eye-outline" @click="handleView" ></Icon>
                       <Icon type="ios-trash-outline" @click="handleRemove"></Icon>
                   </div>
               </div>
           </div>
            <div slot="footer">
                <Button v-on:click="addModal = false">Cancel</Button>
                <Button type="info" :loading="loading" @click="addCategory">
                    {{ loading ? "Adding..." : "Add category" }}
                </Button>
            </div>
        </Modal>
<!--        modal edit-->
        <Modal v-model="editModal" title="Edit category" :mask-closable="false" :closable="false">
            <Input v-model="dataEdit.categoryName" placeholder="Add category name" />
            <div class="space"></div>
            <Upload
                v-show="isShow"
                ref="editDataUploads"
                type="drag"
                :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                :on-success="handleSuccess"
                :on-error="handleError"
                :format="['jpg','jpeg','png']"
                :max-size="2048"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/app/upload"
            >
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                </div>
            </Upload>
            <Modal title="View Image" v-model="visible">
                <img :src="`${dataEdit.iconImage}`" v-if="visible" style="width: 100%">
                <div slot="footer"></div>
            </Modal>
            <div class="demo-upload-list" v-if="dataEdit.iconImage">
                <img :src="`${dataEdit.iconImage}`" />
                <div class="demo-upload-list-cover">
                    <Icon type="ios-eye-outline" @click="handleView" ></Icon>
                    <Icon type="ios-trash-outline" @click="handleRemove(false)"></Icon>
                </div>
            </div>

            <div slot="footer">
                <Button type="default" @click="editModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="editCategory"
                    :loading="loading"
                >{{loading ? 'Editing..' : 'Edit Category'}}</Button>
            </div>
        </Modal>
<!--        modal delete -->
        <DeleteModalComponent></DeleteModalComponent>
    </div>
</template>
<script>
import DeleteModalComponent from "./DeleteModalComponent";
import { mapGetters } from 'vuex'
export default {

    data() {
        return {
            dataAdd: {
                categoryName: "",
                iconImage: "",
            },
            addModal: false,
            editModal: false,
            loading: false,
            categories: [],
            token:'',
            isShow: false,
            visible: false,
            dataEdit:{
                categoryName: "",
                iconImage: "",
            },
            editIndex: -1,
            isEditing: false,
            deleteModal:false,
            deleteIndex: -1,
            deleteData:{

            }
        }
    },
    methods: {
        //thêm sản phẩm
        async addCategory() {
            //kiểm tra xem dữ liệu có trống hay không
            // if (this.dataAdd.categoryName.trim() === "") {
            //     return this.e();
            // }

            //gửi dữ liệu lên server
            this.loading = true
            const res = await this.callApi("post", "/app/create_category", this.dataAdd);
            console.log(res)
            //res.status === 201 là đã xử lý thành công, trả về response là data được gửi đi
            if (res.status === 201) {
                //push dữ liệu trả về vào đầu mảng categories
                this.categories.unshift(res.data);
                this.success("Thành công", "Thêm category thành công");
                this.loading = false;
                this.addModal = false;
                this.dataAdd.categoryName = ""
                this.dataAdd.iconImage = ""
                this.$refs.upload.clearFiles();
            }else {
                if(res.status === 422){
                    console.log(res.data.errors);
                    for (let error in res.data.errors){
                        this.error("Thất bại",res.data.errors.[error][0])
                    }
                    this.loading = false;
                }
            }
            this.loading = false;
        },
        //validate theo cách laravel
        //nhận dữ liệu từ server
        handleSuccess (res, file) {
            res = `/uploads/${res}`;
            if(this.isEditing){
                return   this.dataEdit.iconImage = res;
            }
            console.log(res);
            this.dataAdd.iconImage = res;
        },
        //validate theo cách  laravel
        handleError (res, file) {
            this.$Notice.warning({
                title: 'File upload không đúng',
                desc: file.errors.file.length ? file.errors.file[0] : 'Upload thất bại'
            });
        },
        //validate theo cách iview ui
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'File upload không đúng',
                desc: 'Định dạnh file ' + file.name + ' không đúng, làm ơn chọn jpg hoặc png.'
            });
        },
        //validate theo cách iview ui
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Kích thước file lớn',
                desc: 'File  ' + file.name + ' quá lớn, không lớn hơn 2M.'
            });
        },
        handleView () {
            this.visible = true;
        },
        async  handleRemove (isAdd = true) {
            let image
            if(!isAdd){
                this.isShow = true;
                image  = this.dataEdit.iconImage
                this.dataEdit.iconImage = '';
                this.$refs.editDataUploads.clearFiles();
            } else {
                image  = this.dataAdd.iconImage
                this.dataAdd.iconImage = '';
                this.$refs.upload.clearFiles();
            }
            const res = await this.callApi('post','/app/delete_image',{imageName: image})
        },
        showEditModal(category,index){
            const obj = {
                id : category.id,
                categoryName:category.categoryName,
                iconImage: category.iconImage,
            }
            this.editModal = true;
            this.dataEdit = obj
            this.editIndex = index
            this.isEditing = true
            console.log(this.dataEdit)
        },
        async editCategory(){
            this.loading = true;
            const res = await this.callApi("post", "/app/edit_category", this.dataEdit);
            if(res.status === 200){
                this.categories[this.editIndex].categoryName= this.dataEdit.categoryName
                this.categories[this.editIndex].iconImage= this.dataEdit.iconImage
                this.success("Thành công", "Thêm category thành công");
                this.loading = false;
                this.editModal = false;
            }else {
                if(res.status === 422){
                    if(res.data.errors.categoryName){
                        this.error("Thất bại",res.data.errors.categoryName[0])
                    }
                    if(res.data.errors.iconImage){
                        this.error("Thất bại",res.data.errors.iconImage[0])
                    }
                }
            }
        },
        showDeleteModal(category,index){
            const deleteModalObj = {
                showDeleteModal:true,
                deleteUrl: '/app/delete_category',
                data: category,
                message:'Category',
                deleteIndex: index,
                isDeleted: false
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj)
        }
    },
    components:{
        DeleteModalComponent
    },
    async created() {

        this.token = window.csrf_token
        const res = await this.callApi("get", '/app/display_category');
        console.log(res);
        if (res.status === 200) {
            this.categories = res.data
        }
    },
    computed:{
        ...mapGetters([
            'getDeleteModalObj'
        ])
    },
    watch:{
        getDeleteModalObj(obj){
            if (obj.isDeleted){
                this.categories.splice(obj.deleteIndex,1)
            }
        }
    }
}
</script>
<style>
.demo-upload-list{
    display: inline-block;
    width: 150px;
    height: 150px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
}
.demo-upload-list img{
    width: 100%;
    height: 100%;
}
.demo-upload-list-cover{
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,.6);
}
.demo-upload-list:hover .demo-upload-list-cover{
    display: block;
}
.demo-upload-list-cover i{
    color: #fff;
    cursor: pointer;
    margin: 0 2px;
    padding-top: 40px;
    font-size: 38px;
}
</style>

