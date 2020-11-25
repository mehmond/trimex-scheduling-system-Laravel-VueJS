<template>
    <div>
        <div class="container">
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4"
            >
                <div class="login_header pb-3">
                    <h2>Login</h2>
                </div>
                <Input
                    class="pb-2"
                    type="email"
                    placeholder="Email"
                    v-model="data.email"
                    ><Icon type="ios-person-outline" slot="prepend" />
                </Input>
                <Input
                    class="pb-2"
                    type="password"
                    placeholder="Password"
                    v-model="data.password"
                >
                    <Icon type="ios-lock-outline" slot="prepend" />
                </Input>
                <div class="login_footer">
                    <router-link to='/register'>
                    <Button type="default" shape="circle"
                        >Register</Button
                    ></router-link>
                    <Button
                        :disabled="isLogging"
                        :loading="isLogging"
                        type="primary"
                        shape="circle"
                        @click="login"
                        >{{ isLogging ? "Loging in..." : "Login" }}</Button
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                email: "",
                password: ""
            },
            isLogging: false
        };
    },
    methods: {
        async login() {
            if (this.data.email.trim() == "")
                return this.error("Email is required");
            if (this.data.password.trim() == "")
                return this.error("Password is required");
            if (this.data.password.length < 6)
                return this.error(
                    "Password is should be minimum of 6 characters"
                );
            this.isLogging = true;
            const res = await this.callApi(
                "post",
                "app/login",
                this.data
            );
            if (res.status === 200) {
                this.success(res.data.msg);
                window.location = "/";
            } else {
                if (res.status === 401) {
                    this.info(res.data.msg);
                } else if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
            this.isLogging = false;
        }
    }
};
</script>

<style scoped>
._1adminOverveiw_table_recent {
    margin: 0 auto;
    margin-top: 200px;
}
.login_footer {
    text-align: right;
}
.login_header {
    text-align: center;
}
</style>
