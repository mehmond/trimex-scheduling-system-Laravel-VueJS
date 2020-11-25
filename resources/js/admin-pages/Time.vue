<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Time
                    <Button @click="addModal = true" v-if="isWritePermitted"
                        ><Icon type="md-add" /> Add</Button
                    >
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Time</th>
                            <th>Total Time</th>
                            <th v-if="isUpdatePermitted || isDeletePermitted">
                                Action
                            </th>
                        </tr>
                        <!-- TABLE TITLE -->

                        <!-- ITEMS -->
                        <tr
                            v-for="(item, i) in items"
                            :key="i"
                            v-if="items.length"
                        >
                            <td>{{ item.id }}</td>
                            <td class="_table_name">{{ item.name }}</td>
                            <td>{{ item.total_time }}</td>
                            <td v-if="isUpdatePermitted || isDeletePermitted">
                                <Button
                                    type="info"
                                    size="small"
                                    v-if="isUpdatePermitted"
                                    @click="showEditingModal(item, i)"
                                    >Edit</Button
                                >
                                <Button
                                    type="error"
                                    size="small"
                                    @click="showDeletingModal(item, i)"
                                    v-if="isDeletePermitted"
                                    :loading="item.isDeleting"
                                    >Delete</Button
                                >
                            </td>
                        </tr>
                        <!-- ITEMS -->
                    </table>
                </div>
            </div>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~ ADD MODAL ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <Modal
                v-model="addModal"
                title="Add Time"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="data.name"
                    placeholder="Add time ex. 07:30-10:30, 13:30-15:30"
                />

                <Select
                    class="pb-2"
                    v-model="data.total_time"
                    placeholder="Select subject total time"
                >
                    <Option value="1">1</Option>
                    <Option value="1.5">1.5</Option>
                    <Option value="2">2</Option>
                    <Option value="2.5">2.5</Option>
                    <Option value="3">3</Option>
                </Select>

                <div slot="footer">
                    <Button type="default" @click="addModal = false"
                        >Close</Button
                    >
                    <Button
                        type="primary"
                        @click="store"
                        :disabled="isAdding"
                        :loading="isAdding"
                        >{{ isAdding ? "Adding.." : "Add" }}</Button
                    >
                </div>
            </Modal>
            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~ EDIT MODAL ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <Modal
                v-model="editModal"
                title="Edit time"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="editData.name"
                    placeholder="Edit time ex. 07:30-10:30, 13:30-15:30"
                />

                <Select
                    class="pb-2"
                    v-model="editData.total_time"
                    placeholder="Select total time"
                >
                    <Option value="1">1</Option>
                    <Option value="1.5">1.5</Option>
                    <Option value="2">2</Option>
                    <Option value="2.5">2.5</Option>
                    <Option value="3">3</Option>
                </Select>

                <div slot="footer">
                    <Button type="default" @click="editModal = false"
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
            <deleteModal></deleteModal>
        </div>
    </div>
</template>

<script>
import deleteModal from "../components/DeleteModal.vue";
import { mapGetters } from "vuex";
export default {
    data() {
        return {
            data: {
                name: "",
                total_time: "3"
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            items: [],
            editData: {
                name: "",
                total_time: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        };
    },

    methods: {
        async store() {
            if (this.data.name.trim() == "")
                return this.error("Time name is required");
            if (this.data.total_time.trim() == "")
                return this.error("Total time is required");
            this.isAdding = true;
            const res = await this.callApi("post", "app/store_time", this.data);
            if (res.status === 201) {
                this.items.unshift(res.data);
                this.success("Time has been added successfully!");
                this.addModal = false;
                this.data.name = "";
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
        async update() {
            if (this.editData.name.trim() == "")
                return this.error("Time  is required");
            if (this.editData.total_time.trim() == "")
                return this.error("Total time is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/update_time",
                this.editData
            );
            if (res.status === 200) {
                this.items[this.index] = this.editData;
                this.success("Time has been edited successfully!");
                this.editModal = false;
            } else {
                if (res.status == 422) {
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
                name: item.name,
                total_time: item.total_time
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        showDeletingModal(item, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/destroy_time",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this time?",
                successMsg: "Time has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },

    async created() {
        const res = await this.callApi("get", "app/index_time");
        if (res.status === 200) {
            this.items = res.data;
        } else {
            if (res.status === 404) {
                this.error("No Records Found!");
            }
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
