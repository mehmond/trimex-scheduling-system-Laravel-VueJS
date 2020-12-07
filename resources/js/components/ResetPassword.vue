<template>
    <div>
        <div class="container">
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4"
            >
                <div class="alert alert-primary">
                    <form v-on:submit.prevent="sendToken">
                        <div class="form-group">
                            <label>Email</label>
                            <input
                                type="text"
                                class="form-control"
                                v-bind:class="{
                                    'is-invalid': errorEmail,
                                    'is-valid': infoEmail
                                }"
                                placeholder="Enter email here..."
                                v-model="email"
                            />
                            <div class="invalid-feedback">
                                {{ errorEmail }}
                            </div>
                            <div class="valid-feedback">
                                {{ infoEmail }}
                            </div>
                        </div>
                        <button class="btn btn-secondary" type="submit">
                            Submit Email
                        </button>
                    </form>
                </div>

                <div class="alert alert-secondary" v-if="emailOK">
                    <form v-on:submit.prevent="validateToken">
                        <div class="form-group">
                            <label>Token</label>
                            <input
                                type="text"
                                class="form-control"
                                v-bind:class="{
                                    'is-invalid': errorToken,
                                    'is-valid': infoToken
                                }"
                                placeholder="Token..."
                                v-model="token"
                            />
                            <div class="invalid-feedback">
                                {{ errorToken }}
                            </div>
                            <div class="valid-feedback">
                                {{ infoToken }}
                            </div>
                        </div>
                        <button class="btn btn-secondary" type="submit">
                            Validate Token
                        </button>
                    </form>
                </div>

                <div class="alert alert-success" v-if="tokenValid">
                    <form v-on:submit.prevent="palitpassword">
                        <div class="form-group">
                            <label>New Password</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="newPassword"
                                v-bind:class="{
                                    'is-invalid': errorNewPassword
                                }"
                                placeholder="New Password..."
                            />
                            <div class="invalid-feedback">
                                {{ errorNewPassword }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Repeat Password</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="repeatPassword"
                                v-bind:class="{
                                    'is-invalid': errorRepeatPassword
                                }"
                                placeholder="Repeat New Password..."
                            />
                            <div class="invalid-feedback">
                                {{ errorRepeatPassword }}
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">
                        Change Password
                    </button>
                    </form>   
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "password-reset",
    data() {
        return {
            email: "",
            errorEmail: null,
            infoEmail: null,
            token: "",
            errorToken: null,
            infoToken: null,
            newPassword: "",
            errorNewPassword: null,
            repeatPassword: "",
            errorRepeatPassword: null,
            tokenValid: false,
            user: null,
            emailOK: false
        };
    },
    methods: {
        sendToken() {
            this.errorEmail = null;

            if (!this.email) {
                this.errorEmail = "Email address is required...";
            }

            if (!this.errorEmail) {
                const data = {
                    email: this.email
                };
                axios
                    .post("send-token", data)
                    .then(() => {
                        this.emailOK = true;
                        this.infoEmail = "Email sent.";
                    })
                    .catch(error => {
                        this.errorEmail = error.response.data.error;
                    });
            }
        },
        validateToken() {
            this.errorToken = null;
            if (!this.token) {
                this.errorToken = "Token  is required...";
            }
            if (!this.errorToken) {
                const data = {
                    token: this.token
                };
                axios
                    .post("validate-token", data)
                    .then(response => {
                        if (response.data.id) {
                            this.tokenValid = true;
                            this.infoToken = "Token is valid..";
                            this.user = response.data;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        palitpassword() {
            console.log(this.user)
            this.errorNewPassword = null;
            this.errorRepeatPassword = null;
            console.log(this.newPassword)
            console.log(this.repeatPassword)
            if (!this.newPassword) {
                this.errorNewPassword = "New password is required!";
            }
            if (!this.repeatPassword) {
                this.errorRepeatPassword = "Password confirmation is required!";
            }
            if (this.newPassword !== this.repeatPassword) {
                this.errorRepeatPassword = "Password do not match!";
            }
            console.log(this.newPassword)
            console.log(this.repeatPassword)
            if (!this.errorNewPassword && !this.errorRepeatPassword) {
                const data = {
                    password: this.newPassword,
                    user_id: this.user.id
                };
                axios.post("reset-password", data).then(response => {
                    console.log(response.data)
                    //this.$router.push("/login");
                });
            }
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
