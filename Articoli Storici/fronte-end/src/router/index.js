import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Management from '../views/Management.vue';
import Edit from '../views/Edit.vue';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true }
  },
  {
    path: '/home',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true }
  },
  {
    path: '/management',
    name: 'Management',
    component: Management,
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/edit/:id',
    name: 'Edit',
    component: Edit,
    meta: { requiresAuth: true, requiresAdmin: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const user = JSON.parse(sessionStorage.getItem('user'));

  if (to.meta.requiresAuth && !user) {
    next('/login');
  } else if (to.meta.requiresAdmin && user?.role !== 'admin') {
    next('/home');
  } else if (to.meta.requiresGuest && user) {
    next('/home');
  } else {
    next();
  }
});

export default router;
