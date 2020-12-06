import  Vue from  'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
       deleteModalObj: {
           showDeleteModal:false,
           deleteUrl: '',
           message:'',
           data: null,
           deleteIndex: -1,
           isDeleted: false
       },
        user:false,
        userPermission:null
    },
    getters: {
        getDeleteModalObj(state){
            return state.deleteModalObj
        },
        getUserPermission(state){
            return state.userPermission
        }
    },
    mutations:{
        setDeleteModalObj(state,data){
            state.deleteModalObj = data
        },
        setDeleteModal(state,data){
            const deleteModalObj = {
                showDeleteModal:false,
                deleteUrl: '',
                data: null,
                deleteIndex: state.deleteModalObj.deleteIndex,
                isDeleted: data
            }
            state.deleteModalObj = deleteModalObj
        },
        setUpdateUser(state,data){
            state.user = data
        },
        setPermissionUser(state,data){
            state.userPermission = data
        }
    }
})
