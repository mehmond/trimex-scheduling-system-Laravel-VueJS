<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Room
                    <Button @click="addModal = true" v-if="isWritePermitted"
                        ><Icon type="md-add" /> Add</Button
                    >
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Room Name</th>
                            <th>Campus</th>
                            <th>Room Type</th>
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
                            <td>{{ item.campus_name }}</td>
                            <td>{{ item.type }}</td>
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
                title="Add Room"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="data.name"
                    placeholder="Add room name"
                />

                <Select
                    class="pb-2"
                    v-model="data.campus_name"
                    placeholder="Select room location"
                >
                    <Option
                        :value="campus.name"
                        v-for="(campus, i) in campuses"
                        :key="i"
                        v-if="campuses.length"
                        >{{ campus.name }}</Option
                    >
                </Select>

                <Select
                    class="pb-2"
                    v-model="data.type"
                    placeholder="Select room type"
                >
                    <Option value="Lecture">Lecture</Option>
                    <Option value="Computer Laboratory"
                        >Computer Laboratory</Option
                    >
                    <Option value="Electrical Laboratory"
                        >Electrical Laboratory</Option
                    >
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
                title="Edit room"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="editData.name"
                    placeholder="Add room name"
                />

                <Select
                    class="pb-2"
                    v-model="editData.campus_name"
                    placeholder="Select room location"
                >
                    <Option
                        :value="campus.name"
                        v-for="(campus, i) in campuses"
                        :key="i"
                        v-if="campuses.length"
                        >{{ campus.name }}</Option
                    >
                </Select>

                <Select
                    class="pb-2"
                    v-model="editData.type"
                    placeholder="Select room type"
                >
                    <Option value="Lecture">Lecture</Option>
                    <Option value="Computer Laboratory"
                        >Computer Laboratory</Option
                    >
                    <Option value="Electrical Laboratory"
                        >Electrical Laboratory</Option
                    >
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
import $ from 'jquery';
export default {
    data() {
        return {
            data: {
                name: "",
                campus_name: "Main",
                type: "Lecture"
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            items: [],
            campuses: [],
            editData: {
                name: "",
                campus_name: "",
                type: ""
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
                return this.error("Room name is required");
            if (this.data.campus_name.trim() == "")
                return this.error("Room location is required");
            if (this.data.type.trim() == "")
                return this.error("Room type is required");
            this.isAdding = true;
            const res = await this.callApi("post", "app/store_room", this.data);
            if (res.status === 201 || res.status === 200) {
                this.items.unshift(res.data);
                console.log( 'this is your data',res.data)
                this.success("Room has been added successfully!");
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
                return this.error("Room name is required");
            if (this.editData.campus_name.trim() == "")
                return this.error("Room location is required");
            if (this.editData.type.trim() == "")
                return this.error("Room type is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/update_room",
                this.editData
            );
            if (res.status === 200) {
                this.items[this.index] = this.editData;
                this.success("Room has been edited successfully!");
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
                name: item.name,
                campus_name: item.campus_name,
                type: item.type
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        showDeletingModal(item, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/destroy_room",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this room?",
                successMsg: "Room has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },

    async created() {
        this.$Loading.start();
        const [res, resCampus] = await Promise.all([
            this.callApi("get", "app/index_room"),
            this.callApi("get", "app/index_campus")
        ]);
        if (res.status === 200) {
             this.$Loading.finish();
            this.items = res.data;
        } else {
            if (res.status === 404) {
                this.$Loading.error();
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (resCampus.status === 200) {
            this.campuses = resCampus.data;
        } else {
            if (resCampus.status === 404) {
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
