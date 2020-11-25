<template>
    <div>
        <div class="container pb-lg5">
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <div class="login_header pb-3">
                    <h2>Edit - {{ data.name }}</h2>
                </div>
                <Input
                    class="pb-2"
                    v-model="data.name"
                    placeholder="Add section name"
                />
                <Select
                    class="pb-2"
                    v-model="data.subject_id"
                    filterable
                    multiple
                    placeholder="Select subjects"
                >
                    <Option
                        :value="subject.id"
                        v-for="(subject, i) in subjects"
                        :key="i"
                        >{{ subject.name }}</Option
                    >
                </Select>

                <Select
                    class="pb-2"
                    v-model="data.course_id"
                    placeholder="Select course type"
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
                    v-model="data.year_id"
                    placeholder="Select year type"
                >
                    <Option
                        :value="year.id"
                        v-for="(year, i) in years"
                        :key="i"
                        v-if="years.length"
                        >{{ year.name }}</Option
                    >
                </Select>
                <div class="login_footer">
                    <Button type="default" @click="$router.push(`/section`)"
                        ><Icon type="ios-arrow-back" /> Go Back</Button
                    >
                    <Button
                        type="primary"
                        @click="update"
                        :disabled="isAdding"
                        :loading="isAdding"
                        >{{ isAdding ? "Editing.." : "Edit" }}</Button
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
                name: "",
                subject_id: [],
                course_id: [],
                year_id: []
            },
            isAdding: false,
            subjects: [],
            courses: [],
            years: []
        };
    },

    methods: {
        async update() {
            if (this.data.name.trim() == "")
                return this.error("Section name is required");
            if (!this.data.subject_id.length)
                return this.error("Subject is required");
            if (!this.data.course_id) return this.error("Course is required");
            if (!this.data.year_id) return this.error("Year is required");
            this.isAdding = true;
            const res = await this.callApi(
                "post",
                `/app/update_section/${this.$route.params.id}`,
                this.data
            );
            if (res.status === 200) {
                this.success("Section has been updated successfully!");
                // redirect...
                this.$router.push("/section");
            } else {
                if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
            this.isAdding = false;
        }
    },
    async created() {
        const id = parseInt(this.$route.params.id);
        if (!id) {
            return this.$router.push("/notfound");
        }
        const [section, subject, course, year] = await Promise.all([
            this.callApi("get", `/app/edit_section/${id}`),
            this.callApi("get", "/app/index_subject"),
            this.callApi("get", "/app/index_course"),
            this.callApi("get", "/app/index_year")
        ]);
        if (section.status === 200) {
            if (!section.data) return this.$router.push("/notfound");

            this.subjects = subject.data;
            this.courses = course.data;
            this.years = year.data;

            for (let i of section.data.subject) {
                this.data.subject_id.push(i.id);
            }
            for (let j of section.data.course) {
                this.data.course_id.push(j.id);
            }
            for (let k of section.data.year) {
                this.data.year_id.push(k.id);
            }
            this.data.name = section.data.name;
        }
    }
};
</script>

<style scoped>
._1adminOverveiw_table_recent {
    margin: 0 auto;
    margin-top: 50px;
    width: max-content;
}
.login_footer {
    text-align: right;
}
.login_header {
    text-align: left;
}
</style>
