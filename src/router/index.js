import {createRouter, createWebHistory} from 'vue-router'
import store from '@/store'
import axios from "../axios/axios";

const routes = [
    {
        path: '/',
        name: 'home-texts',
        redirect: '/texts'
    },
    {
        path: '/signup',
        name: 'signup',
        meta: {layout: 'center'},
        component: () => import('@/views/Signup'),
    },
    {
        path: '/login',
        name: 'login',
        meta: {layout: 'center'},
        component: () => import('@/views/Login'),
    },
    {
        path: '/texts',
        name: 'texts',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/Texts'),
    },
    {
        path: '/text/:id',
        name: 'text',
        meta: {layout: 'empty', auth: true},
        component: () => import('@/views/children/Text'),
    },
    {
        path: '/dictionaries',
        name: 'dictionaries',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/Dictionaries'),
    },
    {
        path: '/dictionary/:id',
        name: 'dictionary',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/children/Dictionary'),
    },
    {
        path: '/statistics',
        name: 'statistics',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/Statistics')
    },
    {
        path: '/simulators',
        name: 'simulators',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/Simulators'),
    },
    {
        path: '/simulator/:id',
        name: 'simulator',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/children/Simulator'),
    },
    {
        path: '/admin',
        name: 'admin',
        meta: {layout: 'main', auth: true},
        component: () => import('@/views/Admin'),
        beforeEnter(to, from, next) {
            if (localStorage.currentUser !== undefined) {
                const role = JSON.parse(localStorage.currentUser).role
                if (role === 'ADMIN') {
                    next();
                } else {
                    next('/')
                }
            }
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notFound',
        meta: {layout: 'main'},
        component: () => import('@/views/NotFound'),
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    linkActiveClass: 'active',
    routes
})

router.beforeEach((to ,from, next) => {
    // const dataLogin = {
    //     email: "garshin@gmail.com",
    //     password: "123"
    // };
    //
    // console.log(dataLogin);
    //
    // fetch('http://api/login', {
    //     credentials: 'include',
    //     method: 'POST',
    //     headers: {
    //         'Accept': '*/*',
    //         'Content-Type': 'application/json; charset=UTF-8',
    //     },
    //     body: JSON.stringify(dataLogin)
    // })
    //     .then(res => res.json())
    //     .then(data => console.log('Login:', data))
    //
    // fetch('http://api/check', {
    //     credentials: 'include',
    //     method: 'GET',
    //     headers: {
    //         'Accept': '*/*',
    //         'Content-Type': 'application/json; charset=UTF-8',
    //         'Set-Cookie': 'flavor=choco; SameSite=None; Secure'
    //     }
    // })
    //     .then(res => res.json())
    //     .then(data => console.log('Fetch Check:', data))

    // fetch('http://api/logout', {
    //     credentials: 'include',
    //     method: 'GET',
    //     headers: {
    //         'Accept': '*/*',
    //         'Content-Type': 'application/json; charset=UTF-8',
    //     }
    // })
    //     .then(res => res.json())
    //     .then(data => console.log('Logout:', data))

    let currentUserTEST = {};
    let isAuth = false;

    if (localStorage.currentUser !== undefined) {
        currentUserTEST = JSON.parse(localStorage.currentUser)
    }

    if (localStorage.currentUser !== undefined && currentUserTEST.email) {
        isAuth = true;
        currentUserTEST = JSON.parse(localStorage.currentUser);
        // console.log('currentUserTEST', currentUserTEST);
        // console.log('isAuth', isAuth);

        const requiredAuth = to.matched.some(record => record.meta.auth);

        // console.log('to:', to, ' | ', 'currentUser:', currentUser, ' | ', 'requiredAuth:', requiredAuth);

        if (isAuth && (to.path === '/signup' || to.path === '/login')) {
            // console.log('Уже авторизован')
            next('/texts');
        }
        else if (!requiredAuth) {
            // console.log('Авторизация не требуется');
            next();
        } else if (!isAuth && requiredAuth) {
            // console.log('Пользователь не авторизован, переход не разрешен!')
            next('/login')
        } else if (isAuth && requiredAuth) {
            // console.log('Пользователь авторизован, переход разрешен!')

            console.log('to', to)

            next();
        }
    } else {
        const requiredAuth = to.matched.some(record => record.meta.auth);

        // console.log('currentUserTEST', currentUserTEST);
        // console.log('isAuth', isAuth);

        // console.log('to:', to, ' | ', 'currentUser:', currentUser, ' | ', 'requiredAuth:', requiredAuth);

        if (!requiredAuth) {
            // console.log('Авторизация не требуется');
            next();
        } else if (!isAuth && requiredAuth) {
            // console.log('Пользователь не авторизован, переход не разрешен!')
            next('/login')
        } else if (isAuth && requiredAuth) {
            // console.log('Пользователь авторизован, переход разрешен!')
            next();
        }
    }
});

export default router
