<template>
    <div>
        <div class="content">
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-5">
                <div class="login_header">
                    <h3>Đăng nhập</h3>
                </div>
                <div class="space">
                    <Input v-model="data.email" type="email" placeholder="Nhập email..."  />
                </div>
                <div class="space">
                    <Input v-model="data.password" type="password" placeholder="******"  />
                </div>
                <div class="login_footer">
                    <Button type="info" @click="login" :loading="isLogin">{{isLogin ? 'Logging...' : 'Login'}}</Button>
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
                   email:'',
                   password:''
               },
                isLogin: false
            }
        },
        methods:{
            async  login(){
                this.isLogin = true
                const res = await this.callApi('post','/app/login',this.data)
                if (res.status === 200){
                    this.success('Thành công',res.data.msg)
                    this.isLogin = false
                    window.location.href = '/dashboard'
                }else {
                    if (res.status === 401){
                        this.error('Thất bại',res.data.msg)
                    }else if (res.status === 422){
                        for (let error in res.data.errors){
                            this.error('Thất bại',res.data.errors[error][0])
                        }
                    }else {
                        this.e();
                    }
                }
                this.isLogin = false
            }
        }
    }
</script>
<style scoped>
._1adminOverveiw_table_recent{
    margin-left: 170px;
    margin-top: 50px;
}
.login_footer{
    margin-left: 145px;
}
.login_header{
    margin-left:128px
}
</style>
