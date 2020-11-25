<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <p class="_title0">
                    Section
                    <Button @click="addModal = true" v-if="isWritePermitted"
                        ><Icon type="md-add" /> Add</Button
                    >
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Section Name</th>
                            <th>
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
                            <td>
                                <Button
                                    type="primary"
                                    size="small"
                                    @click="$router.push(`/section/${item.id}`)"
                                    >Show</Button
                                >
                                <Button
                                    type="info"
                                    size="small"
                                    v-if="isUpdatePermitted"
                                    @click="
                                        $router.push(`/editsection/${item.id}`)
                                    "
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
                title="Add Section"
                :mask-closable="false"
                :closable="false"
            >
                <Input
                    class="pb-2"
                    v-model="data.name"
                    placeholder="Add section name"
                />
                <Row>
                    <Col span="12" class="pr-2">
                        <Select
                            v-model="data.sem"
                            placeholder="Select semester"
                        >
                            <Option value="1st">1st</Option>
                            <Option value="2nd">2nd</Option>
                            <Option value="Summer Class">Summer Class</Option>
                        </Select>
                    </Col>
                    <Col span="12"
                        ><Input
                            class="pb-2"
                            v-model="data.ay"
                            placeholder="Annual year(ex. 2020-2021)"
                    /></Col>
                </Row>

                <Select
                    class="pb-2"
                    v-model="data.day"
                    filterable
                    multiple
                    placeholder="Select days"
                    not-found-text="No day"
                >
                    <Option :value="day.id" v-for="(day, i) in days" :key="i">{{
                        day.name
                    }}</Option>
                </Select>

                <Select
                    class="pb-2"
                    v-model="data.time"
                    filterable
                    multiple
                    placeholder="Select time to use"
                    not-found-text="No time"
                >
                    <Option
                        :value="time.id"
                        v-for="(time, i) in times"
                        :key="i"
                        >{{ time.name }}</Option
                    >
                </Select>
                <Row>
                    <Col span="8" class="pr-2"
                        ><Select
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
                        </Select></Col
                    >
                    <Col span="8" class="pr-2"
                        ><Select
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
                        </Select></Col
                    >
                    <Col span="8">
                        <Button type="primary" :long="true" @click="getSubjects"
                            >Get Subjects</Button
                        >
                    </Col>
                </Row>

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
                        >{{ subject.name }} - {{ subject.description }}</Option
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
import $ from "jquery";
export default {
    data() {
        return {
            data: {
                name: "",
                course: null,
                subject: [],
                day: [],
                time: [],
                year: null,
                ay: "",
                sem: ""
            },
            addModal: false,
            isAdding: false,
            items: [],
            days: [],
            subjects: [],
            courses: [],
            years: [],
            times: [],
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
        async getSubjects() {
            if (!this.data.course) return this.error("Course is required");
            if (!this.data.year) return this.error("Year is required");
            const response = await this.callApi("post", "test", this.data);
            if (response.status === 201 || response.status === 200) {
                this.data.subject = response.data;
            } else {
                if (response.status === 422) {
                    for (let i in response.data.errors) {
                        this.error(response.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async store() {
            if (this.data.name.trim() == "")
                return this.error("Section name is required");
            if (!this.data.subject)
                return this.error("Section subjects is required");
            if (!this.data.course)
                return this.error("Section course is required");
            if (!this.data.year) return this.error("Section year is required");
            if (!this.data.day) return this.error("Days is required");
            if (!this.data.time) return this.error("Time is required");
            if (this.data.ay.trim() == "")
                return this.error("Annual Year is required");
            if (this.data.sem.trim() == "")
                return this.error("Semester is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/store_section",
                this.data
            );
            if (res.status === 201 || res.status === 200) {
                this.items.unshift(res.data);
                this.success("Section has been added successfully!");
                this.addModal = false;
                this.data.name = "";
                this.data.course = null;
                this.data.subject = [];
                this.data.year = null;
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
                return this.error("Section name is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                "app/update_section",
                this.editData
            );
            if (res.status === 200) {
                this.items[this.index] = this.editData;
                this.success("Section has been edited successfully!");
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
                deleteUrl: "app/destroy_section",
                data: { id: item.id },
                deletingIndex: i,
                isDeleted: false,
                msg: "Are you sure you want to delete this section?",
                successMsg: "Section has been deleted successfully!"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        }
    },

    async created() {
        this.$Loading.start();
        const [
            res,
            resSubject,
            resCourse,
            resYear,
            resDay,
            resTime
        ] = await Promise.all([
            this.callApi("get", "app/index_section"),
            this.callApi("get", "app/index_subject"),
            this.callApi("get", "app/index_course"),
            this.callApi("get", "app/index_year"),
            this.callApi("get", "app/index_day"),
            this.callApi("get", "app/index_time")
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
        if (resSubject.status === 200) {
            this.subjects = resSubject.data;
        } else {
            if (resSubject.status === 404) {
                this.error("No Subjects Found!");
            }
            this.swr();
        }
        if (resCourse.status === 200) {
            this.courses = resCourse.data;
        } else {
            if (resCourse.status === 404) {
                this.error("No Course Found!");
            }
            this.swr();
        }
        if (resYear.status === 200) {
            this.years = resYear.data;
        } else {
            if (resYear.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (resDay.status === 200) {
            this.days = resDay.data;
        } else {
            if (resDay.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
        if (resTime.status === 200) {
            this.times = resTime.data;
        } else {
            if (resTime.status === 404) {
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
