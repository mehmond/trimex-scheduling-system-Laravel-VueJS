<template>
    <div>
        <div v-if="$store.state.user">
            <!--==========  ADMIN SIDE MENU one ========-->
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #8B0000;">
                <span class="navbar-brand" href="#" style="border-right: 1px solid #333">Trimex &nbsp;</span>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <ul class="navbar-nav mr-auto">
                        <li
                            class="nav-item pr-3"
                            v-for="(menuItem, i) in permission"
                            :key="i"
                            v-if="permission.length && menuItem.read"
                            
                        >
                            <router-link :to="'/' + menuItem.name"
                                >
                                {{ menuItem.resourceName }}</router-link
                            >
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li>
                            <a href="/logout"
                                ><Icon type="ios-exit-outline" />Logout</a
                            >
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <router-view />
    </div>
</template>

<script>
export default {
    props: ["user", "permission"],
    data() {
        return {};
    },
    created() {
        this.$store.commit("setUpdateUser", this.user);
        this.$store.commit("setUserPermission", this.permission);
    }
};
</script>
