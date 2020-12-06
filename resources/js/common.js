import { mapGetters } from 'vuex'
export default {
    data() {
        return {
            global: 'Nguyễn Cương Quyết'
        };
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
                const res = await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
                return res;
            } catch (e) {
                return e.response;
            }
        },
        info(title,desc) {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        success(title,desc) {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning(title,desc) {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error(title,desc) {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        e() {
            this.$Notice.error({
                title: 'Xin lỗi',
                desc: 'Quá trình sữ lý dữ liệu xãy ra lỗi'
            });
        },
        checkUserPermission(key){
            let isPermission = false
            for (let p of this.getUserPermission){
               if(p.name == this.$route.name){
                   if (p[key]){
                       isPermission = true
                       break
                   }else
                   {
                       break
                   }
               }
            }
            return isPermission
        }
    },
    computed:{
        ...mapGetters([
            'getUserPermission'
        ]),
        isReading(){
           return  this.checkUserPermission('read')
        },
        isWriting(){
            return  this.checkUserPermission('write')
        },
        isUpdating(){
            return  this.checkUserPermission('update')
        },
        isDeleting(){
            return  this.checkUserPermission('delete')
        }
    }
};
