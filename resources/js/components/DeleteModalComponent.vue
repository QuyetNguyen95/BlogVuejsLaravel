<template>
    <div>
        <Modal v-model="getDeleteModalObj.showDeleteModal" width="360" :closable="false">
            <p slot="header" style="color: #f60; text-align: center">
                <Icon type="ios-information-circle"></Icon>
                <span>Xác nhận xóa {{getDeleteModalObj.message}}</span>
            </p>
            <div style="text-align: center">
                <p>Sau khi xóa, bạn sẽ mất hết thông tin của {{getDeleteModalObj.message}}</p>
                <p>Bạn có muốn xóa không?</p>
            </div>
            <div slot="footer">
                <Button  @click="close" >Thoát</Button>
                <Button type="error"  :loading="isDelete" @click="Delete" >Xóa</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
    export default {
        data (){
            return {
                isDelete: false,
            }
        },
        methods: {
          async  Delete(){
               this.isDelete = true
              const res = await this.callApi('post',this.getDeleteModalObj.deleteUrl,this.getDeleteModalObj.data);
              if (res.status === 200){
                  this.success('Thành công','Xóa ' + this.getDeleteModalObj.message + ' thành công')
                  this.$store.commit('setDeleteModal',true,)
              }
              else {
                  this.error('Thất bại','Xóa ' + this.getDeleteModalObj.message +' thất bại')
              }
              this.isDelete = false
            },
            close(){
              this.$store.commit('setDeleteModal',false)
            }
        },
        computed:{
            ...mapGetters([
                'getDeleteModalObj'
            ])
        }
    }
</script>
