<template>
    <div>
        <div class="container">
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4"
            >
                <div class="login_header pb-3">
                    <!-- <img
                        src="/images/icon.png"
                        style="width: 80px; height: 80px;   margin-left: auto;
  margin-right: auto;"
                    /> -->
                    <h2>Register</h2>
                </div>
                <Input
                    class="pb-2"
                    type="email"
                    placeholder="Full Name"
                    v-model="data.full_name"
                    ><Icon type="ios-person-outline" slot="prepend" />
                </Input>
                <Input
                    class="pb-2"
                    type="email"
                    placeholder="Email"
                    v-model="data.email"
                    ><Icon type="ios-mail-outline" slot="prepend" />
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
                    <router-link to="/login">
                        <Button type="default" shape="circle">Cancel</Button>
                    </router-link>
                    <Button
                        :disabled="isRegister"
                        :loading="isRegister"
                        type="primary"
                        shape="circle"
                        @click="add"
                        >{{
                            isRegister ? "Registering ..." : "Register"
                        }}</Button
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
                full_name: "",
                password: "",
                email: ""
            },
            isRegister: false
        };
    },
    methods: {
        async add() {
            console.log(this.data)
            this.isRegister = true;
            if (this.data.full_name.trim() == "")
                return this.error("Name is required");
            if (this.data.email.trim() == "")
                return this.error("Email is required");
            if (this.data.password.trim() == "")
                return this.error("Password is required");
            if (this.data.password.length < 6)
                return this.error(
                    "Password is should be minimum of 6 characters"
                );
            const res = await this.callApi(
                "post",
                "app/register",
                this.data
            )
            if (res.status === 201) {
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
            this.isRegister = false
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
