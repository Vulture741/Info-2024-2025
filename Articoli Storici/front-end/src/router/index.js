import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Home from '../views/Home.vue';
import Management from '../views/Management.vue';
import Edit from '../views/Edit.vue';
import Article from '../views/Article.vue';
import Register from '../views/Register.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/home', name: 'Home', component: Home },
  { path: '/management', name: 'Management', component: Management },
  { path: '/edit/:id', name: 'Edit', component: Edit },
  { path: '/article/:id', name: 'Article', component: Article },
  {
    path: '/register',
    component: Register
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const user = JSON.parse(sessionStorage.getItem('user'));
  if ((to.path !== '/login') && !user) return next('/login');
  if (to.path === '/management' && user?.role !== 'admin') return next('/home');
  next();
});

export default router;
