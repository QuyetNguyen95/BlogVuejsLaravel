<template>
  <div>
    <div v-if="$store.state.user">
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu">
        <div class="_1side_menu_logo">
          <h3 style="text-align: center">Logo Image</h3>
          <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content">
          <div class="_1side_menu_img_name">
            <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
            <p class="_1side_menu_name">Admin</p>
          </div>

          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">
              <!--  <li v-for="(menuItem, i) in permission" :key="i" v-if="permission.length && menuItem.read">
                <router-link :to="'/'+menuItem.name"><Icon type="ios-speedometer" /> {{menuItem.resourceName}}</router-link>
              </li> -->

                <li v-for="(p,i) in permission" :key="i" v-if="permission.length && p.read"><router-link :to="'/'+p.name"><Icon type="ios-speedometer" /> {{p.resourceName}}</router-link></li>
<!--                <li><router-link to="/category"><Icon type="md-appstore" /> Category</router-link></li>-->
<!--                <li><router-link to="/tags"><Icon type="md-pricetags" /> Tags</router-link></li>-->
<!--                <li><router-link to="/adminUser"><Icon type="md-person" /> AdminUsers</router-link></li>-->
<!--                <li><router-link to="/role"><Icon type="md-person" /> Role</router-link></li>-->
<!--                <li><router-link to="/assign"><Icon type="md-person" /> AssignRole</router-link></li>-->
                <li>
                    <a   @click="logout"><Icon type="md-log-in" /> Logout</a>
                </li>
            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header">
        <div class="_2menu _box_shadow">
          <div class="_2menu_logo">
            <ul class="open_button">
              <li>
                <Icon type="ios-list" />
              </li>
              <!--<li><Icon type="ios-albums" /></li>-->
            </ul>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
    <router-view />
  </div>
</template>
<script>
export default {
    props:['user','permission'],
    methods:{
        async logout(){
            const res = await this.callApi('get','/app/logout');
           if (res.data.code === 1){
               window.location.href = '/login'
           }
        },
    },
    created() {
        this.$store.commit('setUpdateUser',this.user)
        this.$store.commit('setPermissionUser',this.permission)
    },
}
</script>
