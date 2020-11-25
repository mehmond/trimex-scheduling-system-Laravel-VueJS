import Router from "vue-router";
import Vue from "vue";
Vue.use(Router);
//Components
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
//Admin
import Home from "./admin-pages/Home.vue";
import Users from "./admin-pages/Users.vue";
import Course from "./admin-pages/Course.vue";
import Campus from "./admin-pages/Campus.vue";
import Room from "./admin-pages/Room.vue";
import Subject from "./admin-pages/Subject.vue";
import AssignRole from "./admin-pages/AssignRole.vue";
import Time from "./admin-pages/Time.vue";
import Section from "./admin-pages/Section.vue";
import SectionShow from "./admin-pages/SectionShow.vue";
import SectionEdit from "./admin-pages/SectionEdit.vue";
import NotFound from "./admin-pages/NotFound.vue";
//Faculty
import PreferredSubject from "./faculty-pages/PreferredSubject.vue";
import PreferredTime from "./faculty-pages/PreferredTime.vue";

const routes = [
    {
        path: "/login",
        component: Login,
        name: "login"
    },
    {
        path: "/register",
        component: Register,
        name: "register"
    },

    {
        path: "/",
        component: Home,
        name: "home"
    },
    {
        path: "/users",
        component: Users,
        name: "users"
    },
    {
        path: "/course",
        component: Course,
        name: "course"
    },
    {
        path: "/section",
        component: Section,
        name: "section"
    },
    {
        path: '/section/:id',
        component: SectionShow,
        name: 'showsection'

    },
    {
        path: '/editsection/:id',
        component: SectionEdit,
        name: 'editsection'

    },
    {
        path: '*',
        component: NotFound,
        name: 'notfound'

    },
    {
        path: "/subject",
        component: Subject,
        name: "subject"
    },
    {
        path: "/campus",
        component: Campus,
        name: "campus"
    },
    {
        path: "/room",
        component: Room,
        name: "room"
    },
    {
        path: "/time",
        component: Time,
        name: "time"
    },
    {
        path: "/assign-role",
        component: AssignRole,
        name: "assign-role"
    },

    /// FACULTY ROUTES
    {
        path: "/preferred-subject",
        component: PreferredSubject,
        name: "preferred-subject"
    },
    {
        path: "/preferred-time",
        component: PreferredTime,
        name: "preferred-time"
    },
];

export default new Router({
    mode: "history",
    routes
});
