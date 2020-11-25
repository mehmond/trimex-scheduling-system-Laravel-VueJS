<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Subject
                    <Button @click="addModal = true" v-if="isWritePermitted"
                        ><Icon type="md-add" /> Add</Button
                    >
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Subject Name</th>
                            <th>Subject Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Total Time</th>
                            <th>Type</th>
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
                            <td>{{ item.total_time }}</td>
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
                title="Add Subject"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="data.name"
                    placeholder="Add subject name"
                />

                <Input
                    class="pb-2"
                    v-model="data.description"
                    placeholder="Add subject description"
                />

                <Select
                    class="pb-2"
                    v-model="data.lec"
                    placeholder="Select subject lecture"
                >
                    <Option value="1">1</Option>
                    <Option value="2">2</Option>
                    <Option value="3">3</Option>
                </Select>

                <Select
                    class="pb-2"
                    v-model="data.lab"
                    placeholder="Select subject lab"
                >
                    <Option value="1">1</Option>
                    <Option value="2">2</Option>
                    <Option value="3">3</Option>
                </Select>

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

                <Select
                    class="pb-2"
                    v-model="data.type"
                    placeholder="Select subject type"
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
                title="Edit subject"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="editData.name"
                    placeholder="Add subject name"
                />

                <Input
                    class="pb-2"
                    v-model="editData.description"
                    placeholder="Add subject description"
                />

                <Select
                    class="pb-2"
                    v-model="editData.lec"
                    placeholder="Select subject location"
                >
                    <Option value="1">1</Option>
                    <Option value="2">2</Option>
                    <Option value="3">3</Option>
                </Select>

                <Select
                    class="pb-2"
                    v-model="editData.lab"
                    placeholder="Select subject location"
                >
                    <Option value="1">1</Option>
                    <Option value="2">2</Option>
                    <Option value="3">3</Option>
                </Select>

                <Select
                    class="pb-2"
                    v-model="data.course"
                    placeholder="Select course"
                >
                    <Option
                        :value="course.id"
                        v-for="(course, i) in courses"
                        :key="i"
                        v-if="courses.length"
                        >{{ course.name }}</Option
                    >
                </Select>

                <Select
                            class="pb-2"
                            v-model="data.year"
                            placeholder="Select year"
                        >
                            <Option
                                :value="year.id"
                                v-for="(year, i) in years"
                                :key="i"
                                v-if="years.length"
                                >{{ year.name }}</Option
                            >
                        </Select>

                <Select
                    class="pb-2"
                    v-model="editData.type"
                    placeholder="Select subject type"
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
export default {
    data() {
        return {
            data: {
                name: "",
                description: "",
                lec: "3",
                lab: "1",
                type: "Lecture",
                course: null,
                year: null
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            items: [],
            courses: [],
            years: [],
            editData: {
                name: "",
                description: "",
                lec: "",
                lab: "",
                type: "",
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
                return this.error("subject name is required");
            if (this.data.lec.trim() == "")
                return this.error("Subject lec is required");
            if (this.data.lab.trim() == "")
                return this.error("Subject lab is required");
            if (this.data.type.trim() == "")
                return this.error("Subject type is required");
            if (this.data.total_time.trim() == "")
                return this.error("Subject total is required");
            if (!this.data.course) return this.error("Course is required");
            if (!this.data.year) return this.error("Year is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/store_subject",
                this.data
            );
            if (res.status === 201) {
                this.items.unshift(res.data);
                this.success("Subject has been added successfully!");
                this.addModal = false;
                this.data = "";
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
            console.log(this.editData)
            if (this.editData.name.trim() == "")
                return this.error("Subject name is required");
            if (this.editData.lec.trim() == "")
                return this.error("Subject lec is required");
            if (this.editData.lab.trim() == "")
                return this.error("Subject lab is required");
            if (this.editData.type.trim() == "")
                return this.error("Subject type is required");
            if (this.data.total_time.trim() == "")
                return this.error("Subject total is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/update_subject",
                this.editData
            );
            if (res.status === 200) {
                this.items[this.index] = this.editData;
                this.success("Subject has been edited successfully!");
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
                description: item.description,
                lec: item.lec,
                lab: item.lab,
                type: item.type,
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
                deleteUrl: "app/destroy_subject",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this subject?",
                successMsg: "Subject has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },

    async created() {
        const [res, course, year] = await Promise.all([
this.callApi("get", "app/index_subject"),
this.callApi("get", "app/index_course"),
this.callApi("get", "app/index_year"),
 ]) this.callApi("get", "app/index_subject");
        if (res.status === 200) {
            this.items = res.data;
        } else {
            if (res.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (course.status === 200) {
            this.courses = course.data;
        } else {
            if (course.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (year.status === 200) {
            this.years = year.data;
        } else {
            if (year.status === 404) {
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
