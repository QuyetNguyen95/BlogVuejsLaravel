import Vue from 'vue'
import Router from 'vue-router'
import DashboardComponent from './components/DashboardComponent.vue'
import CategoryComponent from './components/CategoryComponent.vue'
import Tags from './components/Tags.vue'
import AdminUserComponent from './components/AdminUserComponent'
import LoginComponent from './components/LoginComponent'
import RoleComponent from './components/RoleComponent'
import AssignRole from "./components/AssignRole";
Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardComponent
        },
        {
            path: '/tags',
            name: 'tags',
            component: Tags
        },
        {
            path: '/category',
            name: 'category',
            component: CategoryComponent
        },
        {
            path: '/adminUser',
            name: 'adminUser',
            component: AdminUserComponent
        },
        {
            path: '/login',
            name:'login',
            component: LoginComponent
        },
        {
            path: '/role',
            name:'role',
            component: RoleComponent
        },
        {
            path: '/assign',
            name:'assign',
            component: AssignRole
        }
    ]
})
