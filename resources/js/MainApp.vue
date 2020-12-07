<template>
    <div>
        <div v-if="$store.state.user">
            <nav>
                <div class="logo" style="border-right: 1px solid #333">
                    <h3>TRIMEX</h3>
                </div>
                <ul class="nav-links">
                    <li
                        @click="click"
                        v-for="(menuItem, i) in permission"
                        :key="i"
                        v-if="permission.length && menuItem.read"
                    >
                        <router-link :to="'/' + menuItem.name">
                            {{ menuItem.resourceName }}</router-link
                        >
                    </li>
                </ul>
                <div class="burger" @click="click">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </div>
                <div class="dropdownko">
                    <Dropdown trigger="click" style="margin-left: 20px">
                        <a href="javascript:void(0)">
                            Menu
                            <Icon type="ios-arrow-down"></Icon>
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem>
                                <a href="/logout"
                                    ><Icon type="ios-exit-outline" />Logout</a
                                ></DropdownItem
                            >
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </nav>

            <!-- ==========  ADMIN SIDE MENU one ========
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
            </nav> -->
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
    },
    methods: {
        click() {
            const nav = document.querySelector(".nav-links");
            const navLinks = document.querySelectorAll(".nav-links li");
            nav.classList.toggle("nav-active");
            navLinks.forEach((link, index) => {
                if (link.style.animation) {
                    link.style.animation = "";
                } else {
                    link.style.animation = `navLinkFade 0.5 ease forwards ${index /
                        7 +
                        1.5}s`;
                }
            });
        }
    }
};
</script>

<style scoped>
* {
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}
nav {
    display: flex;
    align-items: center;
    min-height: 8vh;
    background-color: maroon;
    font-family: "Poppins", sans-serif;
}
.logo {
    color: black;
    text-transform: uppercase;
    letter-spacing: 5px;
    font-size: 20px;
}
.nav-links {
    width: 100%;
    display: flex;
}

.nav-links li {
    list-style: none;
}

.nav-links2 li {
    list-style: none;
    width: 100%;
}
.nav-links a {
    float: left;
    padding: 12px;
    color: white;
    text-decoration: none;
    font-size: 17px;
    width: 100%; /* Four equal-width links. If you have two links, use 50%, and 33.33% for three links, etc.. */
    text-align: center; /* If you want the text to be centered */
}
.burger {
    display: none;
    cursor: pointer;
}
.burger div {
    width: 25px;
    height: 2px;
    background-color: blanchedalmond;
    margin: 5px;
}
.dropdownko {
    right: 10px;
    width: 10%;
}
@media screen and (max-width: 1024px) {
    .nav-links {
        width: 20%;
        display: inline-flex;
    }
}
@media screen and (max-width: 768px) {
    .nav-links {
        position: absolute;
        right: 0px;
        height: 100vh;
        top: 8vh;
        background-color: maroon;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        opacity: 0;
        visibility: hidden;
    }
    .burger {
        display: block;
        float: right;
    }
    .dropdownko {
        right: 0px;
        width: 100%;
    }
    
}

.nav-active {
    opacity: 1;
    visibility: visible;
    z-index: 100;
}
@keyframes navLinkFade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
