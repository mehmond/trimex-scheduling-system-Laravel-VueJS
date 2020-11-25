<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Preferred Subject
                    <Button @click="addModal = true" v-if="isWritePermitted"
                        ><Icon type="md-add" /> Add</Button
                    >
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Subject Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
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
                            <td>{{ item.description }}</td>
                            <td>{{ item.lec }}</td>
                            <td>{{ item.lab }}</td>
                            <td v-if="isUpdatePermitted || isDeletePermitted">
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
                title="Add Preffered Subjects"
                :mask-closable="false"
                :closable="false"
            >
                <Select
                    class="pb-2"
                    v-model="data.subject"
                    filterable
                    multiple
                    placeholder="Select subjects"
                    not-found-text="No subject"
                >
                    <Option
                        :value="subject.id"
                        v-for="(subject, i) in subjects"
                        :key="i"
                        >{{ subject.name }}</Option
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
                subject: []
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            items: [],
            subjects: [],
            editData: {
                name: ""
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
            if (!this.data.subject)
                return this.error("Preffered Subjects is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/store_preferred_subject",
                this.data
            );
            if (res.status === 200) {
                location.reload();
                this.success("Preffered Subject has been added successfully!");
                this.addModal = false;
                this.data.subject = [];
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
        showDeletingModal(item, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/destroy_preferred_subject",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this course?",
                successMsg: "Preferred Subject has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },

    async created() {
        const [res, subject] = await Promise.all([
            this.callApi("get", "app/index_preferred_subject"),
            this.callApi("get", "app/index_filtered_subject")
        ]);
        if (res.status === 200) {
            this.items = res.data;
        } else {
            if (res.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (subject.status === 200) {
            this.subjects = subject.data;
        } else {
            if (subject.status === 404) {
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
