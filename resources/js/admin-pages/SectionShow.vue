<template>
    <div>
        <div class="container pt-3">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div
                class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
            >
                <h3 class="pb-2">
                    {{ getSectionByIndex({ items, index: 0 }).section }}
                    <Button class="float-right" @click=" $router.push(`/section`)" 
                        ><Icon type="ios-arrow-back" /> Go Back</Button
                    >
                </h3>
                <strong class="flex-right"
                    ><p class="pb-2">
                        {{ getSectionByIndex({ items, index: 0 }).course }}
                        &nbsp; - &nbsp;
                        {{ getSectionByIndex({ items, index: 0 }).year }}
                        year
                    </p></strong
                >
                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Subjects</th>
                            <th>Subject Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
                        </tr>
                        <!-- TABLE TITLE -->
                        <!-- ITEMS -->
                        <tr
                            v-for="(item, i) in items"
                            :key="i"
                            v-if="items.length"
                        >
                            <td>{{ item.id }}</td>
                            <td class="_table_name">{{ item.subject }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.lec }}</td>
                            <td>{{ item.lab }}</td>
                        </tr>
                        <!-- ITEMS -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: []
        };
    },

    methods: {
        getSectionByIndex({ items = [], index = 0 }) {
            return items[index] || {};
        }
    },

    async created() {
        const id = parseInt(this.$route.params.id);
        if (!id) {
            return this.$router.push("/notfound");
        }
        const res = await this.callApi("get", `/app/show_section/${id}`);
        if (res.status === 200) {
            if (!res.data) return this.$router.push("/notfound");
            this.items = res.data;
            console.log(this.items);
        } else {
            if (res.status === 404) {
                this.error("No Records Found!");
            }
            this.swr();
        }
    }
};
</script>
