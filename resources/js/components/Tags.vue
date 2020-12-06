<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">
                        Tags{{$store.state.count}}
                        <Button v-on:click="addModal = true" v-if="isWriting">
                            <Icon type="md-add"/>
                            Add tags
                        </Button>
                    </p>
                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>Tags Name</th>
                                <th>Updated at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(tag, index) in tags" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td class="_table_name">{{ tag.tagName }}</td>
                                <td>{{ tag.updated_at | formatDate }}</td>
                                <td>
                                    <Button type="info" size="small" @click="showEditModal(tag, index)" v-if="isUpdating">Update</Button>
                                    <Button type="error" size="small" @click="showDeleteModal(tag,index)" v-if="isDeleting">Delete</Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--addTag modal -->
                <Modal v-model="addModal" title="Thêm Tags" :closable="false">
                    <Input v-model="data.tagName" placeholder="Nhập tên tags"/>
                    <div slot="footer">
                        <Button v-on:click="addModal = false">Cancel</Button>
                        <Button type="info" v-on:click="addTag"  :loading="loading">
                            {{ loading ? "Adding..." : "Add Tags" }}
                        </Button>
                    </div>
                </Modal>
                <!--editTag modal -->
                <Modal v-model="editModal" title="Thêm Tags" :closable="false">
                    <Input v-model="dataEdit.tagName" placeholder="Nhập tên tags"/>
                    <div slot="footer">
                        <Button v-on:click="editModal = false">Cancel</Button>
                        <Button type="info" v-on:click="editTag"  :loading="loading">
                            {{ loading ? "Editing..." : "Edit Tags" }}
                        </Button>
                    </div>
                </Modal>
                <!--delete modal-->
                <DeleteModal></DeleteModal>
<!--                <Modal v-model="deleteModal" width="360" :closable="false">-->
<!--                    <p slot="header" style="color: #f60; text-align: center">-->
<!--                        <Icon type="ios-information-circle"></Icon>-->
<!--                        <span>Xác nhận xóa Tags</span>-->
<!--                    </p>-->
<!--                    <div style="text-align: center">-->
<!--                        <p>Sau khi xóa, bạn sẽ mất hết thông tin của Tags</p>-->
<!--                        <p>Bạn có muốn xóa không?</p>-->
<!--                    </div>-->
<!--                    <div slot="footer">-->
<!--                        <Button type="error" size="large" long :loading="isDelete" @click="deleteTag" >Xóa</Button>-->
<!--                    </div>-->
<!--                </Modal>-->
            </div>
        </div>
    </div>
</template>
<script>
import DeleteModal from './DeleteModalComponent'
import { mapGetters } from 'vuex'
export default {
    data() {
        return {
            data: {
                tagName: "",
            },
            addModal: false,
            editModal: false,
            deleteModal: false,
            loading: false,
            isDelete: false,
            tags: [],
            dataEdit: {
                tagName: "",
            },
            index: -1,
            dataDelete: {},
            indexDelete: -1,
        };
    },
    methods: {
        //thêm sản phẩm
        async addTag() {
            //kiểm tra xem dữ liệu có trống hay không
            if (this.data.tagName.trim() == "") {
                return this.e();
            }
            this.loading = true
            //gửi dữ liệu lên server
            const res = await this.callApi("post", "/app/create_tag", this.data);
            //res.status === 201 là đã xử lý thành công, trả về response là data được gửi đi
            if (res.status === 201) {
                //   console.log(res);
                //dùng hàm unshift đẩy dữ liệu vào đầu mảng tags
                this.tags.unshift(res.data);
                this.success("Thành công", "Thêm tags thành công")
                this.loading = false;
                //set trống tagname
               this.data.tagName = "";
            } else {
                this.error("Thất bại", "Thêm tags thất bại ");
            }
        },
        //show modal edit
        showEditModal(tag, index) {
            //display modal
            let obj = {
                id: tag.id,
                tagName: tag.tagName,
            };
            this.editModal = true;
            this.dataEdit = obj;
            this.index = index;
        },
        //update thông tin
        async editTag() {
            //  check empty data entry
            if (this.dataEdit.tagName.trim() == "") return this.e();
            //edit
            this.loading = true
            const res = await this.callApi("post", "/app/edit_tag", this.dataEdit);
            if (res.status === 200) {
                this.tags[this.index].tagName = res.data.tagName;
                this.editModal = false;
                this.loading = false
                this.success("Thành công", "Thay đổi tags thành công");
            } else {
                this.error("Thất bại", "Thay đổi tags thất bại ");
            }
            console.log(res);
        },
        //show modal delete
        showDeleteModal(tag, index) {
            //gán dữ liệu
           const deleteModalObj = {
               showDeleteModal:true,
               deleteUrl: '/app/delete_tag',
               data: tag,
               message:'Tags',
               deleteIndex: index,
               isDeleted: false
           }
        //gửi dữ liệu sang store.js
            this.$store.commit('setDeleteModalObj',deleteModalObj);
        },
        // async deleteTag() {
        //     this.isDelete = true
        //     const res = await this.callApi("post",'/app/delete_tag',this.dataDelete);
        //     if (res.status === 200){
        //         this.tags.splice(this.indexDelete,1);
        //         this.deleteModal = false
        //         this.isDelete = false
        //         this.success("Thành công", "Xóa tags thành công");
        //     }
        // }
    },
    components:{
        DeleteModal
    },
    //hiển thị danh sách sản phẩm
    async created() {
        //response trả về dữ liệu
        const res = await this.callApi("get", "/app/display_tag");
        if (res.status == 200) {
            //  console.log(res);
            //gán mảng tags = dữ liệu trả về response(res.data)
            this.tags = res.data;
        }
        //  console.log(this.tags)
    },
    computed:{
        ...mapGetters([
            'getDeleteModalObj'
        ])
    },
    watch:{
        getDeleteModalObj(obj){
            if (obj.isDeleted){
                this.tags.splice(obj.deleteIndex,1);
            }
        }
    }
};
</script>
