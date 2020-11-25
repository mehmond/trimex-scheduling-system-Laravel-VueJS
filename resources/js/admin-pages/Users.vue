<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Users
                    <!-- <Button @click="addModal = true"
                            ><Icon type="md-add" /> Add admin</Button
                        > -->
                </p>
                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th hidden>Role ID</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->

                        <!-- ITEMS -->
                        <tr
                            v-for="(item, i) in items"
                            :key="i"
                            v-if="items.length"
                        >
                            <td>{{ item.id }}</td>
                            <td class="_table_name">
                                {{ item.full_name }}
                            </td>
                            <td class="">{{ item.email }}</td>
                            <td hidden>{{ item.role_id }}</td>
                            <td>{{ item.name }}</td>
                            <td>
                                <Button
                                    type="info"
                                    size="small"
                                    @click="showEditingModal(item, i)"
                                    >Edit</Button
                                >
                                <Button
                                    type="error"
                                    size="small"
                                    @click="showDeletingModal(item, i)"
                                    :loading="item.isDeleting"
                                    >Delete</Button
                                >
                            </td>
                        </tr>
                        <!-- ITEMS -->
                    </table>
                </div>
            </div>

            <!--~~~~~~~~~~~~~~~~~~~ EDIT MODAL ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <Modal
                v-model="editModal"
                title="Edit Admin"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    type="text"
                    v-model="editData.full_name"
                    placeholder="Full name"
                />

                <Input
                    class="pb-2"
                    type="email"
                    v-model="editData.email"
                    placeholder="Email"
                />

                <Input
                    class="pb-2"
                    type="password"
                    v-model="editData.password"
                    placeholder="Password"
                />

                <Select
                    class="pb-2"
                    v-model="editData.name"
                    placeholder="Select user type"
                >
                    <Option
                        :value="role.name"
                        v-for="(role, i) in roles"
                        :key="i"
                        v-if="roles.length"
                        >{{ role.name }}</Option
                    >
                </Select>
                <div slot="footer">
                    <Button
                        type="default"
                        @click="(editModal = false), (isAdding = false)"
                        >Close</Button
                    >
                    <Button
                        type="primary"
                        @click="update"
                        :disabled="isAdding"
                        :loading="isAdding"
                        >{{ isAdding ? "Editing.." : "Edit" }}</Button
                    >
                </div>
            </Modal>
            <deleteModal />
        </div>
    </div>
</template>

<script>
import deleteModal from "../components/DeleteModal.vue";
import { mapGetters } from "vuex";
export default {
    data() {
        return {
            editModal: false,
            isAdding: false,
            items: [],
            editData: {
                full_name: "",
                email: "",
                password: "",
                name: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            roles: []
        };
    },

    methods: {
        async update() {
            if (this.editData.full_name.trim() == "")
                return this.error("User name is required");
            if (this.editData.email.trim() == "")
                return this.error("Email is required");
            if (this.editData.name.trim() == "")
                return this.error("User type is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/update_user",
                this.editData
            );
            if (res.status === 200) {
                this.items[this.index] = this.editData;
                this.success("User has been edited successfully!");
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
                this.isAdding = false;
            }
            this.isAdding = false;
        },
        showEditingModal(item, index) {
            let obj = {
                id: item.id,
                full_name: item.full_name,
                email: item.email,
                name: item.name
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        showDeletingModal(item, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/destroy_user",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this course?",
                successMsg: "Course has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },
    async created() {
        const [res, resRole] = await Promise.all([
            this.callApi("get", "app/index_users"),
            this.callApi("get", "app/get_roles")
        ]);
        if (res.status === 200) {
            this.items = res.data;
        } else {
            this.swr();
        }
        if (resRole.status === 200) {
            this.roles = resRole.data;
        } else {
            this.swr();
        }
    },
    components: {
        deleteModal
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                this.items.splice(this.deletingIndex, 1);
            }
        }
    }
};
</script>
