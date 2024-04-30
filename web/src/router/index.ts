import { createRouter, createWebHistory } from "vue-router";
import Layout from "@/themes";

import LoginPage from "../pages/auth/LoginPage.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      redirect: "/auth/login",
    },
    {
        path: "/home",
        redirect: "/dashboard/main",
    },
    {
      path: "/auth",
      children: [
          {
              path: "/auth/login",
              name: "login",
              component: LoginPage,
          },
          /*
          {
              path: "/auth/register",
              name: 'register',
              component: RegisterPage,
          },
          {
              path: "/auth/forgot-password",
              name: 'forgot-password',
              component: ForgotPasswordPage,
          },
          {
              path: "/auth/reset-password",
              name: 'reset-password',
              component: ResetPasswordPage,
          },
          */
      ]
  },
  {
      path: "/dashboard",
      component: Layout,
      children: [
          {
              path: "/dashboard/main",
              name: "side-menu-dashboard-maindashboard",
              component: () => import("../pages/dashboard/MainDashboard.vue"),
              meta: {
                  remember: true,
              },
          },
          /*
          {
              path: "/dashboard/profile",
              name: "side-menu-dashboard-profile",
              component: ProfileView,
              meta: {
                  remember: true,
              },
          },
          {
              path: "/dashboard/company",
              children: [
                  {
                      path: "/dashboard/company/company",
                      name: "side-menu-company-company",
                      redirect: "/dashboard/company/company/list",
                      component: CompanyIndex,
                      children: [
                          {
                              path: "/dashboard/company/company/list",
                              name: "side-menu-company-company-list",
                              component: CompanyList,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/company/company/create",
                              name: "side-menu-company-company-create",
                              component: CompanyCreate,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/company/company/edit/:ulid",
                              name: "side-menu-company-company-edit",
                              component: CompanyEdit,
                              meta: {
                                  remember: true,
                              },
                          }
                      ]
                  },
                  {
                      path: "/dashboard/company/branch",
                      name: "side-menu-company-branch",
                      redirect: "/dashboard/company/branch/list",
                      component: BranchIndex,
                      children: [
                          {
                              path: "/dashboard/company/branch/list",
                              name: "side-menu-company-branch-list",
                              component: BranchList,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/company/branch/create",
                              name: "side-menu-company-branch-create",
                              component: BranchCreate,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/company/branch/edit/:ulid",
                              name: "side-menu-company-branch-edit",
                              component: BranchEdit,
                              meta: {
                                  remember: true,
                              },
                          }
                      ]
                  },
              ]
          },
          {
              path: "/dashboard/administrator",
              name: "side-menu-administrator",
              children: [
                  {
                      path: "/dashboard/administrator/user",
                      name: "side-menu-administrator-user",
                      redirect: "/dashboard/administrator/user/list",
                      component: UserIndex,
                      children: [
                          {
                              path: "/dashboard/administrator/user/list",
                              name: "side-menu-administrator-user-list",
                              component: UserList,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/administrator/user/create",
                              name: "side-menu-administrator-user-create",
                              component: UserCreate,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/administrator/user/edit/:ulid",
                              name: "side-menu-administrator-user-edit",
                              component: UserEdit,
                              meta: {
                                  remember: true,
                              },
                          }
                      ]
                  }
              ]
          },
          {
              path: "/dashboard/devtool",
              name: "side-menu-devtool",
              children: [
                  {
                      path: "/dashboard/devtool/devtool",
                      name: "side-menu-devtool-devtool",
                      component: DevTool,
                      meta: {
                          remember: false,
                      },
                  },
                  {
                      path: "/dashboard/devtool/playground",
                      name: "side-menu-devtool-playground",
                      children: [
                          {
                              path: "/dashboard/devtool/playground/p1",
                              name: "side-menu-devtool-playground-p1",
                              component: PlayOne,
                              meta: {
                                  remember: true,
                              },
                          },
                          {
                              path: "/dashboard/devtool/playground/p2",
                              name: "side-menu-devtool-playground-p2",
                              component: PlayTwo,
                              meta: {
                                  remember: true,
                              },
                          }
                      ]
                  }
              ]
          },
          {
              path: "/dashboard/error" + "/:code",
              name: "side-menu-error-code",
              component: ErrorView,
              meta: {
                  remember: false,
              },
          }
          */
      ],
  },
  /*
  {
      path: "/:pathMatch(.*)*",
      component: ErrorPage,
      meta: {
          remember: false,
      },
  },
  {
      path: "/error-page",
      name: "error-page",
      component: ErrorPage,
      meta: {
          remember: false,
      },
  }
  */
  ],
});

router.beforeEach(async (to, from, next) => {
  next();
});

router.afterEach((to, from) => {
  if (to.matched.some(r => r.meta.remember)) {
    sessionStorage.setItem('DCSLAB_LAST_ROUTE', to.name as string);
  }
});

export default router;
