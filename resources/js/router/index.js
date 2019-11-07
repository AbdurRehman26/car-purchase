import Vue from 'vue';
import Router from 'vue-router';

/**
 * Layzloading will create many files and slow on compiling, so best not to use lazyloading on devlopment.
 * The syntax is lazyloading, but we convert to proper require() with babel-plugin-syntax-dynamic-import
 * @see https://doc.laravue.dev/guide/advanced/lazy-loading.html
 */

Vue.use(Router);

/* Layout */
import Layout from '@/layout';

/* Router for modules */
// import elementUiRoutes from './modules/element-ui';
import componentRoutes from './modules/components';
import chartsRoutes from './modules/charts';
import tableRoutes from './modules/table';
import adminRoutes from './modules/admin';
import nestedRoutes from './modules/nested';
import errorRoutes from './modules/error';
import excelRoutes from './modules/excel';
import permissionRoutes from './modules/permission';

/**
 * Sub-menu only appear when children.length>=1
 * @see https://doc.laravue.dev/guide/essentials/router-and-nav.html
 **/

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    roles: ['admin', 'editor']   Visible for these roles only
    permissions: ['view menu zip', 'manage user'] Visible for these permissions only
    title: 'title'               the name show in sub-menu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if true, the page will no be cached(default is false)
    breadcrumb: false            if false, the item will hidden in breadcrumb (default is true)
    affix: true                  if true, the tag will affix in the tags-view
  }
**/

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    redirect: { name: 'Page404' },
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'users',
    children: [
      {
        path: 'users',
        component: () => import('@/views/users/List'),
        name: 'Users',
        meta: { title: 'Users', icon: 'user', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'roles',
    children: [
      {
        path: 'roles',
        component: () => import('@/views/roles/List'),
        name: 'Roles',
        meta: { title: 'Roles', icon: 'role', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'purchases',
    children: [
      {
        path: 'purchases',
        component: () => import('@/views/purchases/List'),
        name: 'Purchases',
        meta: { title: 'Purchases', icon: 'list', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'sales',
    children: [
      {
        path: 'sales',
        component: () => import('@/views/sales/List'),
        name: 'Sales',
        meta: { title: 'Sales', icon: 'list', noCache: false },
      },
    ],
  },

  {
    path: '',
    component: Layout,
    redirect: 'warranty',
    children: [
      {
        path: 'warranty',
        component: () => import('@/views/warranty/List'),
        name: 'Warranty',
        meta: { title: 'Warranty', icon: 'list', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'funding-status',
    children: [
      {
        path: 'funding-status',
        component: () => import('@/views/funding/List.vue'),
        name: 'Funding Status',
        meta: { title: 'Funding Status', icon: 'list', noCache: false },
      },
    ],
  },
  {
    path: '',
    component: Layout,
    redirect: 'make-ready',
    children: [
      {
        path: 'make-ready',
        component: () => import('@/views/make_ready/List.vue'),
        name: 'Make Ready',
        meta: { title: 'Make Ready', icon: 'list', noCache: false },
      },
    ],
  },

  {
    path: '',
    component: Layout,
    redirect: 'lenders',
    children: [
      {
        path: 'lenders',
        component: () => import('@/views/lenders/List.vue'),
        name: 'Lenders',
        meta: { title: 'Lenders', icon: 'list', noCache: false },
      },
    ],
  },

  {
    path: '',
    component: Layout,
    redirect: 'import',
    children: [
      {
        path: 'upload-excel',
        component: () => import('@/views/excel/UploadExcel'),
        name: 'UploadExcel',
        meta: { title: 'Upload Excel', icon: 'excel', noCache: false },
      },
    ],
  },

  // elementUiRoutes,
];

export const asyncRoutes = [
  // permissionRoutes,
  // componentRoutes,
  // chartsRoutes,
  // nestedRoutes,
  // tableRoutes,
  // adminRoutes,
  // {
  //   path: '/theme',
  //   component: Layout,
  //   redirect: 'noredirect',
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/theme/index'),
  //       name: 'Theme',
  //       meta: { title: 'theme', icon: 'theme' },
  //     },
  //   ],
  // },
  // errorRoutes,
  // excelRoutes,
  { path: '*', redirect: '/404', hidden: true },
];

const createRouter = () =>
  new Router({
    // mode: 'history', // require service support
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes,
  });

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
